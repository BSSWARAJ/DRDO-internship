<?php
// Start the session
session_start();

// Check if the user is logged in (check if the user's session exists)
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, you can redirect them to the login page
    header("location: login.html");
    exit;
}

// Your existing dashboard code here
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            color: #007bff;
        }

        .container {
            background-color: #fff;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <p>Hello, <?php echo $_SESSION['username']; ?>!</p>
        <a href="logout.php">Logout</a> <!-- Add a logout link to log out the user -->
    </div>
</body>
</html>