<!DOCTYPE html>
<html>
<head>
    <title>View Offers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .offer {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .offer img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .offer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>View Offers</h2>
    <div id="offersContainer">
        <!-- Offers will be dynamically loaded here -->
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/8.9.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.0/firebase-database.js"></script>
<script>
    // Initialize Firebase
    var firebaseConfig = {
        apiKey: "AIzaSyD_tF8IzqgVFLoqhTGPLrNUGtkcskJ8Zgs",
  authDomain: "carapp-b9bc9.firebaseapp.com",
  databaseURL: "https://carapp-b9bc9-default-rtdb.firebaseio.com",
  projectId: "carapp-b9bc9",
  storageBucket: "carapp-b9bc9.appspot.com",
  messagingSenderId: "515913279706",
  appId: "1:515913279706:web:4b88e1c7f76208c0bb1de3",
  measurementId: "G-CJM8R9NNW3"
    };
    firebase.initializeApp(firebaseConfig);

    // Get a reference to the database service
    var database = firebase.database();
    <?php session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} ?>
    // Reference to the offers node in the Firebase database
    var offersRef = database.ref("<?php echo $_SESSION['username']; ?>/offers");

    // Function to fetch and display offers
    function displayOffers() {
        offersRef.once('value', function(snapshot) {
            snapshot.forEach(function(childSnapshot) {
                var offerData = childSnapshot.val();
                var offerHtml = '<div class="offer">';
                offerHtml += '<img src="' + offerData.image_url + '" alt="Offer Image">';
                offerHtml += '<p><strong>Car Model:</strong> ' + offerData.car_model + '</p>';
                offerHtml += '<p><strong>Car Variant:</strong> ' + offerData.car_variant + '</p>';
                offerHtml += '<p><strong>Discount Price:</strong> ' + offerData.discount_price + '</p>';
                offerHtml += '<p><strong>Offer Description:</strong> ' + offerData.offer_description + '</p>';
                offerHtml += '</div>';
                document.getElementById('offersContainer').innerHTML += offerHtml;
            });
        });
    }

    // Call the function to display offers when the page loads
    displayOffers();
</script>
</body>
</html>
