<?php
session_start();
include 'dbconf.php';
global $pdo;
$user_id = $personnel_id = $PWreset = $password = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $personnel_id = $_POST['personnel_id'];
    $PWreset = isset($_POST['PWreset']) ? 1 : 0;
    $password = $_POST['password'];
    $_SESSION['user_id']=$user_id;


    $sql = "INSERT INTO user (user_id, personnel_id, PWreset, password) VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$user_id, $personnel_id, $PWreset, password_hash($password, PASSWORD_DEFAULT)])) {
        $successMessage = "User information saved successfully!";
        // Redirect to courses.php after successful insertion
        header("Location: dashboard.php");
    } else {
        $successMessage = "Error saving user information!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #444;
        }

        input[type="text"], input[type="password"], input[type="checkbox"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .success-message {
            margin-top: 20px;
            color: green;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>User Information Form</h2>

        <?php if ($successMessage): ?>
            <div class="success-message"><?= $successMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="home.php">
            <input type="text" name="user_id" placeholder="User ID" required>
            <input type="text" name="personnel_id" placeholder="Personnel ID" required>
            <label>
                <input type="checkbox" name="reset"> Reset Password
            </label>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
