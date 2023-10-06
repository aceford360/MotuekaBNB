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
                <h1>Motueka Bed N' Breakfast</h1>

    <?php
    session_start(); 

    //check if the user is logged in and if their first name and last name are set in the session
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        echo "<h2>Welcome, $firstname $lastname!</h2>";
    
        // Check the user's role to determine which links to display
        if ($_SESSION['role'] === 'admin') {
            //admin links
            echo "<ul>";
            echo "<li><a href='registercustomer.php'>Register customer</a></li>";
            echo "<li><a href='make_booking.php'>Make a booking</a></li>";
            echo "<li><a href='booking_list.php'>Bookings listing</a></li>";
            echo "<li><a href='listcustomers.php'>Customer listing</a></li>";
            echo "<li><a href='addroom.php'>Add room</a></li>";
            echo "<li><a href='listrooms.php'>Rooms listing</a></li>";
            echo "<li><a href='logout.php'>Logout</a></li>";
            echo "</ul>";
        } else {
            //customer
            echo "<ul>";
            echo "<li><a href='make_booking.php'>Make a booking</a></li>";
            echo "<li><a href='booking_list.php'>Bookings listing</a></li>";
            echo "<li><a href='logout.php'>Logout</a></li>";
            echo "</ul>";
        }
    } else {
        //if not logged in, show login and register links
        echo "<h1>Privacy Statement</h1>";
        echo "<p>We collect personal information from you, including information about your:</p>";
        echo "<ul>";
        echo "<li>Name</li>";
        echo "<li>Contact information</li>";
        echo "</ul>";
        echo "<p>We collect your personal information in order to:</p>";
        echo "<ul>";
        echo "<li>Start the booking process.</li>";
        echo "</ul>";
        echo "<p>You have the right to ask for a copy of any personal information we hold about you, and to ask
        for it to be corrected if you think it is wrong. If you’d like to ask for a copy of your
        information, or to have it corrected, please contact us at motuekabnbSupport@gmail.com, or
        1234567890, or 21 East Rd, Haumoana,Motueka,.</p>";
    }
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
