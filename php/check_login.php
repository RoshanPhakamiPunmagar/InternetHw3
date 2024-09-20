<?php

	if (isset($_POST['name']) || isset($_POST['password'])) {
		if (!isset($_POST['name']) || empty($_POST['name'])) {
			echo "Name not supplied";
			return false;
		}
		if (!isset($_POST['password']) || empty($_POST['password'])) {
			echo "Password not supplied";
			return false;
		}

		require('db_connection.php');
		$name = $_POST['name'];
		$password = $_POST['password'];

		
		$query = "SELECT count(*) 
				  FROM authorized_users 
				  WHERE name=? AND password= sha1(?)";
				  
		$stmt = $db->prepare($query);
		$stmt->bind_param("ss", $name, $password);
		$stmt->execute();
		
		$result = $stmt->get_result();
		$stmt->close();

		if (!$result) {
			echo "Couldn't check credentials";
			$db->close();
			exit;
		}
		
		$row = $result->fetch_row();
		
		if ($row[0] > 0) {
			$db->close();
			return true;
		}
		else {
			echo "Username and Password Incorrect<br>";
			$db->close();
			return false;
		}		
	}	
	return false;
?>
