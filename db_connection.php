<?php
	$dbAddress = 'localhost';
	$dbUser = 'webauth';
	$dbPass = 'webauth';
	$dbName = 'gp_clinic';

	$db = new mysqli($dbAddress, $dbUser, $dbPass, $dbName);
	if ($db->connect_error) {
		echo "Could not connect to the database.  Please try again later.";
		exit;
	}
?>
 