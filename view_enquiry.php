<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Retrieve Enquiries</title>
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-database.js"></script>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
  }

  .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }

  .section {
    margin-bottom: 20px;
  }

  .section h3 {
    margin-bottom: 10px;
    font-size: 1.2em;
  }

  .enquiry {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    position: relative;
  }

  .enquiry-buttons {
    position: absolute;
    top: 10px;
    right: 10px;
  }

  .enquiry-buttons button {
    margin-left: 5px;
  }

  .enquiry-buttons button.enquired {
    background-color: green;
    color: white;
  }
</style>
</head>
<body>

<div class="container">

  <div class="section">
    <h3>Email Enquiries</h3>
    <div id="emailEnquiries" class="enquiriesResult"></div>
  </div>

  <div class="section">
    <h3>Other Enquiries</h3>
    <div id="otherEnquiries" class="enquiriesResult"></div>
  </div>

</div>

<script>
  // Firebase configuration
  var firebaseConfig = {
  apiKey: "AIzaSyCRFnHx8rTuTLcroDWvLiFyph8NfcwIGCE",
  authDomain: "carapp-b9bc9.firebaseapp.com",
  databaseURL: "https://carapp-b9bc9-default-rtdb.firebaseio.com",
  projectId: "carapp-b9bc9",
  storageBucket: "carapp-b9bc9.appspot.com",
  messagingSenderId: "515913279706",
  appId: "1:515913279706:web:2702c8f82a5e0c8dbb1de3"
};

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

  // Reference to the database
  var database = firebase.database();

  // Function to retrieve enquiries by username
  <?php
    session_start();
    if (isset($_SESSION['username'])) {
        // Change the username to "Nishad" for testing
        $username = $_SESSION['username'];
        echo "getEnquiriesByUsername('$username');";
    }
  ?>

  // Function to retrieve enquiries by username
  function getEnquiriesByUsername(username) {
    database.ref(username + '/Enquiries').once('value', function(snapshot) {
      var emailEnquiries = document.getElementById('emailEnquiries');
      var otherEnquiries = document.getElementById('otherEnquiries');
      emailEnquiries.innerHTML = ""; // Clear previous results
      otherEnquiries.innerHTML = ""; // Clear previous results
      if (snapshot.exists()) {
        snapshot.forEach(function(childSnapshot) {
          var enquiry = childSnapshot.val();
          var enquiryHTML = "<div class='enquiry'>";
          enquiryHTML += "<p><strong>Email:</strong> " + enquiry.email + "</p>";
          enquiryHTML += "<p><strong>Enquiry:</strong> " + enquiry.enquiry + "</p>";
          enquiryHTML += "<div class='enquiry-buttons'>";
          // Respond button
          enquiryHTML += "<button class='respond-button' onclick='respondToEnquiry(\"" + enquiry.email + "\")'>Respond</button>";
          // Mark as Enquired button
          var enquiredClass = enquiry.enquired ? 'enquired' : '';
          enquiryHTML += "<button class='enquire-button " + enquiredClass + "' onclick='markAsEnquired(this, \"" + childSnapshot.key + "\")'>Mark as Enquired</button>";
          enquiryHTML += "</div>";
          enquiryHTML += "</div>";
          if (enquiry.email.endsWith('@example.com')) {
            emailEnquiries.innerHTML += enquiryHTML;
          } else {
            otherEnquiries.innerHTML += enquiryHTML;
          }
        });
      } else {
        emailEnquiries.innerHTML = "<p>No email enquiries found for " + username + ".</p>";
        otherEnquiries.innerHTML = "<p>No other enquiries found for " + username + ".</p>";
      }
    });
  }

  // Function to mark enquiry as enquired
  function markAsEnquired(button, enquiryKey) {
    // Update the status of the enquiry in the database
    var username = "<?php echo $username; ?>";
    database.ref(username + '/Enquiries/' + enquiryKey).update({
      enquired: true
    }).then(function() {
      // Change button style to green
      button.classList.add('enquired');
      button.disabled = true; // Disable the button after marking as enquired
    }).catch(function(error) {
      console.error("Error marking enquiry as enquired: ", error);
    });
  }

  // Function to respond to an enquiry via Gmail
  function respondToEnquiry(email) {
    var subject = encodeURIComponent("Response to Your Enquiry");
    var body = encodeURIComponent("Thank you for showing interest with us.");
    var gmailLink = "https://mail.google.com/mail/?view=cm&fs=1&to=" + email + "&su=" + subject + "&body=" + body;
    window.open(gmailLink);
  }

</script>



</body>
</html>
