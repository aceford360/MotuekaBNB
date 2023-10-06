<?php
//start the session
session_start();

//check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    //unset all of the session variables
    $_SESSION = array();

    //destroy the session
    session_destroy();
    
    //redirect to the login page or any other page as needed
    header("Location: login.php");
    exit;
} else {
    //if the user is not logged in, you can redirect them to the login page
    header("Location: login.php");
    exit;
}
?>
