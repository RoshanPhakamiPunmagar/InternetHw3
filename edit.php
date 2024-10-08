<!-- 
 * Author: Roshan Phakami PunMagar
 * File Name: edit.php
 * Date: 30/9/2024
 * Purpose:
 * This script allows the admin to edit a patient's details in the GP Clinic system.
 * It fetches the patient's current details from the database based on the patient ID supplied via GET.
 * After editing, the form is submitted, and the data is validated and updated in the database.
 * The admin also has the option to cancel the editing and return to the patient list.
-->

<html>
    <head>
        <title>Edit Patient</title>
        <meta charset="UTF-8"/>
    </head>
    <body>
        <h1>Edit Patient</h1>
        
        <?php
        // Start the session to manage user login information
        session_start();

        // Include the database connection file to connect to the MySQL database
        include 'db_connection.php';

        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            // If not logged in, redirect the user to the login page
            header("Location: login.php");
            exit;
        }
        
        // Check if patient ID is supplied
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo "Error: Patient ID not supplied.<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
            $db->close();
            exit;
        }
        $patientId = $_GET['id'];       
        
        // If form is submitted, process the form data
        if (isset($_POST['submit'])) {
            $submit = $_POST['submit'];
            
            // Cancel and redirect to patient list if Cancel button is clicked
            if ($submit == "Cancel") {
                $db->close();
                header('location: view_patients.php');
                exit;
            }
            
            // Validate that required fields are provided
            if (!isset($_POST['first-name']) || empty($_POST['first-name']) || 
                !isset($_POST['last-name']) || empty($_POST['last-name']) || 
                !isset($_POST['date-of-birth']) || empty($_POST['date-of-birth']) || 
                !isset($_POST['sex']) || empty($_POST['sex']) || 
                !isset($_POST['address']) || empty($_POST['address']) || 
                !isset($_POST['city']) || empty($_POST['city'])) {
                echo "Error: All fields must be filled.<br>";
                echo "<a href=\"patients.php\">Back to Patient List</a>";
                $db->close();
                exit;
            }

            // Collect data from form inputs
            $firstName = $_POST['first-name'];
            $lastName = $_POST['last-name'];
            $dob = $_POST['date-of-birth'];
            $sex = $_POST['sex'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $phone = $_POST['phone'];

            // Fetch current patient details for comparison
            $queryCheck = "SELECT * FROM patients WHERE patientid = ?";
            $stmtCheck = $db->prepare($queryCheck);
            $stmtCheck->bind_param("i", $patientId);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $currentData = $resultCheck->fetch_assoc();
            $stmtCheck->close();
            
            // Debugging output: Check current values
            echo "Current Data: <br>";
            echo "First Name: {$currentData['first_name']}<br>";
            echo "Last Name: {$currentData['last_name']}<br>";
            echo "DOB: {$currentData['date_of_birth']}<br>";
            echo "Sex: {$currentData['sex']}<br>";
            echo "Address: {$currentData['address']}<br>";
            echo "City: {$currentData['city']}<br>";
            echo "Phone: {$currentData['phone']}<br>";

            // Update patient details in the database
            $query = "UPDATE patients 
                      SET first_name=?, last_name=?, date_of_birth=?, sex=?, address=?, city=?, phone=? 
                      WHERE patientid = ?";
            
            $stmt = $db->prepare($query);
            $stmt->bind_param("sssssssi", $firstName, $lastName, $dob, $sex, $address, $city, $phone, $patientId);
            if (!$stmt->execute()) {
                echo "SQL Error: " . $stmt->error;
                $stmt->close();
                $db->close();
                exit;
            }
            
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            $db->close();
            
            // Check if the update was successful or no changes were made
            if ($affectedRows == 1) {
                echo "Successfully Updated Patient<br>";
                echo "<a href=\"patients.php\">Back to Patient List</a>";
                echo "<br><hr>";
                exit;     
            } else if ($affectedRows == 0) {
                echo "No changes were made. The data was identical to what is already in the database.<br>";
                echo "<a href=\"patients.php\">Back to Patient List</a>";
                echo "<br><hr>";
                exit;
            } else {
                echo "Failed to Update Patient<br>";
                echo "<a href=\"patients.php\">Back to Patient List</a>";
                echo "<br><hr>";
                exit;               
            }
        } else {
            // Fetch patient details for the given patient ID
            $queryPatientDetails = "SELECT * FROM patients WHERE patientid = ?";
            $stmtPatientDetails = $db->prepare($queryPatientDetails);
            $stmtPatientDetails->bind_param("i", $patientId);
            
            $stmtPatientDetails->execute();
            $result = $stmtPatientDetails->get_result();
            $stmtPatientDetails->close();
            
            $row = $result->fetch_assoc();
            
            // Extract patient details from the result
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $dob = $row['date_of_birth'];
            $sex = $row['sex'];
            $address = $row['address'];
            $city = $row['city'];
            $phone = $row['phone'];
            
            // Display the edit form pre-populated with patient details
            echo <<<END
            Editing Patient with ID: <strong>$patientId</strong><br><br>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>First Name:</td>
                        <td><input type="text" id="first-name" name="first-name" value="$firstName"></td>
                    </tr>
                    <tr>
                        <td>Last Name:</td>
                        <td><input type="text" id="last-name" name="last-name" value="$lastName"></td>
                    </tr>
                    <tr>
                        <td>Date of Birth:</td>
                        <td><input type="date" id="date-of-birth" name="date-of-birth" value="$dob"></td>
                    </tr>
                    <tr>
                        <td>Sex:</td>
                        <td>
                            <select id="sex" name="sex">
                                <option value="M" . ($sex == 'Male' ? 'selected' : '') . >M</option>
                                <option value="F" . ($sex == 'Female' ? 'selected' : '') . >F</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><input type="text" id="address" name="address" value="$address"></td>
                    </tr>
                    <tr>
                        <td>City:</td>
                        <td><input type="text" id="city" name="city" value="$city"></td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td><input type="text" id="phone" name="phone" value="$phone"></td>
                    </tr>
                </table>
                <br>
                <input type="hidden" name="patient-id" value="$patientId">
                <input type="submit" name="submit" value="Submit Changes">
                <input type="submit" name="submit" value="Cancel">
            </form>
END;
            $result->free(); // free the result set
        }
        $db->close(); // close the database connection
        ?>          
    </body>
</html>
