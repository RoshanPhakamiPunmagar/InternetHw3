<!--
 * Author: Roshan Phakami PunMagar
 * File Name: edit.php
 * Date: 30/9/2024
 * Purpose:
 * This script allows users to edit patient records in the GP Clinic system.
 * The patient's existing details are fetched from the database, pre-populated in the form,
 * and can be updated by the user. If the user cancels the update, they are redirected
 * back to the patient list. The system validates the required fields and updates the database
 * if any changes are made. After updating, it notifies the user of success or failure.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1>Edit Patient</h1>

    <?php
    session_start(); // Start the session to track the user's login status

    // Include the database connection file to connect to the MySQL database
    include 'db_connection.php';

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to the login page if the user is not logged in
        header("Location: login.php");
        exit;
    }

    // Get the patient ID from the URL parameters
    $patientID = $_GET['id'];

    // If the form is submitted, process the form data
    if (isset($_POST['submit'])) {
        $submit = $_POST['submit'];  // Get the value of the submit button

        // Handle the Cancel action and redirect to the patient list
        if ($submit == "Cancel") {
            $db->close(); // Close the database connection
            header('location: view_patients.php'); // Redirect to the patient list
            exit;
        }

        // Validate required fields to ensure no empty data is submitted
        if (!isset($_POST['first_name']) || empty($_POST['first_name']) ||
            !isset($_POST['last_name']) || empty($_POST['last_name']) ||
            !isset($_POST['date_of_birth']) || empty($_POST['date_of_birth']) ||
            !isset($_POST['sex']) || empty($_POST['sex']) ||
            !isset($_POST['address']) || empty($_POST['address']) ||
            !isset($_POST['city']) || empty($_POST['city'])) {
            // Display error message if any field is missing
            echo "Error: All fields must be filled.";
            $db->close(); // Close the database connection
            exit;
        }

        // Collect data from the form inputs
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $dob = $_POST['date_of_birth'];
        $sex = $_POST['sex'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];

        // Fetch current patient details for comparison before updating
        $queryCheck = "SELECT * FROM patients WHERE patientid = ?";
        $stmtCheck = $db->prepare($queryCheck); // Prepare the SQL query
        $stmtCheck->bind_param("i", $patientID); // Bind the patient ID
        $stmtCheck->execute(); // Execute the query
        $resultCheck = $stmtCheck->get_result(); // Get the result set
        $currentData = $resultCheck->fetch_assoc(); // Fetch the current data
        $stmtCheck->close(); // Close the statement

        // Debugging output: Display current patient details (can be removed in production)
        echo "Current Data: <br>";
        echo "First Name: {$currentData['first_name']}<br>";
        echo "Last Name: {$currentData['last_name']}<br>";
        echo "DOB: {$currentData['date_of_birth']}<br>";
        echo "Sex: {$currentData['sex']}<br>";
        echo "Address: {$currentData['address']}<br>";
        echo "City: {$currentData['city']}<br>";
        echo "Phone: {$currentData['phone']}<br>";

        // Prepare the SQL query to update the patient's details
        $query = "UPDATE patients 
                  SET first_name=?, last_name=?, date_of_birth=?, sex=?, address=?, city=?, phone=? 
                  WHERE patientid = ?";
        $stmt = $db->prepare($query); // Prepare the SQL query
        $stmt->bind_param("sssssssi", $firstName, $lastName, $dob, $sex, $address, $city, $phone, $patientID); // Bind parameters

        // Execute the query and check for errors
        if (!$stmt->execute()) {
            // Display SQL error if the query fails
            echo "SQL Error: " . $stmt->error;
            $stmt->close(); // Close the statement
            $db->close(); // Close the database connection
            exit;
        }

        $affectedRows = $stmt->affected_rows; // Get the number of affected rows
        $stmt->close(); // Close the statement
        $db->close(); // Close the database connection

        // Check if the update was successful or if no changes were made
        if ($affectedRows == 1) {
            // Display success message if the update was successful
            echo "Successfully Updated Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
            echo "<br><hr>";
            exit;
        } else if ($affectedRows == 0) {
            // Display message if no changes were made (the data was identical)
            echo "No changes were made. The data was identical to what is already in the database.<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
            echo "<br><hr>";
            exit;
        } else {
            // Display failure message if the update failed
            echo "Failed to Update Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
            echo "<br><hr>";
            exit;
        }
    } else {
        // Fetch patient details to pre-populate the form with the current data
        $queryPatientDetails = "SELECT * FROM patients WHERE patientid = ?";
        $stmtPatientDetails = $db->prepare($queryPatientDetails); // Prepare the SQL query
        $stmtPatientDetails->bind_param("i", $patientID); // Bind the patient ID
        $stmtPatientDetails->execute(); // Execute the query
        $result = $stmtPatientDetails->get_result(); // Get the result set
        $stmtPatientDetails->close(); // Close the statement

        $row = $result->fetch_assoc(); // Fetch the current patient data

        // Extract patient details for form population
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $dob = $row['date_of_birth'];
        $sex = $row['sex'];
        $address = $row['address'];
        $city = $row['city'];
        $phone = $row['phone'];

        // Display the form pre-populated with the patient's current details
        echo <<<END
        Editing Patient with ID: <strong>$patientID</strong><br><br>
        <form action="" method="POST">
            <table>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="first_name" value="$firstName"></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="last_name" value="$lastName"></td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td><input type="date" name="date_of_birth" value="$dob"></td>
                </tr>
                <tr>
                    <td>Sex:</td>
                    <td>
                        <select name="sex">
                            <option value="M" . ($sex == 'M' ? 'selected' : '') . >M</option>
                            <option value="F" . ($sex == 'F' ? 'selected' : '') . >F</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" value="$address"></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city" value="$city"></td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td><input type="text" name="phone" value="$phone"></td>
                </tr>
            </table>
            <br>
            <input type="hidden" name="patientid" value=$patientID>
            <input type="submit" name="submit" value="Submit Changes">
            <input type="submit" name="submit" value="Cancel">
        </form>
END;
        $result->free(); // Free the result set
    }
    $db->close(); // Close the database connection
    ?>
</body>
</html>