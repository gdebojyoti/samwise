var API = {
    // base: "http://localhost/samwise/app/api",
    base: "http://api.bhasic.org/",
    version: "v1/"
}

function register() {
    var name = document.getElementsByName('name')[0].value;
	var college = document.getElementsByName('college')[0].value;
    var email = document.getElementsByName('email')[0].value;
    var password = document.getElementsByName('password')[0].value;
	var confirm_password = document.getElementsByName('confirm_password')[0].value;	
    var phone = document.getElementsByName('phone')[0].value;
    var street_address = document.getElementsByName('street_address')[0].value;
    var city = document.getElementsByName('city')[0].value;
    var state = document.getElementsByName('state')[0].value;
    var pin = document.getElementsByName('pin')[0].value;
    var country = document.getElementsByName('country')[0].value;

    if (!name || !college || !email || !phone) {
        fireToast("error", "Please Fill the mandatory fields");
        return false;
    }

    if(!validateEmail(email)) {
        fireToast("error", "Invalid email entered");
        return false;
    }

    $.ajax({
        method: "POST",
        url: API.base + API.version + "students/register",
        data: {
            name: name,
			college: college,
            email: email,
            password: password,
			confirm_password:confirm_password,
            phone: phone,
            street_address: street_address,
            city: city,
            state: state,
            pin: pin,
            country: country,
            institute_id: 1,
        }
    }) .success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts == 0) {
                fireToast("success", "You have successfully registered with us!");
            }
            else if (data.sts == 1) {
                if (data.msg == "email already exists") fireToast("error", "Email already exists");
                else if (data.msg == "invalid email") fireToast("error", "Invalid email entered");
                else if (data.msg == "invalid phone number") fireToast("error", "Invalid phone number entered");
                else if (data.msg == "password mismatch") fireToast("error", "Passwords do not match");
                else fireToast("error", "Unknown error occurred");
            }
        }
    });
}


function collegeRegistration() {
    
	var college = collegeDetails.college.value;
	var email= collegeDetails.email.value;
    var phone = document.getElementsByName('phone')[0].value;
    var street_address = document.getElementsByName('street_address')[0].value;
    var city = document.getElementsByName('city')[0].value;
    var state = document.getElementsByName('state')[0].value;
    var pin = document.getElementsByName('pin')[0].value;
    var country = document.getElementsByName('country')[0].value;

    if (college == "" || email == "") {
        fireToast("error", "Please Fill the mandatory fields");
        return false;
    }

    if(!validateEmail(email)) {
        fireToast("error", "Invalid email entered");
        return false;
    }
	
    $.ajax({
        method: "POST",
        url: API.base + API.version + "institutes/register",
        data: {
            name: college,
            email: email,         
            phone: phone,
            street_address: street_address,
            city: city,
            state: state,
            pin: pin,
			type:2,
            country: country,
            institute_id: 1,
        }
    }) .success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts == 0) {
                fireToast("success", "You have successfully registered with us!");
            }
            else if (data.sts == 1) {
                if (data.msg == "email already exists") fireToast("error", "Email already exists");
                else if (data.msg == "invalid email") fireToast("error", "Invalid email entered");
                else if (data.msg == "invalid phone number") fireToast("error", "Invalid phone number entered");                
                else fireToast("error", "Unknown error occurred");
            }
        }
    });
}




function registerNow() {
    var name = document.getElementsByName('userIDname')[0].value;
    var email = document.getElementsByName('emailId')[0].value;
    var password = document.getElementsByName('pass')[0].value;
    var phone = document.getElementsByName('phoneNumber')[0].value;
    var street_address = document.getElementsByName('s_address')[0].value;
    var city = document.getElementsByName('city_name')[0].value;
    var state = document.getElementsByName('state_name')[0].value;
    var pin = document.getElementsByName('pin_code')[0].value;
    var country = document.getElementsByName('country')[0].value;

    if (!name || !email || !city || !phone) {
        fireToast("error", "All fields are required");
        return false;
    }

    if(!validateEmail(email)) {
        fireToast("error", "Invalid email entered");
        return false;
    }

    $.ajax({
        method: "POST",
        url: API.base + API.version + "students/registernow",
        data: {
            name: userIDname,
            email: emailId,
            password: pass,
            confirm_password: pass,
            phone: phoneNumber,
            street_address: s_address,
            city: city_name,
            state: state_name,
            pin: pin_code,
            country: country,
            institute_id: 1,
        }
    }) .success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts == 0) {
                fireToast("success", "You have successfully registered with us!");
            }
            else if (data.sts == 1) {
                if (data.msg == "email already exists") fireToast("error", "Email already exists");
                else if (data.msg == "invalid email") fireToast("error", "Invalid email entered");
                else if (data.msg == "invalid phone number") fireToast("error", "Invalid phone number entered");
                else if (data.msg == "password mismatch") fireToast("error", "Passwords do not match");
                else fireToast("error", "Unknown error occurred");
            }
        }
    });
}


// Create a toast notification
function fireToast(category, message) {
    alert(category + ": " + message);
}

// Check email ID to see if it is a valid one
var validateEmail = function(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
