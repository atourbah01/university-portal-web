<?php
session_start();
global $pdo;
include 'dbconf.php';

// Function to fetch all semesters
function getSemesters($pdo) {
    $stmt = $pdo->query("SELECT DISTINCT semester_id FROM registration ORDER BY semester_id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function to fetch courses based on the selected semester and course code
function getCourses($pdo, $semester, $courseCode = '') {
    $sql = "SELECT DISTINCT(course_id), course_code, course_title, credit_units, lecturer, course_info 
            FROM registration 
            WHERE semester_id = :semester_id";
    $params = ['semester_id' => $semester];

    if ($courseCode) {
        $sql .= " AND course_code LIKE :course_code";
        $params['course_code'] = '%' . $courseCode . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all semesters
$semesters = getSemesters($pdo);
$courses = [];

// Simulated student_id, replace with actual session value or database retrieval
$student_id = 1;

// Handle course fetching based on semester and course code
/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['semester_id'])) {
    $selectedSemester = $_POST['semester_id'];
    $courseCode = isset($_POST['course_code']) ? $_POST['course_code'] : '';
    // Fetch the courses for the selected semester
    $courses = getCourses($pdo, $selectedSemester);
    // Ensure we return the data in JSON format
    header('Content-Type: application/json');
    echo json_encode($courses);
    exit;
}*/
$selectedOption = isset($_POST['registration_type']) ? $_POST['registration_type'] : '';
$selectedSemester = isset($_POST['semester_id']) ? $_POST['semester_id'] : '';
$courseCode = isset($_POST['course_code']) ? $_POST['course_code'] : '';

// Fetch courses based on the selected semester and course code
if ($selectedSemester) {
    $courses = getCourses($pdo, $selectedSemester, $courseCode);
}

// Handle course registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_ids'])) {
    $selectedCourses = $_POST['course_ids'];
    $allCourses = array_column($courses, 'course_id');

    if (!empty($selectedCourses)) {
        // Track processed course IDs to avoid duplicate insertions
        $processedCourses = [];
        foreach ($selectedCourses as $course_id) {
            // Skip if this course ID has already been processed
            if (in_array($course_id, $processedCourses)) {
                continue;
            }
            // Mark this course ID as processed
            $processedCourses[] = $course_id;

            foreach ($courses as $course) {
                if ($course['course_id'] == $course_id) {

                    // Insert selected course into registration table with student_id
                    $stmt = $pdo->prepare("
                        INSERT INTO student_registration (student_id, course_id)
                        VALUES (:student_id, :course_id)
                        ON DUPLICATE KEY UPDATE 
                            student_id = VALUES(student_id),
                            course_id = VALUES(course_id)"); // Prevent duplicate entries


                   if ($stmt->execute([
                        'student_id'   => $student_id,
                        'course_id'    => $course['course_id']
                    ])){
                       echo "Course ID " . htmlspecialchars($course_id ?: '') . " registered successfully!<br>";
                   } else {
                       echo "Failed to register Course ID " . htmlspecialchars($course_id ?: '') . "<br>";
                   }
                }
            }
        }
    }
    $uncheckedCourses = array_diff($allCourses, $selectedCourses);
    // Courses that were unchecked
    if (!empty($uncheckedCourses)) {
        foreach ($uncheckedCourses as $course_id) {
            // First check if the student has registered for this course
            $stmt = $pdo->prepare("SELECT 1 FROM student_registration WHERE student_id = :student_id AND course_id = :course_id LIMIT 1");
            $stmt->execute(['student_id' => $student_id, 'course_id' => $course_id]);
            // If the course is registered, delete it
            if ($stmt->fetch()) {
                $deleteStmt = $pdo->prepare("DELETE FROM student_registration WHERE student_id = :student_id AND course_id = :course_id");
                if ($deleteStmt->execute(['student_id' => $student_id, 'course_id' => $course_id])) {
                    echo "Course ID " . htmlspecialchars($course_id ?: '') . " deregistered successfully!<br>";
                } else {
                    echo "Failed to deregister Course ID " . htmlspecialchars($course_id ?: '') . "<br>";
                }
            }
        }
    }
    else {
        echo "No courses were selected.";
    }
    // Return the courses in JSON format
   /* header('Content-Type: application/json');
    echo json_encode($courses);
    exit;*/
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <style>
        .nav {
            background-color: #333;
            overflow: hidden;
        }
        .nav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .nav a:hover {
            background-color: #ddd;
            color: black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
    <script>
        function fetchCourses() {
            const formData = new FormData(document.getElementById('semesterForm'));
            fetch('', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(courses => {
                    console.log(courses);
                    let courseTable = document.getElementById('courseTable');
                    courseTable.innerHTML = ''; // Clear the table content

                    if (courses.length === 0) {
                        courseTable.innerHTML = '<tr><td colspan="6">No courses available for this semester.</td></tr>';
                    } else {
                        courses.forEach(course => {
                            let row = `
                            <tr>
                                <td>${course.course_id}</td>
                                <td>${course.course_code}</td>
                                <td>${course.course_title}</td>
                                <td>${course.credit_units}</td>
                                <td>${course.lecturer}</td>
                                <td>${course.course_info}</td>
                            </tr>
                        `;
                            courseTable.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
<!-- Navigation bar -->
<div class="nav">
    <a href="register.php">Register</a>
    <a href="courses.php">Courses</a>
</div>

<h1>Course Registration</h1>

<!-- Form to select registration type -->
<form method="post" action="">
    <label for="registration_type">Register by:</label>
    <select name="registration_type" id="registration_type" onchange="this.form.submit()">
        <option value="">-- Select Registration Method --</option>
        <option value="CRN" <?php echo ($selectedOption === 'CRN') ? 'selected' : ''; ?>>Register by CRN</option>
        <option value="search" <?php echo ($selectedOption === 'search') ? 'selected' : ''; ?>>Register by Searching Courses</option>
    </select>
</form>

<?php if ($selectedOption === 'CRN'): ?>
    <!-- If the user selects to register by CRN -->
    <form method="post" action="register_by_crn.php">
        <input type="hidden" name="semester_id" value="<?php echo htmlspecialchars($selectedSemester ?: ''); ?>">
        <label for="crn">Enter CRN:</label>
        <input type="text" name="crn" id="crn" required>
        <button type="submit">Register</button>
    </form>

<?php elseif ($selectedOption === 'search'): ?>
    <!-- If the user selects to register by searching -->
    <form method="post" action="">
        <!-- Retain the selected registration type when selecting a semester -->
        <input type="hidden" name="registration_type" value="search">
        <label for="semester_id">Select Semester:</label>
        <select name="semester_id" id="semester_id" onchange="this.form.submit()"><!--this.form.submit()-->
            <option value="">-- Select a Semester --</option>
            <?php foreach ($semesters as $semester): ?>
                <option value="<?php echo htmlspecialchars($semester); ?>" <?php echo ($semester === $selectedSemester) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($semester); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selectedSemester): ?>
        <form method="post" action="">
            <!-- Retain the registration type and selected semester -->
            <input type="hidden" name="registration_type" value="search">
            <input type="hidden" name="semester_id" value="<?php echo htmlspecialchars($selectedSemester); ?>">

            <!-- Allow user to search by course code -->
            <label for="course_code">Enter Course Code:</label>
            <input type="text" name="course_code" id="course_code" value="<?php echo htmlspecialchars($courseCode); ?>" required>
            <button type="submit">Search</button>
        </form>

        <!-- Display courses if search results are available -->
        <?php if (!empty($courses)): ?>
            <h2>Courses for <?php echo htmlspecialchars($selectedSemester); ?></h2>
            <form method="post" action="">
                <input type="hidden" name="registration_type" value="search">
                <input type="hidden" name="semester_id" value="<?php echo htmlspecialchars($selectedSemester); ?>">
                <input type="hidden" name="course_code" value="<?php echo htmlspecialchars($courseCode); ?>">

                <table>
                    <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Credit Units</th>
                        <th>Lecturer</th>
                        <th>Course Info</th>
                        <th>Register</th> <!-- Checkbox for registration -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['course_id'] ?: ''); ?></td>
                            <td><?php echo htmlspecialchars($course['course_code'] ?: ''); ?></td>
                            <td><?php echo htmlspecialchars($course['course_title'] ?: ''); ?></td>
                            <td><?php echo htmlspecialchars($course['credit_units'] ?: ''); ?></td>
                            <td><?php echo htmlspecialchars($course['lecturer'] ?: ''); ?></td>
                            <td><?php echo htmlspecialchars($course['course_info'] ?: ''); ?></td>
                            <td>
                                <input type="checkbox" name="course_ids[]" value="<?php echo htmlspecialchars($course['course_id'] ?: ''); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Save Changes</button>
            </form>
        <?php else: ?>
            <p>No courses found for the given course code.</p>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>