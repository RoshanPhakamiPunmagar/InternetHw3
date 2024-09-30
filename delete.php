<!--
 * Author: Roshan Phakami PunMagar
 * File Name: delete.php
 * Date: 30/9/2024
 * Purpose:
 * This script handles the deletion of a patient's record from the GP Clinic system.
 * It first confirms the patient's details before deletion and gives the user the 
 * option to either delete the record or cancel the action. If the patient is successfully
 * deleted, a confirmation message is displayed. Otherwise, an error message is shown.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Delete Patient</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1>Delete Patient</h1>

    <?php
    session_start(); // Start the session to track user login status

    // Include the database connection file to connect to the MySQL database
    include 'db_connection.php';

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        // If not logged in, redirect the user to the login page
        header("Location: login.php");
        exit;
    }

    // Get the patient ID from the URL parameters
    $patientID = $_GET['id'];

    // Check if the form has been submitted (for Delete or Cancel)
    if (isset($_POST['submit'])) {
        $submit = $_POST['submit'];  // Get the value of the submit button

        // Handle the Cancel action
        if ($submit == "Cancel") {
            $db->close(); // Close the database connection
            header('location: patients.php'); // Redirect to the patient list
            exit;
        }

        // Prepare the SQL query to delete the patient from the database
        $query = "DELETE FROM patients WHERE patientid = ?";
        $stmt = $db->prepare($query); // Prepare the SQL statement
        $stmt->bind_param("i", $patientID); // Bind the patient ID parameter
        $stmt->execute(); // Execute the deletion

        $affectedRows = $stmt->affected_rows; // Get the number of affected rows
        $stmt->close(); // Close the prepared statement
        $db->close(); // Close the database connection

        // Check if the deletion was successful
        if ($affectedRows == 1) {
            // Display success message
            echo "Successfully Deleted Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
            exit;
        } else {
            // Display failure message
            echo "Failed to Delete Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
            exit;
        }
    } else {
        // Fetch the patient details to display for confirmation before deletion
        $query_patient_details = "SELECT * FROM patients WHERE patientid = ?";
        $stmt_patient_details = $db->prepare($query_patient_details); // Prepare SQL query
        $stmt_patient_details->bind_param("i", $patientID); // Bind the patient ID parameter
        $stmt_patient_details->execute(); // Execute the query

        $result = $stmt_patient_details->get_result(); // Get the result of the query
        $stmt_patient_details->close(); // Close the prepared statement

        // Check if the patient exists in the database
        if ($result->num_rows === 0) {
            // If no patient found, display an error message
            echo "No patient found with ID $patientID.";
            exit;
        }

        // Fetch the patient's details for display in the confirmation form
        $row = $result->fetch_assoc();
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $dob = $row['date_of_birth'];
        $sex = $row['sex'];
        $address = $row['address'];
        $city = $row['city'];
        $phone = $row['phone'];

        // Display the patient's details in a form for deletion confirmation
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
            <!-- Hidden input field to pass patient ID -->
            <input type="hidden" name="patientid" value=$patientID>
            <!-- Submit buttons for Delete and Cancel actions -->
            <input type="submit" name="submit" value="Delete">
            <input type="submit" name="submit" value="Cancel">
        </form>
END;

        $result->free(); // Free the result set
    }
    $db->close(); // Close the database connection
    ?>
</body>
</html>
