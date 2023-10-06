<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php"; 
include "cleaninput.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit;
}

// Initialize variables
$bookingID = null;
$existingReview = null;
$customerName = null; // Variable to store the customer's name

// Check if the bookingID parameter is set in the URL
if (isset($_GET['bookingID']) && is_numeric($_GET['bookingID'])) {
    $bookingID = $_GET['bookingID'];

    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit;
    }

    // Check if the customer has already submitted a review for this booking
    $reviewQuery = "SELECT reviews FROM booking WHERE bookingID = ?";
    $stmt = mysqli_prepare($db_connection, $reviewQuery);
    mysqli_stmt_bind_param($stmt, 'i', $bookingID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $existingReview);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Fetch the customer's name based on the booking ID
    $customerNameQuery = "SELECT customer.firstName, customer.lastName FROM booking
                          INNER JOIN customer ON booking.customerID = customer.customerID
                          WHERE booking.bookingID = ?";
    $stmt = mysqli_prepare($db_connection, $customerNameQuery);
    mysqli_stmt_bind_param($stmt, 'i', $bookingID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $customerFirstName, $customerLastName);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Combine the first name and last name to get the full customer name
    $customerName = $customerFirstName . ' ' . $customerLastName;

    mysqli_close($db_connection);
} else {
    echo "<h2>Invalid booking ID.</h2>";
    exit;
}

// Check if the form has been submitted for making/editing a review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newReview = cleanInput($_POST['review']);

    // Check if the review is empty (customer wants to delete the review)
    if (empty($newReview)) {
        // Remove the review from the database
        $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        
        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
            exit;
        }

        $deleteReviewQuery = "UPDATE booking SET reviews = NULL WHERE bookingID = ?";
        $stmt = mysqli_prepare($db_connection, $deleteReviewQuery);
        mysqli_stmt_bind_param($stmt, 'i', $bookingID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_close($db_connection);

        // Redirect to booking list or wherever appropriate
        header("Location: booking_list.php");
        exit;
    }

    // Save the new review to the database
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit;
    }

    $updateReviewQuery = "UPDATE booking SET reviews = ? WHERE bookingID = ?";
    $stmt = mysqli_prepare($db_connection, $updateReviewQuery);
    mysqli_stmt_bind_param($stmt, 'si', $newReview, $bookingID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_close($db_connection);
    
    // Redirect to booking list or wherever appropriate
    header("Location: booking_list.php");
    exit;
}
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Manage reviews</title>
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
            <h1>Edit / Add Room Review</h1>
    <h2>Review for: <?php echo $customerName; ?></h2>

    <!-- Review Form -->
    <form method="POST" action="reviews.php?bookingID=<?php echo $bookingID; ?>">
        <label for="review" style="color:white;">Review:</label><br>
        <textarea id="review" name="review" rows="4" cols="50" placeholder="<?php echo empty($existingReview) ? 'Write your review here' : htmlspecialchars($existingReview); ?>"></textarea><br>
        <input type="submit" value="Update">
    </form>
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
