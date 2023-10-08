<!DOCTYPE HTML>
<html>

<head>
    <title>Browse rooms</title>
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
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT roomID,roomname,roomtype FROM room ORDER BY roomtype';
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
<h1 >Room list</h1>
<table border="1">
<thead><tr><th>Room Name</th><th>Type</th><th>Action</th></tr></thead>
<?php

//makes sure we have rooms
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['roomID'];	
	  echo '<tr><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td>';
	  echo '<td><a href="viewroom.php?id='.$id.'">[view]</a>';
	  echo '<a href="editroom.php?id='.$id.'">[edit]</a>';
	  echo '<a href="deleteroom.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>';
   }
} else echo "<h2>No rooms found!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done
?>
</table>
            </div>
        </div>
        <div id="footer">
           MotuekaBNB &copy;  2023
              
        </div>
    </div>
</body>

</html>
