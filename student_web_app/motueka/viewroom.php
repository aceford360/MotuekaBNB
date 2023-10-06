<?php
include "checksession.php";
//checkUser();
//loginStatus(); 
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
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit; //stop processing the page further
}

//do some simple validation to check if id exists
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
    echo "<h2>Invalid Room ID</h2>"; //simple error feedback
    exit;
}

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM room WHERE roomid=' . $id;
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
<h1 >Room Details View</h1>
<h2><a href='listrooms.php' >[Return to the Room listing]</a><a href='index.php' >[Return to the main page]</a></h2>

<?php
//makes sure we have the Room
if ($rowcount > 0) {  
   echo "<fieldset><legend>Room detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
   echo "<dt>Description:</dt><dd>".$row['description']."</dd>".PHP_EOL;
   echo "<dt>Room type:</dt><dd>".$row['roomtype']."</dd>".PHP_EOL;
   echo "<dt>Sleeps:</dt><dd>".$row['beds']."</dd>".PHP_EOL; 
   echo '</dl></fieldset>'.PHP_EOL;  
}
else {
    echo "<h2'>No Room found!</h2>"; //suitable feedback
}
mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done
?>
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
