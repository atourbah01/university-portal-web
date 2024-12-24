<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Assuming you have the necessary data for the user profile.
//$user_id = $_SESSION['user_id'];
$profile_data = [
    'user_id' => 1,
    'personnel_id' => 'P123456',
    'first_name' => 'John',
    'middle_name' => 'A.',
    'last_name' => 'Doe',
    'email' => 'johndoe@example.com',
    'matriculation_number' => 'M123456',
    'department_id' => 'D001',
    'profile_picture' => 'https://live.staticflickr.com/65535/48968273437_2de6a4fa6d_b.jpg' // Example profile picture
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle profile picture update logic here.
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Save new profile picture logic
        // ...
        //$profile_data['profile_picture'] = 'new_picture.jpg'; // Assume new picture is saved and updated.
        // Validate and move uploaded file
        $upload_dir = '../Desktop'; // Adjust this to your upload directory
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;

        // Check if the file is an actual image
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check !== false) {
            // Move the uploaded file to the desired directory
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                // Update the profile data with the new picture path
                $profile_data['profile_picture'] = $target_file;
                // Optionally, you could also save this new path in the database here
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            position: relative;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: auto;
            width: 500px;
            border: 1px solid #ccc;
            height: 107%;
            text-align: center;
        }

        .profile-container h2 {
            margin-top: 0;
            font-weight: bold;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #4CAF50;
        }

        .profile-container input[type="file"] {
            display: none;
        }

        .profile-container label[for="profile_picture"] {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .profile-container label[for="profile_picture"]:hover {
            background-color: #45a049;
        }

        .profile-container form div {
            margin-bottom: 15px;
            text-align: left;
        }

        .profile-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .profile-container input[type="text"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f7f7f7;
            color: #333;
            font-size: 0.9rem;
        }

        .profile-container input[readonly] {
            background-color: #f7f7f7;
            cursor: not-allowed;
            width: 95%;
        }

        /* Save Button styling */
        .save-btn-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .profile-container button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .profile-container button:hover {
            background-color: #45a049;
        }

        /* Close Button (X) */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #f44336;
        }
    </style>
    <script>
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const imgUrl = URL.createObjectURL(file);
                    document.getElementById('profile-picture').src =imgUrl;
            }
        }
    </script>
</head>
<body>

<div class="profile-container">
    <!-- Close button (X) -->
    <button class="close-btn" onclick="window.location.href='dashboard.php';">&times;</button>

    <!-- Profile Picture -->
    <img src="<?php echo htmlspecialchars($profile_data['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
    <form action="profile.php" method="POST" enctype="multipart/form-data">
        <label for="profile_picture">Change Profile Picture</label>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(this);">

    <!-- Profile Information (Locked Fields) -->
        <div>
            <label for="user_id">User ID</label>
            <input type="text" id="user_id" value="<?php echo htmlspecialchars($profile_data['user_id']); ?>" readonly>
        </div>
        <div>
            <label for="personnel_id">Personnel ID</label>
            <input type="text" id="personnel_id" value="<?php echo htmlspecialchars($profile_data['personnel_id']); ?>" readonly>
        </div>
        <div>
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" value="<?php echo htmlspecialchars($profile_data['first_name']); ?>" readonly>
        </div>
        <div>
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" value="<?php echo htmlspecialchars($profile_data['middle_name']); ?>" readonly>
        </div>
        <div>
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" value="<?php echo htmlspecialchars($profile_data['last_name']); ?>" readonly>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" value="<?php echo htmlspecialchars($profile_data['email']); ?>" readonly>
        </div>
        <div>
            <label for="matriculation_number">Matriculation Number</label>
            <input type="text" id="matriculation_number" value="<?php echo htmlspecialchars($profile_data['matriculation_number']); ?>" readonly>
        </div>
        <div>
            <label for="department_id">Department ID</label>
            <input type="text" id="department_id" value="<?php echo htmlspecialchars($profile_data['department_id']); ?>" readonly>
        </div>

        <!-- Save button centered below department_id field -->
        <div class="save-btn-container">
            <button type="submit">Save</button>
        </div>
    </form>
</div>

</body>
</html>
