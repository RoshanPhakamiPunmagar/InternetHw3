<html>
<head>
    <title>GP Clinic Patient List</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1>GP Clinic Patient List</h1>

    <?php
    session_start(); // Start the session
    // Include the database connection file
    include 'db_connection.php';

    // Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit;
}

    // Default sorting field is patientid
    $sortBy = 'patientid';

    // Check if the form is submitted with a sorting field
    if (isset($_GET['sort_by'])) {
        $sortBy = $_GET['sort_by'];  // Get the selected sorting field from the form
    }

    // Form for sorting
    echo "<form method='GET' action='patients.php'>";
    echo "<label for='sort_by'>Sort By: </label>";
    echo "<select name='sort_by' id='sort_by'>";
    echo "<option value='first_name' " . ($sortBy == 'first_name' ? 'selected' : '') . ">First Name</option>";
    echo "<option value='last_name' " . ($sortBy == 'last_name' ? 'selected' : '') . ">Last Name</option>";
    echo "<option value='date_of_birth' " . ($sortBy == 'date_of_birth' ? 'selected' : '') . ">DOB</option>";
    echo "<option value='sex' " . ($sortBy == 'sex' ? 'selected' : '') . ">Sex</option>";
    echo "<option value='city' " . ($sortBy == 'city' ? 'selected' : '') . ">City</option>";
    echo "<option value='phone' " . ($sortBy == 'phone' ? 'selected' : '') . ">Phone</option>";
    echo "</select>";
    echo "<button type='submit'>Go</button>";
    echo "</form>";

    // Query to select patients, ordered by the selected field
    $query = "SELECT * FROM patients ORDER BY $sortBy";
    $result = $db->query($query);

    // Check if the query was successful
    if ($result) {
        $numResults = $result->num_rows;
    } else {
        // If there's an error in the query, display it
        echo "Error retrieving patients: " . $db->error;
        exit;
    }

    // Start the HTML table
    echo "<table border='1' cellpadding='5'>";
    echo "<thead>";
    echo "<tr>
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
          </tr>";
    echo "</thead>";
    echo "<tbody>";

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
        echo "<td valign='top'>$id</td>";
        echo "<td valign='top'>$firstName</td>";
        echo "<td valign='top'>$lastName</td>";
        echo "<td valign='top'>$dob</td>";
        echo "<td valign='top'>$sex</td>";
        echo "<td valign='top'>$address</td>";
        echo "<td valign='top'>$city</td>";
        echo "<td valign='top'>$phone</td>";

        // Create the edit and delete buttons for each patient
        createButtonColumn("id", $id, "Edit", "edit.php");
        createButtonColumn("id", $id, "Delete", "delete.php");
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    // Free the result set and close the database connection
    $result->free();
    $db->close();

    // Display links
    echo "<p>";
    echo "<a href='home.php'>Home</a> | ";
    echo "<a href='add.php'>Add New Patient</a> | ";
    echo "<a href='logout.php'>Log out</a>";
    echo "</p>";

    // Function to create edit and delete buttons
    function createButtonColumn($hiddenName, $hiddenValue, $buttonText, $actionPage) {
        echo "<td>";
        echo "<form action='$actionPage' method='GET'>";
        echo "<input type='hidden' name='$hiddenName' value='$hiddenValue'>";
        echo "<button type='submit'>$buttonText</button>";
        echo "</form>";
        echo "</td>";
    }
    ?>

	<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>


</body>
</html>
