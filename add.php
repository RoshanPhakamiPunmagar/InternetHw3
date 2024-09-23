<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <meta charset="UTF-8"/>
</head>
<body>
    <h1>Add Patient</h1>

    <?php
    session_start();
   
		// Include the database connection file
		include 'db_connection.php';

        //check if the user is logged in
        if(!isset($_SESSION['username'])){
            //redirect to the login page if not logged in
            header("Location:login.php");
            exit;
        }
        
    // Check if the form has been submitted
    if (isset($_POST['submit'])) {
        $submit = $_POST['submit'];

        // Handle the Cancel action
        if ($submit == "Cancel") {
            $db->close();
            header('location: home.php'); // Redirect back to the home page
            exit;
        }

        // Validate required fields
        if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['dob']) ||
            empty($_POST['sex']) || empty($_POST['address']) || empty($_POST['city']) || empty($_POST['phone'])) {
            echo "Error: All fields are required.";
            $db->close();
            exit;
        }

        // Assign form values to variables
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $dob = $_POST['dob'];
        $sex = $_POST['sex'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];

        // Insert patient data into the database
        $query = "INSERT INTO patients (first_name, last_name, date_of_birth, sex, address, city, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssssss", $firstName, $lastName, $dob, $sex, $address, $city, $phone);
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows == 1) {
            echo "Successfully Added Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
        } else {
            echo "Failed to Add Patient<br>";
            echo "<a href=\"patients.php\">Back to Patient List</a>";
        }

        $stmt->close();
        $db->close();
        exit;
    } else {
        // Display the form for adding a patient
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
                    <td>
                        <input type="date" name="dob" required>
                    </td>
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
            <input type="submit" name="submit" value="Add">
            <input type="submit" name="submit" value="Cancel">
        </form>
END;
    }

    $db->close();
    ?>
</body>
</html>
