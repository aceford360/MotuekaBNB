<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database configuration here
include "config.php";
include "checksession.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Connect to the database
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to the database. " . mysqli_connect_error();
        exit;
    }

    // Check if the provided email exists in the database
    $query = "SELECT customerID, email, password, firstname, lastname FROM customer WHERE TRIM(email) = ?";
    $stmt = mysqli_prepare($db_connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $customerID, $fetchedEmail, $hashedPassword, $fetchedFirstName, $fetchedLastName);

    if (mysqli_stmt_fetch($stmt)) {
        // Verify the provided password against the hashed password in the database
        if (password_verify($password, $hashedPassword)) {
            // Start a session
            session_start();

            // Check if the user is an admin based on their email
            if (strpos($email, "@admin.com") !== false) {
                // This email is recognized as an admin
                $_SESSION['role'] = 'admin';
            } else {
                // Regular user
                $_SESSION['role'] = 'user';
            }

            // Other session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['customerID'] = $customerID;
            $_SESSION['email'] = $fetchedEmail;
            $_SESSION['firstname'] = $fetchedFirstName;
            $_SESSION['lastname'] = $fetchedLastName;

            // Redirect to index.php
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        // Check for database errors
        if (mysqli_error($db_connection)) {
            echo "Database error: " . mysqli_error($db_connection);
        } else {
            $error_message = "Email not found. Please check your email address.";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db_connection);
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Motueka BnB</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original_template/style/style.css"/>
</head>

<body>
    <div id="main">
        <div id="header">
            <div id="logo">
                <div id="logo_text">
                    <!-- class="logo_colour", allows you to change the colour of the text -->
                    <h1><a href="index.php">Motueka<span class="logo_colour">BnB</span></a></h1>
                    <h2>Welcome to the Motueka BnB</h2>
                </div>
            </div>
            <div id="menubar">
                <ul id="menu">
                    <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
                    <li><a href="index.php">Home</a></li>
                    <li class="selected"><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div id="site_content">
            <div id="content">
                <!-- insert the page content here -->
                <h1>Login</h1>

    <?php
    if (isset($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>

    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="submit" value="Log In">
    </form>
            </div>
        </div>
        <div id="footer">
           MotuekaBNB &copy;  2023
              
        </div>
    </div>
</body>

</html>
