<?php
session_start();

if (isset($_SESSION['username'])) {
    // If user is already logged in, redirect to home page
    header('Location: home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If form is submitted, handle login
    require('db_connection.php');

    $name = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($password)) {
        // Prepare the query to check the user credentials
        $query = "SELECT * FROM authorized_users WHERE username=? AND password=SHA1(?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $name, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Valid user, set session and redirect to home
            $_SESSION['username'] = $name;
            header('Location: home.php');
            exit();
        } else {
            // Invalid login credentials
            echo "<p>Username or password is incorrect.</p>";
        }

        $stmt->close();
        $db->close();
    } else {
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
<form method="POST" action="login.php">
    <p>Username: <input type="text" name="username"></p>
    <p>Password: <input type="password" name="password"></p>
    <p><input type="submit" value="Log In"></p>
</form>

</body>
</html>
