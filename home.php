<?php
session_start();
require('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<h1>Welcome to the GP Clinic System</h1>";
    echo "<p>You must log in to manage patient data.</p>";
} else {
    // Fetch the logged-in user’s name from the database
    $username = $_SESSION['username'];
    $query = "SELECT username FROM authorized_users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    if ($row) {
        $username = $row['username'];
        echo "<h1>Welcome, $username!</h1>";
        echo "<p>You are now logged in. What would you like to do?</p>";
        echo "<ul>
                <li><a href='view_patients.php'>View All Patients</a></li>
                <li><a href='add_patient.php'>Add a New Patient</a></li>
              </ul>";
    }
}
?>

<!-- Footer -->
<footer>
    <p>
    <?php
    if (isset($_SESSION['username'])) {
        // Links for logged-in users
        echo "<a href='home.php'>Home</a> | ";
        echo "<a href='add_patient.php'>Add New Patient</a> | ";
        echo "<a href='logout.php'>Log out</a>";
    } else {
        // Links for non-logged-in users
        echo "<a href='home.php'>Home</a> | ";
        echo "<a href='login.php'>Login</a>";
    }
    ?>
    </p>
</footer>
