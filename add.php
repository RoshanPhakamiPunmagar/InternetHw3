<!--
 * Author: Roshan Phakami PunMagar
 * File Name: add.php
 * Date: 30/9/2024
 * Purpose:
 * This script handles the functionality for adding a new patient to the GP Clinic system.
 * It includes form validation, inserting data into the patients table in the database,
 * and providing options for users to either submit or cancel the form. If the form
 * is submitted successfully, a new patient is added to the system. 
-->

<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1>Add Patient</h1>

    <?php
    session_start(); // Start a new session or resume an existing one

    // Include the database connection file to connect to the MySQL database
    include 'db_connection.php';

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to login page if the user is not logged in
        header("Location: login.php");
        exit;
    }
    
    // Check if the form has been submitted
    if (isset($_POST['submit'])) {
        $submit = $_POST['submit'];  // Get the value of the submit button

        // Handle the Cancel action
        if ($submit == "Cancel") {
            $db->close(); // Close the database connection
            header('location: home.php'); // Redirect to the home page
            exit;
        }

        // Validate that all required fields are filled
        if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['dob']) ||
            empty($_POST['sex']) || empty($_POST['address']) || empty($_POST['city']) || empty($_POST['phone'])) {
            echo "Error: All fields are required.";  // Display error if any field is missing
            $db->close();  // Close the database connection
            exit;  // Stop script execution
        }

        // Assign form values to variables
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $dob = $_POST['dob'];
        $sex = $_POST['sex'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];

        // Prepare an SQL query to insert patient data into the database
        $query = "INSERT INTO patients (first_name, last_name, date_of_birth, sex, address, city, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);  // Prepare the SQL statement for execution
        $stmt->bind_param("sssssss", $firstName, $lastName, $dob, $sex, $address, $city, $phone);  // Bind parameters to the query
        $stmt->execute();  // Execute the SQL query

        // Check if the insertion was successful
        if ($stmt->affected_rows == 1) {
            // Display success message and link back to patient list
            echo "Successfully Added Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
        } else {
            // Display failure message and link back to patient list
            echo "Failed to Add Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
        }

        $stmt->close();  // Close the prepared statement
        $db->close();    // Close the database connection
        exit;  // Exit the script
    } else {
        // If the form has not been submitted, display the form for adding a patient
        echo <<<END
        <form action="" method="POST">
            <table>
                <tr>
                    <td>First Name:</td>
                    <td><input type="text" name="first_name" required></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><input type="text" name="last_name" required></td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td><input type="date" name="dob" required></td>
                </tr>
                <tr>
                    <td>Sex:</td>
                    <td>
                        <select name="sex" required>
                            <option value="M">M</option>
                            <option value="F">F</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" required></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city" required></td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td><input type="text" name="phone" required></td>
                </tr>
            </table>
            <br>
            <!-- Submit and Cancel buttons -->
            <input type="submit" name="submit" value="Add">
            <input type="submit" name="submit" value="Cancel">
        </form>
END;
    }

    $db->close(); // Close the database connection
    ?>
</body>
</html>
