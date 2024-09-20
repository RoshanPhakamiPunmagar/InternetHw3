<?php
$actionPage = "home.php"; // Set the action page to login.php

echo <<< END
<form method="post" action="$actionPage">
    <p>Username: <input type="text" name="username"></p>
    <p>Password: <input type="password" name="password"></p>
    <p><input type="submit" name="submit" value="Log In"></p>
</form>	
END;
?>
