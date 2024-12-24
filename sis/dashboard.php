<?php
// Start session and include necessary files (e.g., database connection, header)
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

// You can include necessary files here like database connection
include 'dbconf.php'; // Assuming you have a header.php file for navigation and layout

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Import Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            height: 100vh;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .dashboard-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
        }

        .dashboard-card i {
            font-size: 40px;
            color: #4CAF50;
        }

        .dashboard-card h2 {
            margin-top: 10px;
            font-size: 1.5rem;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }

        .dashboard-card p {
            color: #777;
            font-size: 1rem;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .dashboard-header h1 {
            font-size: 3rem;
            color: #fff;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            font-family: 'Montserrat', sans-serif;
        }

        /* Subtitle Styling */
        .dashboard-header p {
            font-size: 1.25rem;
            color: #f0f0f0;
            margin-bottom: 20px;
            font-weight: 300;
            letter-spacing: 1px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            font-family: 'Poppins', sans-serif;
        }

        /* Link Styling */
        .dashboard-card a {
            text-decoration: none;
            color: inherit;
        }

        a:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>
<div class="dashboard-header">
    <h1>Welcome to the Dashboard, <?php echo $_SESSION['user_id']; ?>!</h1>
    <p><strong>Navigate through the sections below</p>
</div>
<div class="dashboard-container">
    <div class="dashboard-card">
        <a href="courses.php">
            <i class="fas fa-book"></i>
            <h2>Course Registration</h2>
            <p>Register or drop courses</p>
        </a>
    </div>

    <div class="dashboard-card">
        <a href="profile.php">
            <i class="fas fa-user"></i>
            <h2>Profile</h2>
            <p>View and edit your profile</p>
        </a>
    </div>

    <div class="dashboard-card">
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <h2>Logout</h2>
            <p>Sign out of your account</p>
        </a>
    </div>
</div>
</body>
</body>
</html>
