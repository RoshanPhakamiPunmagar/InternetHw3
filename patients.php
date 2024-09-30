<!--
 * Author: Roshan Phakami PunMagar
 * File Name: patients.php 
 * Date: 30/9/2024
 * Purpose: This file displays a list of patients in the GP Clinic system. 
 *          It allows logged-in users to sort the patient list and provides options 
 *          to edit or delete patient records. 
 *          Users are redirected to the login page if they are not authenticated.
 -->
 <html>
<head>
    <title>GP Clinic Patient List</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1>GP Clinic Patient List</h1>

    <?php
    session_start(); // Start the session to access session variables
    
    // Include the database connection file
    include 'db_connection.php';

    // Check if the user is logged in; if not, redirect to the login page
    if (!isset($_SESSION['username'])) {
        header("Location: login.php"); // Redirect to login if not authenticated
        exit;
    }

    // Set the default sorting field to patient ID
    $sortBy = 'patientid';

    // Check if a sorting field has been submitted via GET request
    if (isset($_GET['sort_by'])) {
        $sortBy = $_GET['sort_by']; // Update sorting field based on user selection
    }

    // Display the sorting form
    echo "<form method='GET' action='patients.php'>";
    echo "<label for='sort_by'>Sort By: </label>";
    echo "<select name='sort_by' id='sort_by'>";
    // Create options for sorting based on various fields
    echo "<option value='first_name' " . ($sortBy == 'first_name' ? 'selected' : '') . ">First Name</option>";
    echo "<option value='last_name' " . ($sortBy == 'last_name' ? 'selected' : '') . ">Last Name</option>";
    echo "</select>";
    echo "<button type='submit'>Go</button>"; // Submit button for sorting
    echo "</form>";

    // Query to select patients ordered by the selected field
    $query = "SELECT * FROM patients ORDER BY $sortBy";
    $result = $db->query($query); // Execute the query

    // Check if the query was successful
    if ($result) {
        $numResults = $result->num_rows; // Get the number of rows returned

        // Check if there are any patient records
        if ($numResults > 0) {
            // Start the HTML table if there are records
            echo "<table border='1' cellpadding='5'>";
            echo "<thead>";
            // Table header with column titles
            echo "<tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Sex</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Phone</th>
                    <th></th> <!-- Column for Edit button -->
                    <th></th> <!-- Column for Delete button -->
                  </tr>";
            echo "</thead>";
            echo "<tbody>";

            // Fetch each patient's details and display them in a table row
            for ($i = 0; $i < $numResults; $i++) {
                $row = $result->fetch_assoc(); // Fetch the current row as an associative array
                $id = $row['patientid'];
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                $dob = $row['date_of_birth'];
                $sex = $row['sex'];
                $address = $row['address'];
                $city = $row['city'];
                $phone = $row['phone'];

                // Output the patient data in table cells
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
                echo "</tr>"; // End of the patient row
            }

            echo "</tbody>";
            echo "</table>"; // End of the patient table
        } else {
            // Display a message if no patients were found
            echo "<p>Sorry, no patient records were found.</p>";
        }
    } else {
        // If there's an error in the query, display it
        echo "Error retrieving patients: " . $db->error;
        exit;
    }

    // Free the result set and close the database connection
    $result->free();
    $db->close();

    // Display links for navigation
    echo "<p>";
    echo "<a href='home.php'>Home</a> | ";
    echo "<a href='add.php'>Add New Patient</a> | ";
    echo "<a href='logout.php'>Log out</a>";
    echo "</p>";

    // Function to create edit and delete buttons
    function createButtonColumn($hiddenName, $hiddenValue, $buttonText, $actionPage) {
        echo "<td>"; // Start of the table cell for the button
        echo "<form action='$actionPage' method='GET'>"; // Form for the button action
        echo "<input type='hidden' name='$hiddenName' value='$hiddenValue'>"; // Hidden field to pass patient ID
        echo "<button type='submit'>$buttonText</button>"; // Button for edit/delete action
        echo "</form>";
        echo "</td>"; // End of the table cell
    }
    ?>

    <!-- Code injected by live-server for live reloading -->
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
    } else {
        console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    }
    // ]]>
    </script>
</body>
</html>
