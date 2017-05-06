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


function fetchCollegeList(elemId) {

    $.ajax({
        method: "GET",
        url: API.base + API.version + "institutes/search"

    }).success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts === 0) {
                console.log(data.data);
                for(var i = 0, elm; i < data.data.length; i++) {
                    elm = "<option value=" + data.data[i].id + ">" + data.data[i].name + "</option>";
                    $("#" + elemId).append(elm);
                }
            }
            else if (data.sts == 1) {
               fireToast("error", "Email already exists");
            }
        }
    });
}


function fetchProjectList(elemId) {

    $.ajax({
        method: "GET",
        url: API.base + API.version + "projects/search"

    }).success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts === 0) {
                console.log(data.data);
                for(var i = 0, elm; i < data.data.length; i++) {					
				  var elm = "<tr id ='projectitem" + data.data[i].id + "'>";
			      elm += '<td>' + data.data[i].name + '</td>';								  
				  elm += '<td>' + data.data[i].name + '</td>';
			      elm += '<td>' + data.data[i].name + '</td>';
				  elm += '<td><span class="label label-info label-mini">Pending</span></td>';
				  elm += '<td>';
				  elm += '<button class="btn btn-success btn-xs" onclick="approve('+ data.data[i].id +')"><i class="fa fa-check tooltips" data-placement="right" data-original-title="Approve"></i></button>';
			      elm += '<button class="btn btn-primary btn-xs" onclick="reject()"><i class="fa fa-pencil tooltips" data-placement="right" data-original-title="Edit"></i></button>';
			      elm += '<button class="btn btn-danger btn-xs" onclick="delete()"><i class="fa fa-trash-o tooltips" data-placement="right" data-original-title="Delete"></i></button>';
				  elm += '</td>';
				  elm += '</tr>';
                  //elm = "<tbody>" + data.data[i].name + "</tbody>";
                  $("#" + elemId).append(elm);
                }
            }
            else if (data.sts == 1) {
               fireToast("error", "Project List Not Found");
            }
        }
    });
}


function submitRegistration() {
	
	var emailId = document.getElementsByName('email')[0].value;
	var passwd = document.getElementsByName('pass')[0].value;
	var confmpasswd = document.getElementsByName('confmPass')[0].value;
	var instId = document.getElementsByName('InstID')[0].value;
    
  
	 $.ajax({
        method: "POST",
        url: API.base + API.version + "professors/register",
        data: {
			email: emailId,
            password:passwd,
			confirm_password:confmpasswd,
            institute_id:instId,
        }
    }) .success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts == 0) {
				console.log(data);
                fireToast("success", "Registration Successful!");
            }
            else if (data.sts == 1) {
                fireToast("error", data.msg || "Form Error");
				
            }
        }
    });
}



function submit_project() {
	
	var projectname = document.getElementsByName('projectname')[0].value;
	var projectaddress = document.getElementsByName('projectaddress')[0].value;
	var aidtype = document.getElementsByName('formaidtype')[0].value;
    var othercategory = document.getElementsByName('othername')[0].value;
    var duration = document.getElementsByName('duration')[0].value;
    var projectamount = document.getElementsByName('amount')[0].value;
	var fundingstatus = document.getElementsByName('formfunding')[0].value;
    var contribution = document.getElementsByName('contributing')[0].value;
	var askingamount = document.getElementsByName('asking')[0].value;
    var professorID = document.getElementsByName('professorID')[0].value;
    var commentsection = document.getElementsByName('comment')[0].value;
	var name = document.getElementsByName('name')[0].value;
	var sex = document.getElementsByName('sex')[0].value;
	var age = document.getElementsByName('age')[0].value;
	var occupation = document.getElementsByName('Occupation')[0].value;
	var address = document.getElementsByName('Address')[0].value;
	var gpscor = document.getElementsByName('ordinates')[0].value;
	var phone = document.getElementsByName('PhoneNumber')[0].value;
	var mail = document.getElementsByName('EmailID')[0].value;
  
	 $.ajax({
        method: "POST",
        url: API.base + API.version + "projects/create",
        data: {
			name:projectname,
            address: projectaddress,
			category:aidtype,
            category_other: othercategory,
            weeks: duration,
            amount: projectamount,
			funding_status:fundingstatus,
            contributing: contribution,
            asking: askingamount,
            professor_id: professorID,
            details: commentsection,
			contact_name: name,
			sex: sex,
			age: age,
			occupation: occupation,
			contact_address: address,
			gps: gpscor,
			phone: phone,
			email: mail,
        }
    }) .success(function( data ) {
        if(typeof data.sts !== 'undefined') {
            if (data.sts == 0) {
				console.log(data);
                fireToast("success", "You have successfully submitted project details!");
            }
            else if (data.sts == 1) {
                fireToast("error", "Unknown error occurred");
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
