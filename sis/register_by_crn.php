<?php
session_start();
global $pdo;
include 'dbconf.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Retrieve CRN and semester_id from POST data
$crn = trim($_POST['crn'] ?? '');
$semester_id = 1;

// Validate and sanitize inputs
if (empty($crn) || empty($semester_id)) {
echo "CRN and Semester ID are required.";
exit;
}

// Simulated student_id, replace with actual session value or database retrieval
$student_id = 1;

// Check if the course exists for the given semester
$stmt = $pdo->prepare("
SELECT course_id, course_code, credit_units, lecturer, course_info
FROM registration
WHERE course_id = :crn AND semester_id = :semester_id
");
$stmt->execute([
'crn' => $crn,
'semester_id' => $semester_id
]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if ($course) {
// Register the course
$stmt = $pdo->prepare("
INSERT INTO student_registration (student_id, course_id)
VALUES (:student_id, :course_id)
ON DUPLICATE KEY UPDATE
student_id = :student_id,
course_id = :course_id
");

if ($stmt->execute([
'student_id'   => $student_id,
'course_id'    => $course['course_id']
])) {
echo "Course with CRN " . htmlspecialchars($crn) . " registered successfully!";
} else {
echo "Failed to register course with CRN " . htmlspecialchars($crn);
}
} else {
echo "No course found with CRN " . htmlspecialchars($crn) . " for the selected semester.";
}
} else {
echo "Invalid request.";
}
?>