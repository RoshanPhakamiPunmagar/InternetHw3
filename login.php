<?php
/*
 * Author: Roshan Phakami PunMagar
 * File Name: login.php
 * Date: 30/9/2024
 * Purpose:
 * This script provides a login form for users to access the GP Clinic system.
 * It checks if the username and password are correct by querying the database. 
 * If valid credentials are provided, the user is logged in and redirected to the home page.
 * If invalid credentials are provided, an error message is shown.
 */

// Include the database connection file to connect to the MySQL database
include 'db_connection.php';

// Start the session to manage user login information
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    // If the user is already logged in, redirect them to the home page
    header('Location: home.php');
    exit();
}

// Check if the form has been submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve the form inputs (username and password)
    $name = $_POST['username'];
    $password = $_POST['password'];

    // Validate that both the username and password are provided
    if (!empty($name) && !empty($password)) {
        // Prepare the query to check if the provided credentials are correct
        $query = "SELECT * FROM authorized_users WHERE username=? AND password=SHA1(?)";
        $stmt = $db->prepare($query);  // Prepare the SQL query
        $stmt->bind_param('ss', $name, $password);  // Bind the username and password to the query
        $stmt->execute();  // Execute the query

        $result = $stmt->get_result();  // Get the result set

        // Check if exactly one matching user was found in the database
        if ($result->num_rows == 1) {
            // Valid login credentials, set the session variable for the user
            $_SESSION['username'] = $name;
            // Redirect to the home page after successful login
            header('Location: home.php');
            exit();
        } else {
            // Invalid login credentials, display an error message
            echo "<p>Username or password is incorrect.</p>";
        }

        // Close the prepared statement and the database connection
        $stmt->close();
        $db->close();
    } else {
        // If either the username or password is empty, display an error message
        echo "<p>Please enter both username and password.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<!-- Display the login form -->
<form method="POST" action="login.php">
    <p>Username: <input type="text" name="username"></p>  <!-- Input for username -->
    <p>Password: <input type="password" name="password"></p>  <!-- Input for password -->
    <p><input type="submit" value="Log In"></p>  <!-- Submit button -->
</form>

</body>
</html>
