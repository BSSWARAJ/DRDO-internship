<?php
// Include database connection configuration
include 'db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to retrieve user data
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the provided password with the hashed password from the database
        if (password_verify($password, $user['password'])) {
            // Check if the selected role matches the user's role
            $selectedRole = $_POST['role'];
            if ($selectedRole == $user['role']) {
                // Start a session and store user data
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to the dashboard or some other page
                header("location: obj.html");
            } else {
                // Role doesn't match
                echo "You do not have the required role to log in.";
            }
        } else {
            // Password is incorrect
            echo "Incorrect password. Please try again.";
        }
    } else {
        // Username not found
        echo "Username not found. Please check your username or register if you are a new user.";
    }
}

// Close the database connection
mysqli_close($conn);
?>