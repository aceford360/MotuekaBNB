<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php"; 
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

// check if the connection was successful
if (mysqli_connect_errno()) {
    echo "<h2 style='color: white;'>Error: Unable to connect to MySQL. " . mysqli_connect_error() . "</h2>";
    exit;
}

//clean input but not validate type and content
function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// retrieve the Booking ID from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2 style='color: white;'>Invalid Booking ID</h2>"; 
        exit;
    }
}

$successMessage = "";
$errorMessage = "";

// check if the data was submitted using a form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check if the delete button is clicked
    if (isset($_POST['submit']) && $_POST['submit'] == 'Delete') {
        $error = 0; 
        $msg = 'Error: ';

        // clean and validate Booking ID before deleted
        if (isset($_POST['id']) && !empty($_POST['id']) && is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']);
        } else {
            $error++; 
            $msg .= 'Invalid Booking ID ';
            $id = 0;
        }

        // delete the booking if there are no errors
        if ($error == 0 && $id > 0) {
            $query = "DELETE FROM booking WHERE bookingID=?";
            $stmt = mysqli_prepare($db_connection, $query); 
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // check if the booking was deleted
            if (mysqli_error($db_connection)) {
                // get error message if there was an error with the query
                $errorMessage = "<h2>Error deleting booking: " . mysqli_error($db_connection) . "</h2>";
            } else {
                // get success message if the booking was deleted
                $successMessage = "<h2>Booking deleted successfully.</h2>";
            }
        } else { 
            // get error message
            $errorMessage = "<h2'>$msg</h2>".PHP_EOL;
        }      
    }
}

// query and send it to the server
$query = 'SELECT * FROM booking WHERE bookingID=' . $id;
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result);

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
                    <li class="selected"><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div id="site_content">
            <div id="content">
                
        <?php

        // show booking info and redirect links
        if ($rowcount > 0) {  
            echo"<h1>Are you sure you want to delete this Booking?</h1>".PHP_EOL;
            echo"<h2><a href='booking_list.php>[Return to the Booking listing]
            </a><a href='index.php'>[Return to the main page]</a></h2>".PHP_EOL;
            echo "<fieldset><legend>Booking detail #$id</legend><dl>"; 
            $row = mysqli_fetch_assoc($result);
            echo "<dt>Start Date:</dt><dd>".$row['startDate']."</dd>".PHP_EOL;
            echo "<dt>End Date:</dt><dd>".$row['endDate']."</dd>".PHP_EOL;
            echo "<dt>Contact Number:</dt><dd>".$row['contactNumber']."</dd>".PHP_EOL;
            echo "<dt>Room ID:</dt><dd>".$row['roomID']."</dd>".PHP_EOL; 
            echo "<dt>Customer ID:</dt><dd>".$row['customerID']."</dd>".PHP_EOL; 
            echo "</dl></fieldset>".PHP_EOL;
        ?>
        <form method="POST" action="delete_booking.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submit" value="Delete">
            <a href="booking_list.php">[Cancel]</a>
        </form>
        <?php
        } else {
            // show success or error message accordingly
            if (!empty($successMessage)) {
                echo "<h2>$successMessage</h2>";
                echo"<h2><a href='booking_list.php'[Return to the Booking listing]</a><a href='index.php'>[Return to the main page]</a></h2>".PHP_EOL;
            } else {
                echo "<h2>No Booking found, possibly deleted!</h2>"; // Suitable feedback
                echo"<h2><a href='booking_list.php'>[Return to the Booking listing]</a><a href='index.php'>[Return to the main page]</a></h2>".PHP_EOL;
            }
        }

        // close the database connection
        mysqli_close($db_connection);
        ?>
            </div>
        </div>
        <div id="footer">
           MotuekaBNB &copy;  2023
              
        </div>
    </div>
</body>

</html>
