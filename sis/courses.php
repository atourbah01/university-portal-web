<?php
global $pdo;
// Include the database configuration file
include 'dbconf.php';

// Function to fetch all semesters
function getSemesters($pdo) {
    $stmt = $pdo->query("SELECT DISTINCT semester_id FROM registration ORDER BY semester_id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function to fetch courses based on the selected semester
function getCourses($pdo, $semester) {
    $stmt = $pdo->prepare("SELECT DISTINCT (course_id), course_code, course_title, credit_units, lecturer, course_info FROM registration WHERE semester_id = :semester_id");
    $stmt->execute(['semester_id' => $semester]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all semesters
$semesters = getSemesters($pdo);
$selectedSemester = isset($_POST['semester_id']) ? $_POST['semester_id'] : '';
if ($selectedSemester) {
    $courses = getCourses($pdo, $selectedSemester);
}

?>

<!DOCTYPE html>
<html>
<head>
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

<h1>Course Information</h1>

<!-- Form to select semester -->
<form method="post" action="">
    <label for="semester_id">Select Semester:</label>
    <select name="semester_id" id="semester_id" onchange="this.form.submit()">
        <option value="">-- Select a Semester --</option>
        <?php foreach ($semesters as $semester): ?>
            <option value="<?php echo htmlspecialchars($semester); ?>" <?php echo ($semester === $selectedSemester) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($semester); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Display courses if a semester is selected -->
<?php if ($selectedSemester): ?>
    <h2>Courses for <?php echo htmlspecialchars($selectedSemester); ?></h2>
    <table>
        <thead>
        <tr>
            <th>Course ID</th>
            <th>Course Code</th>
            <th>Course title</th>
            <th>Credit Units</th>
            <th>Lecturer</th>
            <th>Course Info</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($course['credit_units']); ?></td>
                    <td><?php echo htmlspecialchars($course['lecturer']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_info']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No courses available for this semester.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
