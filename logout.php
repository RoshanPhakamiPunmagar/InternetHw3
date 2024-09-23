<?php


		// Include the database connection file
		include 'db_connection.php';
        
session_start(); // Start the session

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the home page
    header("Location: home.php");
    exit;
} else {
    // If the user is not logged in, redirect to home
    header("Location: home.php");
    exit;
}
?>
