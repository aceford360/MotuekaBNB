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
include "cleaninput.php";

$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);


//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "<h2>Error: Unable to connect to MySQL. " . mysqli_connect_error() . "</h2>";
    exit; //stop processing the page further
}

//retrieve the customerid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Customer ID</h2>"; //simple error feedback
        exit;
    } 
}

//the data was sent using a form, therefore, we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     
    $error = 0; //clear our error flag
    $msg = 'Error: ';  
    //customerID (sent via a form, it is a string not a number so we try a type conversion!)    
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Customer ID '; //append error message
       $id = 0;  
    }        
    
    //save the customer data if the error flag is still clear and customer id is > 0
    if ($error == 0 and $id > 0) {
        $query = "DELETE FROM customer WHERE customerID=?";
        $stmt = mysqli_prepare($db_connection, $query); //prepare the query
        mysqli_stmt_bind_param($stmt, 'i', $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Customer details deleted.</h2>";     
        
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      

}

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM customer WHERE customerid='.$id;
$result = mysqli_query($db_connection,$query);
$rowcount = mysqli_num_rows($result); 
?>
<?php

//makes sure we have the customer
if ($rowcount > 0) {  
   echo "<fieldset><legend>Customer detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>First name:</dt><dd>".$row['firstname']."</dd>".PHP_EOL;
   echo "<dt>Last name:</dt><dd>".$row['lastname']."</dd>".PHP_EOL;
   echo "<dt>Email:</dt><dd>".$row['email']."</dd>".PHP_EOL;
   echo "</dl></fieldset>".PHP_EOL;
   ?><form method="POST" action="deletecustomer.php">
     <h2>Are you sure you want to delete this customer?</h2>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <input type="submit" name="submit" value="Delete">
     <a href="listcustomers.php">[Cancel]</a>
     </form>
<?php    
} else echo "<h2>No Customer found, possibly deleted!</h2>"; //suitable feedback

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
