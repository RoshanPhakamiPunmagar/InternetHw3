<html>
	<head>
		<title>GP Clinic Patient List</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>GP Clinic Patient List</h1>

		<?php
			// Database connection details
			$dbAddress = 'localhost';
			$dbUser = 'webauth';
			$dbPass = 'webauth';
			$dbName = 'gp_clinic';
			
			$db = new mysqli($dbAddress, $dbUser, $dbPass, $dbName);
			
			if ($db->connect_error) {
				echo "Could not connect to the database";
				exit;
			}

			// Query to select patients, ordered by first and last name
			$query = "SELECT * FROM patients ORDER BY first_name, last_name";
			$result = $db->query($query);
			$numResults = $result->num_rows;
		?>

		<?php
			echo <<<END
				<table border="1" cellpadding="5">
					<thead>
						<tr>
							<th>Patient ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Date of Birth</th>
							<th>Sex</th>
							<th>Address</th>
							<th>City</th>
							<th>Phone</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
END;
			
				// Fetch each patient's details and display them in a table row
				for ($i = 0; $i < $numResults; $i++) {
					$row = $result->fetch_assoc();
					$id = $row['patientid'];
					$firstName = $row['first_name'];
					$lastName = $row['last_name'];
					$dob = $row['date_of_birth'];
					$sex = $row['sex'];
					$address = $row['address'];
					$city = $row['city'];
					$phone = $row['phone'];	
				
					// Output the patient data
					echo "<tr>";
					echo "<td valign=\"top\">$id</td>";
					echo "<td valign=\"top\">$firstName</td>";
					echo "<td valign=\"top\">$lastName</td>";
					echo "<td valign=\"top\">$dob</td>";
					echo "<td valign=\"top\">$sex</td>";
					echo "<td valign=\"top\">$address</td>";
					echo "<td valign=\"top\">$city</td>";
					echo "<td valign=\"top\">$phone</td>";

					// Create the edit and delete buttons for each patient
					createButtonColumn("id", $id, "Edit", "edit_patient.php");
					createButtonColumn("id", $id, "Delete", "delete_patient.php");
					echo "</tr>";					
				}

				// Free result set and close the database connection
				$result->free();
				$db->close();

				echo "</tbody></table>";
				echo "<br><a href=\"add_patient.php\">Add New Patient</a>";
				
				// Function to create edit and delete buttons
				function createButtonColumn($hiddenName, $hiddenValue, $buttonText, $actionPage) {
					echo "<td>";
					echo "<form action=\"$actionPage\" method=\"GET\">";
					echo "<input type=\"hidden\" name=\"$hiddenName\" value=\"$hiddenValue\">";					
					echo "<button type=\"submit\">$buttonText</button>";
					echo "</form>";			
					echo "</td>";
				}
		?>

	</body>
</html>
