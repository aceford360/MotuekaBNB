<!DOCTYPE HTML>
<html>

<head>
    <title>Edit a customer</title>
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
$error=0;
if (mysqli_connect_errno()) {
  echo "<h2>Error: Unable to connect to MySQL. " . mysqli_connect_error() . "</h2>";
  exit; //stop processing the page further
};

//retrieve the customerid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid Customer ID</h2>"; //simple error feedback
        exit;
    } 
}
//the data was sent using a form, therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
//validate incoming data - only the first field is done for you in this example - rest is up to you do
    $error = 0; //clear our error flag
    $msg = 'Error: ';  
     
//customerID (sent via a form it is a string not a number so we try a type conversion!)    
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Customer ID '; //append error message
       $id = 0;  
    }   
//firstname
       $firstname = cleanInput($_POST['firstname']); 
//lastname
       $lastname = cleanInput($_POST['lastname']);        
//email
       $email = cleanInput($_POST['email']);         
    
//save the customer data if the error flag is still clear and customer id is > 0
    if ($error == 0 and $id > 0) {
        $query = "UPDATE customer SET firstname=?,lastname=?,email=? WHERE customerID=?";
        $stmt = mysqli_prepare($db_connection,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssi', $firstname, $lastname, $email, $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>customer details updated.</h2>";     
//        header('Location: http://localhost/bit608/listcustomers.php', true, 303);      
    } else { 
      echo "<h2>$msg</h2>";
    }      
}
//locate the customer to edit by using the customerID
//we also include the customer ID in our form for sending it back for saving the data
$query = 'SELECT customerID,firstname,lastname,email FROM customer WHERE customerid='.$id;
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
?>
<h1  >Customer Details Update</h1>

<form method="POST" action="editcustomer.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
  <p>
    <label for="firstname" >First name: </label>
    <input type="text" id="firstname" name="firstname" minlength="1" 
           maxlength="50" required value="<?php echo $row['firstname']; ?>"> 
  </p> 
  <p>
    <label for="lastname" >Last name: </label>
    <input type="text" id="lastname" name="lastname" minlength="1" 
           maxlength="50" required value="<?php echo $row['lastname']; ?>">  
  </p>  
  <p>  
    <label for="email" >Email: </label>
    <input type="email" id="email" name="email" maxlength="100" 
           size="50" required value="<?php echo $row['email']; ?>"> 
   </p>

   <input type="submit" name="submit" value="Update">
 </form>
<?php 
} else { 
  echo "<h2>Customer not found with that ID</h2>"; //simple error feedback
}
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
