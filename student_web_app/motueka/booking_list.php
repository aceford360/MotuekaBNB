<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php"; 
// connect to the database
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// check to see if the connection is successful
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit; 
}

// join data together in table
$query = "SELECT booking.*, CONCAT(customer.firstName, ' ', customer.lastName) AS customerFullName, room.roomname
          FROM booking
          INNER JOIN customer ON booking.customerID = customer.customerID
          INNER JOIN room ON booking.roomID = room.roomID";

$result = mysqli_query($db_connection, $query);

//checking if the query was successful
if (!$result) {
    die("Database query failed.");
}
?>

<?php
// close the database connection
mysqli_close($db_connection);
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>List of bookings</title>
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
            <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: white;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div id="bookingtable">
    <h1>List of current bookings</h1>
        <table>
            <tr>
                <!-- table headers -->
                <th>Booking (room, dates)</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>
            <?php
            //display booking data in table rows
            while ($row = mysqli_fetch_assoc($result)) {
                $bookingID = $row['bookingID'];
                $roomname = $row['roomname'];
                $startDate = $row['startDate'];
                $endDate = $row['endDate'];
                $customerFullName = $row['customerFullName'];
            
                echo "<tr>";
                echo "<td>$roomname, $startDate, $endDate</td>";
                echo "<td>$customerFullName</td>";
                echo "<td><a href='booking_details.php?roomname=" . urlencode($roomname) . "&bookingID=$bookingID'>View</a> | ";
                echo "<a href='edit_booking.php?id=$bookingID'>Edit</a> | ";
                echo "<a href='reviews.php?bookingID=$bookingID'>Manage reviews</a> | "; // Pass the bookingID as a parameter
                echo "<a href='delete_booking.php?id=$bookingID'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
            </div>
        </div>
        <div id="footer">
            Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a
                href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a
                href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
        </div>
    </div>
</body>

</html>
