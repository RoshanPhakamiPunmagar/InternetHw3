<!--
 * Author: Roshan Phakami PunMagar
 * File Name: db_connection.php
 * Date: 30/9/2024
 * Purpose:
 * This script establishes a connection to the 'gp_clinic' MySQL database 
 * using the provided credentials (database address, username, and password). 
 * If the connection fails, an error message is displayed, and script execution is halted.
-->

<?php
    // Database connection credentials
    $dbAddress = 'localhost';  // The address of the database server (localhost indicates the local server)
    $dbUser = 'webauth';       // The username for authenticating to the database
    $dbPass = 'webauth';       // The password associated with the database user
    $dbName = 'gp_clinic';     // The name of the database to which we are connecting

    // Establish a new connection to the MySQL database
    $db = new mysqli($dbAddress, $dbUser, $dbPass, $dbName);

    // Check if there is a connection error
    if ($db->connect_error) {
        // Display an error message if the connection fails
        echo "Could not connect to the database. Please try again later.";
        // Terminate the script to prevent further execution if the connection fails
        exit;
    }
?>
