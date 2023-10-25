<?php
// Include database connection configuration
include 'db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $designation = $_POST['designation'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $query = "INSERT INTO users (name, username, designation, password, role) VALUES (?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $username, $designation, $hashed_password, $role);

    if (mysqli_stmt_execute($stmt)) {
        // Registration successful, redirect to login page
        header("location: login.html");
    } else {
        // Handle registration error (e.g., duplicate username)
        echo "Error: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>