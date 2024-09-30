<?php
/*
 * Author: Roshan Phakami PunMagar
 * File Name: logout.php
 * Date: 30/9/2024
 * Purpose:
 * This script handles user logout by destroying the session and unsetting the session variables.
 * After logging out, the user is presented with a confirmation message and given the option to log back in or return home.
 */

// Start the session to access session variables
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Clear the session variables by setting the session array to an empty array
    $_SESSION = array();
    
    // Destroy the session to fully log out the user
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta data for character set and responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>Logged Out</title>
</head>
<body>
    <div class="container">
        <!-- Header displaying the service name -->
        <h1>GP Clinic</h1>
        
        <!-- Display a message confirming that the user has logged out -->
        <p>Thank you for using our service. You have successfully logged out.</p>
        
       <!-- Footer with navigation links -->
<footer>
    <p>
    <?php
        // Display footer links for non-logged-in users (Home and Login)
        echo "<a href='home.php'>Home</a> | ";
        echo "<a href='login.php'>Login</a>";
    ?>
    </p>
</footer>

    </div>
</body>
</html>
