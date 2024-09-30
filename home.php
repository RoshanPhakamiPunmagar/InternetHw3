<?php
/*
 * Author: Roshan Phakami PunMagar
 * File Name: home.php
 * Date: 30/9/2024
 * Purpose:
 * This script serves as the homepage of the GP Clinic system. 
 * It checks if a user is logged in, displays a welcome message, and provides
 * navigation options for managing patients. If the user is not logged in, it prompts
 * the user to log in. It also includes a footer with relevant links.
 */

// Include the database connection file to connect to the MySQL database
include 'db_connection.php';

// Start the session to manage user login information
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, display a welcome message and a prompt to log in
    echo "<h1>Welcome to the GP Clinic System</h1>";
    echo "<p>You are not logged in.</p>";
    echo "<p>You must log in to manage patient data.</p>";
} else {
    // If the user is logged in, fetch the logged-in user's name from the database
    $username = $_SESSION['username'];
    $query = "SELECT username FROM authorized_users WHERE username = ?";  // SQL query to fetch the username
    $stmt = $db->prepare($query);  // Prepare the query
    $stmt->bind_param('s', $username);  // Bind the username to the query
    $stmt->execute();  // Execute the query
    $result = $stmt->get_result();  // Get the result set
    $row = $result->fetch_assoc();  // Fetch the result as an associative array
    $stmt->close();  // Close the prepared statement

    // If the user exists in the database, display a welcome message and navigation links
    if ($row) {
        $username = $row['username'];
        echo "<h1>Welcome, $username!</h1>";  // Display a personalized welcome message
        echo "<p>You are now logged in. What would you like to do?</p>";
        // Provide links to view all patients or add a new patient
        echo "<ul>
                <li><a href='patients.php'>View All Patients</a></li>
                <li><a href='add.php'>Add a New Patient</a></li>
              </ul>";
    }
}
?>

<!-- Footer -->
<footer>
    <p>
    <?php
    // If the user is logged in, show links to Home, Add New Patient, and Log out
    if (isset($_SESSION['username'])) {
        echo "<a href='home.php'>Home</a> | ";  // Link to Home
        echo "<a href='add.php'>Add New Patient</a> | ";  // Link to Add Patient page
        echo "<a href='logout.php'>Log out</a>";  // Link to log out
    } else {
        // If the user is not logged in, show links to Home and Login pages
        echo "<a href='home.php'>Home</a> | ";  // Link to Home
        echo "<a href='login.php'>Login</a>";  // Link to Login page
    }
    ?>
    </p>
</footer>
