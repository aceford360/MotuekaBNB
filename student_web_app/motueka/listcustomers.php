<!DOCTYPE HTML>
<html>

<head>
    <title>Browse for customers</title>
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
            <script>
        function searchResult(searchstr) {
            if (searchstr.length == 0) {
                return;
            }
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    //take JSON text from the server and convert it to JavaScript objects
                    //mbrs will become a two-dimensional array of our customers much like 
                    //a PHP associative array
                    var mbrs = JSON.parse(this.responseText);
                    var tbl = document.getElementById("tblcustomers"); //find the table in the HTML

                    //clear any existing rows from any previous searches
                    //if this is not cleared, rows will just keep being added
                    var rowCount = tbl.rows.length;
                    for (var i = 1; i < rowCount; i++) {
                        //delete from the top - row 0 is the table header we keep
                        tbl.deleteRow(1);
                    }

                    //populate the table
                    //mbrs.length is the size of our array
                    for (var i = 0; i < mbrs.length; i++) {
                        var mbrid = mbrs[i]['customerID'];
                        var fn = mbrs[i]['firstname'];
                        var ln = mbrs[i]['lastname'];

                        //concatenate our action URLs into a single string
                        var urls = '<a href="viewcustomer.php?id=' + mbrid + '">[view]</a>';
                        urls += '<a href="editcustomer.php?id=' + mbrid + '">[edit]</a>';
                        urls += '<a href="deletecustomer.php?id=' + mbrid + '">[delete]</a>';

                        //create a table row with three cells  
                        tr = tbl.insertRow(-1);
                        var tabCell = tr.insertCell(-1);
                        tabCell.innerHTML = ln; //lastname
                        var tabCell = tr.insertCell(-1);
                        tabCell.innerHTML = fn; //firstname      
                        var tabCell = tr.insertCell(-1);
                        tabCell.innerHTML = urls; //action URLs            
                    }
                }
            }
            //call our PHP file that will look for a customer or customers matching the search string
            xmlhttp.open("GET", "customersearch.php?sq=" + searchstr, true);
            xmlhttp.send();
        }
    </script>
</head>
<body >

<h1 >Customer List Search by Lastname</h1>
</h2>
<form>
    <label for="lastname" >Lastname: </label>
    <input id="lastname" type="text" size="30" onkeyup="searchResult(this.value)" onclick="javascript: this.value = ''"
           placeholder="Start typing a last name">
</form>
<table id="tblcustomers" border="1">
    <thead>
    <tr>
        <th>Last name</th>
        <th>First name</th>
        <th>Actions</th>
    </tr>
    </thead>
</table>
            </div>
        </div>
        <div id="footer">
            MotuekaBNB &copy; 2023</a>
        </div>
    </div>
</body>

</html>
