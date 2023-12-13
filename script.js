         // JavaScript for basic form validation
    document.getElementById("jobApplicationForm").addEventListener("submit", function(event) {
    // Validate the form fields here
    // For simplicity, you can add more sophisticated validation logic

    // Example: Check if the age is a valid number
    var ageInput = document.getElementById("age");
    if (isNaN(ageInput.value)) {
        alert("Please enter a valid age.");
        event.preventDefault();
    }

    // Example: Check if the ID number is 8 numerical values
    var idNumberInput = document.getElementById("idNumber");
    if (!/^\d{8}$/.test(idNumberInput.value)) {
        alert("Please enter a valid 8-digit ID number.");
        event.preventDefault();
    }
});

function submitApplication() {
    // Get the telephone number input value
    var phoneNumber = document.getElementById("telephone").value;
    var ageInput = document.getElementById("age").value;
    // Additional fields to check for emptiness
    var nameInput = document.getElementById("name").value;
    var emailInput = document.getElementById("email").value;
    var cvInput = document.getElementById("cv").value;
    var resumeInput = document.getElementById("resume").value;
    var locationInput = document.getElementById("location").value;
    var kraPinInput = document.getElementById("kraPin").value;
    var idNumberInput = document.getElementById("idNumber").value;
    var positionInput = document.getElementById("position").value;
    // Check if any of the fields (except phone number) are empty
    if (
        nameInput === "" ||
        emailInput === "" ||
        cvInput === "" ||
        resumeInput === "" ||
        locationInput === "" ||
        kraPinInput === "" ||
        idNumberInput === "" ||
        positionInput === ""
    ) {
        alert("Please fill in all the required fields before submitting.");
        return;
    }
    // Validate the ID number
    if (!/^\d{8}$/.test(idNumberInput)) {
        alert("Please enter a valid 8-digit ID number.");
        
        // Highlight the ID number input field for the user to rectify
        document.getElementById("idNumber").style.border = "2px solid red";
        
        return;
    }

    //Validate KRA PIN
           var kraPinPattern = /^a\d{9}[a-z]$/i;
              if (!kraPinPattern.test(kraPinInput)) {
                alert("Please enter a valid KRA Pin (e.g., a123456789z).");
        
               // Highlight the KRA Pin input field for the user to rectify
                  document.getElementById("kraPin").style.border = "2px solid red";
        
                   return;
              }



    // Validate the phone number before proceeding
    if (!/^(?:254|\+254|0)?((?:(?:7(?:(?:[01249][0-9])|(?:5[789])|(?:6[89])))|(?:1(?:[1][0-5])))[0-9]{6})$/.test(phoneNumber)) {
        alert("Please enter a valid Safaricom number.");
        return;
    }
    // Validate the age
    if (isNaN(ageInput) || parseInt(ageInput) < 18) {
        alert("You must be 18 years or older to apply.");
        return;
    }

          // If all validations pass, show a success message
              alert("Form data valid. Enter Mpesa Pin To Proceed!");
        // Send the form data to mpesa_payment.php
        sendDataToMpesa(phoneNumber);

        return true;
    }
      


    function sendDataToMpesa(phoneNumber) {
        // Use AJAX to call the PHP script (mpesa_payment.php)
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "mpesa_payment.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response, e.g., show a success message
                var response = JSON.parse(xhr.responseText);

                if (response.paymentStatus) {
                    // Payment successful, proceed with data submission
                    alert("Mpesa payment successful. Proceeding with data submission.");
                    sendDataToDatabase();
                } else {
                    // Payment failed, show an error message
                    alert("Mpesa payment failed. Please try again.");
                }
            }
        };

        // Send the phone number as POST data
        xhr.send("telephone=" + encodeURIComponent(phoneNumber));
    }  
    function sendDataToDatabase(name, email, cv, resume, age, location, kraPin, idNumber, position, telephone) {

     //Get form data
     var formData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        cv: document.getElementById("cv").value,
        resume: document.getElementById("resume").value,
        age: document.getElementById("age").value,
        location: document.getElementById("location").value,
        kraPin: document.getElementById("kraPin").value,
        idNumber: document.getElementById("idNumber").value,
        position: document.getElementById("position").value,
        telephone: document.getElementById("telephone").value 
     }


        // Use AJAX to call the PHP script (submit.php)
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response, e.g., show a success message
                alert(xhr.responseText);
            }
        };
       // Convert the form data to a URL-encoded string
              var encodedFormData = Object.keys(formData).map(function (key) {
             return encodeURIComponent(key) + "=" + encodeURIComponent(formData[key]);
         }).join("&");
        // Send the form data as POST data
        xhr.send(encodedFormData);
        
    }