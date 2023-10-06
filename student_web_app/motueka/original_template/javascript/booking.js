// validation
function validation() {
    // Check if input data is found
    let startdate = document.getElementById("startdate").value;
    let enddate = document.getElementById("enddate").value;
    let contactnumber = document.getElementById("contactnumber").value;
    let extras = document.getElementById("extras").value;

    if (contactnumber === '') {
        alert("Please enter a contact number!");
        return false;
    } else if (startdate === '') {
        alert("Please enter a start date!");
        return false;
    } else if (enddate === '') {
        alert("Please enter an end date!");
        return false;
    } else {
        let submitform = 'Start date: ' + startdate + '\n' + 'End date: ' + enddate + '\n' + 'Contact number: ' + contactnumber + '\n' + 'Extras: ' + extras;
        alert("Booking is made.\nPlease double-check to see if your submission is correct.\n"+submitform)
        return true;
    }
}

