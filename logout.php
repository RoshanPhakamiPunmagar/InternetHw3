<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Unset all of the session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
</head>
<body>
    <div class="container">
        <h1>GP Clinic</h1>
        <p>Thank you for using our service. You have successfully logged out.</p>
        
       <!-- Footer -->
<footer>
    <p>
    <?php
        // Links for non-logged-in users
        echo "<a href='home.php'>Home</a> | ";
        echo "<a href='login.php'>Login</a>";
    ?>
    </p>
</footer>

        
    </div>
</body>
</html>
