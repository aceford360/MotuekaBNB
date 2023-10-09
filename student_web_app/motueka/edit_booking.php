<?php
include "config.php"; 
include "cleaninput.php"; 

$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
$error = 0;
$msg = "";

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit; 
}

// retrieve the booking ID from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $bookingID = $_GET['id'];
    if (empty($bookingID) || !is_numeric($bookingID)) {
        echo "<h2>Invalid Booking ID</h2>"; 
        exit;
    }
}


if (isset($_POST['submit']) && !empty($_POST['submit']) && ($_POST['submit'] == 'Update')) {

    // validate and clean incoming data
    if (isset($_POST['bookingID']) && !empty($_POST['bookingID']) && is_integer(intval($_POST['bookingID']))) {
        $bookingID = cleanInput($_POST['bookingID']);
    } else {
        $error++;
        $msg .= 'Invalid Booking ID ';
        $bookingID = 0;
    }

    $roomID = cleanInput($_POST['roomID']);
    $startDate = cleanInput($_POST['startDate']);
    $endDate = cleanInput($_POST['endDate']);
    $contactNumber = cleanInput($_POST['contactNumber']);
    $extras = cleanInput($_POST['extras']);

    //check if the error flag is still clear and both IDs are greater than 0
    if ($error == 0 && $bookingID > 0) {
        //update the booking data in the database
        $query = "UPDATE booking SET roomID=?, startDate=?, endDate=?, contactNumber=?, extras=? WHERE bookingID=?";
        $stmt = mysqli_prepare($db_connection, $query);
        mysqli_stmt_bind_param($stmt, 'sssssi', $roomID, $startDate, $endDate, $contactNumber, $extras, $bookingID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<h2>Booking details updated.</h2>";
    } else {
        echo "<h2>$msg</h2>";
    }
}

//fetch the booking data based on the booking ID
$query = "SELECT * FROM booking WHERE bookingID = $bookingID";
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    $row = mysqli_fetch_assoc($result);

    //fetch available rooms from the database
    $roomQuery = "SELECT roomID, roomname, roomtype, beds FROM room";
    $roomResult = mysqli_query($db_connection, $roomQuery);
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
                <!-- insert the page content here -->
                <h1>Edit Booking</h1>

    <form method="POST" action="edit_booking.php">
        <input type="hidden" name="bookingID" value="<?php echo $row['bookingID']; ?>">
        
        <label for="roomID">Room:</label>
        <select id="roomID" name="roomID">
            <?php
            while ($room = mysqli_fetch_assoc($roomResult)) {
                $selected = ($room['roomID'] == $row['roomID']) ? 'selected' : '';
                $roomInfo = "{$room['roomname']} (Type: {$room['roomtype']}, Beds: {$room['beds']})";
                echo "<option value='{$room['roomID']}' $selected>$roomInfo</option>";
            }
            ?>
        </select><br><br>
        
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" value="<?php echo $row['startDate']; ?>"><br><br>
        
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate" value="<?php echo $row['endDate']; ?>"><br><br>

        <label for="contactNumber">Contact Number:</label>
        <input type="text" id="contactNumber" name="contactNumber" value="<?php echo $row['contactNumber']; ?>"><br><br>

        <label for="extras">Booking Extras:</label>
        <input type="text" id="extras" name="extras" value="<?php echo $row['extras']; ?>"><br><br>
        
        <input type="submit" name="submit" value="Update">
    </form>
            </div>
        </div>
        <div id="footer">
           MotuekaBNB &copy;  2023
              
        </div>
    </div>
</body>

</html>

<?php
} else {
    echo "<h2>Booking not found with that ID</h2>";
}

mysqli_close($db_connection);
?>
