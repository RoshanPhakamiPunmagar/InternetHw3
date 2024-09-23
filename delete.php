<html>
	<head>
		<title>Delete Patient</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>Delete Patient</h1>
		
		<?php
		session_start(); //start the session
			
		// Include the database connection file
		include 'db_connection.php';

		//check if the user is already logged in
		if(!isset($_SESSION['username'])){
			//Redirect to the login page if the user is not already logged in
			header("Location: login.php");
			exit;
		}
		

			$patientID = $_GET['id'];		
			
			// Check if form is submitted (Delete or Cancel)
			if (isset($_POST['submit'])) {
				$submit = $_POST['submit'];
				if ($submit == "Cancel") {
					$db->close();
					header('location: patients.php');
					exit;
				}		
				
				// Prepare query to delete patient
				$query = "DELETE FROM patients WHERE patientid = ?";
				$stmt = $db->prepare($query);
				$stmt->bind_param("i", $patientID);
				$stmt->execute();				
				$affectedRows = $stmt->affected_rows;
				$stmt->close();
				$db->close();
				
				// Check if the deletion was successful
				if ($affectedRows == 1) {
					echo "Successfully Deleted Patient<br>";
					echo "<a href=\"patients.php\">Back to Patient List</a>";
					exit;		
				} else {
					echo "Failed to Delete Patient<br>";
					echo "<a href=\"patients.php\">Back to Patient List</a>";
					exit;				
				}
			}
			else {
				// Fetch patient details for display before deletion
				$query_patient_details = "SELECT * FROM patients WHERE patientid = ?";
				$stmt_patient_details = $db->prepare($query_patient_details);
				$stmt_patient_details->bind_param("i", $patientID);
				$stmt_patient_details->execute();
				
				$result = $stmt_patient_details->get_result();
				$stmt_patient_details->close();
				
				// Check if patient exists
				if ($result->num_rows === 0) {
					echo "No patient found with ID $patientID.";
					exit();
				}
				
				$row = $result->fetch_assoc();
				
				// Get patient details for confirmation display
				$firstName = $row['first_name'];
				$lastName = $row['last_name'];
				$dob = $row['date_of_birth'];
				$sex = $row['sex'];
				$address = $row['address'];
				$city = $row['city'];
				$phone = $row['phone'];
				
				echo <<<END
				Delete Patient with ID: <strong>$patientID</strong><br><br>
				<form action="" method="POST">
					<table>
						<tr>
							<td>First Name:</td>
							<td>$firstName</td>
						</tr>
						<tr>
							<td>Last Name:</td>
							<td>$lastName</td>
						</tr>
						<tr>
							<td>Date of Birth:</td>
							<td>$dob</td>
						</tr>
						<tr>
							<td>Sex:</td>
							<td>$sex</td>
						</tr>
						<tr>
							<td>Address:</td>
							<td>$address</td>
						</tr>
						<tr>
							<td>City:</td>
							<td>$city</td>
						</tr>
						<tr>
							<td>Phone:</td>
							<td>$phone</td>
						</tr>
					</table>
					<br>
					<input type="hidden" name="patientid" value=$patientID>
					<input type="submit" name="submit" value="Delete">
					<input type="submit" name="submit" value="Cancel">
				</form>
END;
				$result->free();
			}
			$db->close();
		?>			
	</body>
</html>
