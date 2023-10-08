<?php
include "config.php"; 
include "cleaninput.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate incoming data
    $errors = [];

    // Room ID
    if (isset($_POST['roomID']) && is_numeric($_POST['roomID'])) {
        $roomID = $_POST['roomID'];
    } else {
        $errors[] = "Invalid room ID.";
    }
    if (isset($_POST['startDate']) && !empty($_POST['startDate'])) {
        $startDate = cleanInput($_POST['startDate']);
    } else {
        $errors[] = "Start Date is required.";
    }
    if (isset($_POST['endDate']) && !empty($_POST['endDate'])) {
        $endDate = cleanInput($_POST['endDate']);
    } else {
        $errors[] = "End Date is required.";
    }
    if (isset($_POST['contactNumber']) && !empty($_POST['contactNumber'])) {
        $contactNumber = cleanInput($_POST['contactNumber']);
        
        // Validate that the contact number contains only numbers
        if (!preg_match("/^[0-9]+$/", $contactNumber)) {
            $errors[] = "Contact Number must contain only numbers.";
        }
    } else {
        $errors[] = "Contact Number is required.";
    }

    // Extras
    $extras = isset($_POST['extras']) ? cleanInput($_POST['extras']) : null;

    // Check if the user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        if ($_SESSION['role'] === 'admin') {
            // If an admin is logged in, they can book for a selected customer
            if (isset($_POST['customerID']) && is_numeric($_POST['customerID'])) {
                $customerID = $_POST['customerID'];
            } else {
                $errors[] = "Invalid customer ID.";
            }
        } else {
            // For regular customers, use their customerID from the session
            $customerID = $_SESSION['customerID'];
        }
    } else {
        $errors[] = "User is not logged in.";
    }

    // If there are no errors, insert the booking data into the database
    if (empty($errors)) {
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
            exit;
        }

        // Fetch the room data based on roomID
        $room_query = "SELECT roomname, beds FROM room WHERE roomID = ?";
        $room_stmt = mysqli_prepare($db_connection, $room_query);
        mysqli_stmt_bind_param($room_stmt, 'i', $roomID);
        mysqli_stmt_execute($room_stmt);
        mysqli_stmt_bind_result($room_stmt, $fetchedRoomname, $roomBeds);

        if (mysqli_stmt_fetch($room_stmt)) {
            // Close the result set for the room query
            mysqli_stmt_close($room_stmt);

            // Check if the selected room has enough 'stays' for the requested dates
            $date_query = "SELECT COUNT(*) FROM booking WHERE roomID = ? AND startDate <= ? AND endDate >= ?";
            $date_stmt = mysqli_prepare($db_connection, $date_query);
            mysqli_stmt_bind_param($date_stmt, 'iss', $roomID, $endDate, $startDate);
            mysqli_stmt_execute($date_stmt);
            mysqli_stmt_bind_result($date_stmt, $bookedBeds);

            if (mysqli_stmt_fetch($date_stmt)) {
                mysqli_stmt_close($date_stmt);

                if (($bookedBeds + 1) <= $roomBeds) {
                    // Insert the booking data 
                    $query = "INSERT INTO booking (startDate, endDate, contactNumber, extras, roomID, roomname, customerID)
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($db_connection, $query);
                    mysqli_stmt_bind_param($stmt, 'ssssisi', $startDate, $endDate, $contactNumber, $extras, $roomID, $fetchedRoomname, $customerID);

                    if (mysqli_stmt_execute($stmt)) {
                        echo "<h2 style='color: white;'>New booking added successfully.</h2>";
                    } else {
                        echo "<h2>Error adding booking.</h2>";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "<h2>Selected room does not have enough beds for the requested dates.</h2>";
                }
            }
        } else {
            echo "<h2>Error fetching room information.</h2>";
        }

        mysqli_close($db_connection);
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>
<!DOCTYPE HTML>
<html>

<head>
<title>Make a booking</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original_template/style/style.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" type="text/css" href="original_template/style/style.css" title="style" />
    <link rel="stylesheet" type="text/css" href="original_template/style/daterangepicker.css" title="style" />
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
            <h1>Make a booking</h1>
  <?php
    // Display the user's name if logged in and not an admin
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && $_SESSION['role'] !== 'admin') {
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        echo "<h2 style='color: white;'>Booking for $firstname $lastname</h2>";
    } elseif ($_SESSION['role'] === 'admin') {
        // If an admin is logged in, show the customer selection dropdown
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
            exit;
        }

        // Fetch the list of customers who don't have a booking
        $customer_query = "SELECT customerID, firstname, lastname FROM customer WHERE customerID NOT IN (SELECT DISTINCT customerID FROM booking)";
        $customer_result = mysqli_query($db_connection, $customer_query);

        if (!$customer_result) {
            echo "Error: Unable to fetch customers. " . mysqli_error($db_connection);
            exit;
        }

        echo "<form method='POST' action='make_booking.php'>";
        echo "<label for='customerID'>Select a Customer: </label>";
        echo "<select id='customerID' name='customerID' required>";
        echo "<option value=''>Select a Customer</option>";
        
        while ($row = mysqli_fetch_assoc($customer_result)) {
            echo "<option value='" . $row['customerID'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
        }
        
        echo "</select><br><br>";
        echo "</form>";

        mysqli_close($db_connection);
    }
    ?>

    <form method="POST" action="make_booking.php">

        <!--update the room dropdown based on availability -->
        <label for="roomID" >Select a Room:</label>
        <select id="roomID" name="roomID" required>
            <option value="">Select a Room</option>
        </select><br><br>   
        <label for="startDate" >Start Date:</label>
        <input type="text" id="startDate" name="startDate" required><br><br>
        <label for="endDate" >End Date:</label>
        <input type="text" id="endDate" name="endDate" required><br><br>

        <!--hide these extra fields until the search availability button is clicked -->
        <div id="additionalFields" style="display: none;">
            <label for="contactNumber" >Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" required>
            <span class="error">
            <?php
                if (isset($errors) && in_array("Contact Number must contain only numbers.", $errors)) {
                    echo "<h2>Contact Number must contain only numbers.</h2>";
                }
            ?>
            </span>
            <br><br>
            <label for="extras" >Extras:</label>
            <input type="text" id="extras" name="extras"><br><br>
            <input type="submit" value="Make booking"><br><br>
        </div>

        <button type="button" id="searchAvailability">Search Availability</button>

        <!--hide the table until the search availability button is clicked -->
        <div id="availableRoomsContainer" style="display: none;">
            <h2>Available Rooms</h2>
            <table id="availableRooms" border="1">
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Room Type</th>
                        <th>Beds</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows for available rooms will be added here -->
                </tbody>
            </table>
        </div>
    </form>

    <script>

        // Date range picker
        $(function() {
            $('input[name="startDate"], input[name="endDate"]').daterangepicker({
                autoUpdateInput: false,
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: moment().startOf('day')
            }, function(start, end, label) {
                $('#startDate').val(start.format('YYYY-MM-DD'));
                $('#endDate').val(end.format('YYYY-MM-DD'));
            });

            $('#startDate').val(moment().format('YYYY-MM-DD'));
            $('#endDate').val(moment().format('YYYY-MM-DD'));
        });

        // Check room availability
        $(document).ready(function() {
            $("#searchAvailability").click(function() {
                var startDate = $("#startDate").val();
                var endDate = $("#endDate").val();

                $.ajax({
                    type: "GET",
                    url: "roomsearch.php",
                    data: { startDate: startDate, endDate: endDate },
                    dataType: "json",
                    success: function(data) {
                        var roomDropdown = $("#roomID");
                        roomDropdown.empty(); 
                        roomDropdown.append('<option value="">Select a Room</option>');
                        var table = $("#availableRooms tbody");
                        table.empty();

                        if (data.length > 0) {
                            $.each(data, function(index, room) {
                                roomDropdown.append('<option value="' + room.roomID + '">' + room.roomname + '</option>');
                                var newRow = $("<tr></tr>");
                                newRow.append($("<td></td>").text(room.roomname));
                                newRow.append($("<td></td>").text(room.roomtype));
                                newRow.append($("<td></td>").text(room.beds));
                                table.append(newRow);
                            });
                        } else {
                            roomDropdown.append('<option value="">No rooms available</option>');
                            table.append('<tr><td colspan="3">No rooms available</td></tr>');
                        }

                        $("#availableRoomsContainer").css("display", "block");
                        $("#additionalFields").css("display", "block");
                    },
                    error: function() {
                        alert("An error occurred while fetching room availability.");
                    }
                });
            });
        });
    </script>
            </div>
        </div>
        <div id="footer">
           MotuekaBNB &copy;  2023
              
        </div>
    </div>
</body>

</html>
