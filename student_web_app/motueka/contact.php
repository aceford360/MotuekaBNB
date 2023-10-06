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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li class="selected"><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div id="site_content">
            <div id="content">
                <!-- insert the page content here -->
                <h1>Contact Us</h1>
                <form action="#" method="post">
                    <div class="form_settings">
                        <p><span>Name</span><input class="contact" type="text" name="your_name" value="" /></p>
                        <p><span>Email Address</span><input class="contact" type="text" name="your_email" value="" /></p>
                        <p><span>Message</span><textarea class="contact textarea" rows="8" cols="50" name="your_enquiry"></textarea></p>
                        <p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="contact_submitted" value="submit" /></p>
                    </div>
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
