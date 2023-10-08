<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

// connect to the database
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// check if the connection was successful
if (!$db_connection) {
    die("Error: Unable to connect to MySQL " . mysqli_connect_errno() . "= " . mysqli_connect_error());
}

// Check if both room name and booking ID are provided in the URL
if (!isset($_GET['roomname']) || !isset($_GET['bookingID'])) {
    die("Invalid Room Name or Booking ID.");
}

$roomname = $_GET['roomname'];
$bookingID = $_GET['bookingID'];

// Fetch booking data from the database for the given room name and booking ID
$query = "SELECT booking.*, customer.firstName, customer.lastName
        FROM booking
        INNER JOIN customer ON booking.customerID = customer.customerID
        WHERE roomname = '$roomname' AND bookingID = $bookingID";
$result = mysqli_query($db_connection, $query);

// check if the query was successful
if (!$result) {
    die("Database query failed.");
}

$row = mysqli_fetch_assoc($result);

//close the database connection
mysqli_close($db_connection);
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Booking details</title>
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
                    <li class="selected"><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div id="site_content">
            <div id="content">
            <h1>Booking Details</h1>
        <p>Booking ID: <?php echo $row['bookingID']; ?></p>
        <p>Start Date: <?php echo $row['startDate']; ?></p>
        <p>End Date: <?php echo $row['endDate']; ?></p>
        <p>Contact Number: <?php echo $row['contactNumber']; ?></p>
        <p>Room ID: <?php echo $row['roomID']; ?></p>
        <p>Customer: <?php echo $row['firstName'] . ' ' . $row['lastName']; ?></p>
        <p>Room name: <?php echo $row['roomname']; ?></p>
            </div>
        </div>
        <div id="footer">
           MotuekaBNB &copy;  2023
              
        </div>
    </div>
</body>

</html>
