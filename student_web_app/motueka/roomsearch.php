<?php
include "config.php"; 
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();

// get the start and end date from the AJAX request
$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];

// check for available rooms
$query = "SELECT roomID, roomname, roomtype, beds FROM room WHERE roomID NOT IN (
    SELECT roomID FROM booking
    WHERE startDate <= '$endDate' AND endDate >= '$startDate'
) AND beds >= DATEDIFF('$endDate', '$startDate') + 1";

$result = mysqli_query($db_connection, $query);
$rows = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

//
echo json_encode($rows);

mysqli_free_result($result);
mysqli_close($db_connection);
?>
