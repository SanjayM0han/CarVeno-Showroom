<!DOCTYPE html>
<html lang="en">
<head>
<?php
        session_start();
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];}
    ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Details</title>
  <!-- External CSS -->
  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href='https://fonts.googleapis.com/css?family=Anton' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
  <!-- External CSS -->
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Inline CSS */
    /* Global styles */
    body {
      font-family: 'Anton', sans-serif;
      background-color: #ecf0f1;
      color: #333;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      margin-top: 20px;
    }

    .container {
      margin: 20px auto;
      max-width: 800px;
      padding: 0 20px;
      box-sizing: border-box;
    }

    /* Car details styles */
    .car-details {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .car-details p {
      margin-bottom: 10px;
    }

    .car-details strong {
      color: #3498db;
    }

    /* Images container styles */
    .images {
      width: calc(100% - 40px); /* Adjust for padding */
      overflow-x: auto; /* Horizontal scrolling */
      white-space: nowrap; /* Prevents images from wrapping to the next line */
      padding: 20px 0; /* Add padding for better spacing */
      margin: 0 auto;
    }

    .images img {
      width: 200px; /* Adjust image width as needed */
      height: auto; /* Maintain aspect ratio */
      margin-right: 10px; /* Space between images */
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .edit-button,
    .delete-button {
      cursor: pointer;
      display: inline-block;
      margin-left: 10px;
      padding: 5px 10px;
      border-radius: 5px;
      background-color: #3498db;
      color: #fff;
    }

    .edit-button:hover,
    .delete-button:hover {
      background-color: #2980b9;
    }

    .show-variants-button {
      cursor: pointer;
      display: inline-block;
      margin-left: 10px;
      padding: 5px 10px;
      border-radius: 5px;
      background-color: #2ecc71;
      color: #fff;
    }

    .show-variants-button:hover {
      background-color: #27ae60;
    }
  </style>
</head>
<body>
  <h1>Car Details</h1>
  <div id="carList"></div>

  <!-- Firebase JavaScript -->
  <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-database.js"></script>
  <script>
    // Firebase configuration
    var firebaseConfig = {
  apiKey: "AIzaSyB3pZkTYmaStorSVXvz8S-UNa9iSiHGzh8",
  authDomain: "major-fdf32.firebaseapp.com",
  databaseURL: "https://carapp-b9bc9-default-rtdb.firebaseio.com",
  projectId: "major-fdf32",
  storageBucket: "major-fdf32.appspot.com",
  messagingSenderId: "215339354943",
  appId: "1:215339354943:web:24c753bcb36e49d2f5e18a",
  measurementId: "G-DS7X824F1W"
};

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    var username = "<?php echo $username; ?>";
    // Reference to the "Emmanuel" node in the database
    var database = firebase.database().ref(username);

    // Function to display car details
    function displayCarDetails(snapshot) {
        var carList = document.getElementById('carList');

        snapshot.forEach(function(childSnapshot) {
            var carKey = childSnapshot.key;
            var carData = childSnapshot.val();

            var carElement = document.createElement('div');
            carElement.className = 'container'; // Add container class
            carElement.innerHTML = `
                <div class="car-details" data-car-key="${carKey}">
                    <p><strong>${carData.car_name}</strong></p>
                    <p><strong>Number of Variants:</strong> ${carData.num_variants}</p>
                    <button class="show-variants-button" onclick="toggleVariants('${carKey}')">Show Variants</button>
                    <div class="variants-container" style="display: none;"></div>
                </div>
            `;
            // Append images
            carElement.innerHTML += `
                <div class="images">
                    <img src="${carData.image_url1}" alt="Car Image">
                    <img src="${carData.image_url2}" alt="Car Image">
                    <img src="${carData.image_url3}" alt="Car Image">
                    <img src="${carData.image_url4}" alt="Car Image">
                    <img src="${carData.image_url5}" alt="Car Image">
                </div>
            `;

            carList.appendChild(carElement);
        });
    }

    // Function to toggle variants visibility
    function toggleVariants(carKey) {
        var variantsContainer = document.querySelector(`[data-car-key="${carKey}"] .variants-container`);
        if (variantsContainer.style.display === 'none') {
            showVariants(carKey);
        } else {
            variantsContainer.style.display = 'none';
        }
    }

    // Function to show variants
    function showVariants(carKey) {
        var variantsContainer = document.querySelector(`[data-car-key="${carKey}"] .variants-container`);

        // Clear existing content
        variantsContainer.innerHTML = '';

        database.child(carKey).child('variants').once('value', function(variantsSnapshot) {
            variantsSnapshot.forEach(function(variantSnapshot) {
                var variantData = variantSnapshot.val();

                var variantElement = document.createElement('div');
                variantElement.className = 'car-details';
                variantElement.innerHTML = `
                    <p><strong>Variant:</strong> ${variantData.model}</p>
                    <p><strong>Condition:</strong> ${variantData.conditions}</p>
                    <p><strong>Engine:</strong> ${variantData.engine}</p>
                    <p><strong>Exterior Color:</strong> ${variantData.exterior_color}</p>
                    <p><strong>Interior Color:</strong> ${variantData.interior_color}</p>
                    <p><strong>Mileage:</strong> ${variantData.mileage}</p>
                    <p><strong>Transmission:</strong> ${variantData.transmission}</p>
                    <p><strong>Year:</strong> ${variantData.year}</p>
                    <button class="edit-button" onclick="editVariant('${carKey}', '${variantSnapshot.key}', ${JSON.stringify(variantData)})">Edit Variant</button>
                    <button class="delete-button" onclick="deleteVariant('${carKey}', '${variantSnapshot.key}')">Delete Variant</button>
                `;

                variantsContainer.appendChild(variantElement);
            });
        });

        variantsContainer.style.display = 'block';
    }

    // Fetch car details when the page loads
    database.once('value', function(snapshot) {
        displayCarDetails(snapshot);
    });

    // Function to delete car
    function deleteCar(carKey) {
        if (confirm("Are you sure you want to delete this car?")) {
            database.child(carKey).remove();
            location.reload(); // Reload the page to reflect changes
        }
    }

    // Function to edit car
    function editCar(carKey, carName) {
        var newCarName = prompt("Enter the new name for the car:", carName);
        if (newCarName !== null) {
            database.child(carKey).child('car_name').set(newCarName);
            location.reload(); // Reload the page to reflect changes
        }
    }

    // Function to edit variant
    function editVariant(carKey, variantId, variantData) {
        var newModel = prompt("Enter the new model for the variant:", variantData.model);
        var newCondition = prompt("Enter the new condition for the variant:", variantData.conditions);
        var newEngine = prompt("Enter the new engine for the variant:", variantData.engine);
        var newExteriorColor = prompt("Enter the new exterior color for the variant:", variantData.exterior_color);
        var newInteriorColor = prompt("Enter the new interior color for the variant:", variantData.interior_color);
        var newMileage = prompt("Enter the new mileage for the variant:", variantData.mileage);
        var newTransmission = prompt("Enter the new transmission for the variant:", variantData.transmission);
        var newYear = prompt("Enter the new year for the variant:", variantData.year);

        if (newModel !== null && newCondition !== null && newEngine !== null && newExteriorColor !== null &&
            newInteriorColor !== null && newMileage !== null && newTransmission !== null && newYear !== null) {
            var updatedVariantData = {
                model: newModel,
                conditions: newCondition,
                engine: newEngine,
                exterior_color: newExteriorColor,
                interior_color: newInteriorColor,
                mileage: newMileage,
                transmission: newTransmission,
                year: newYear
            };

            database.child(carKey).child('variants').child(variantId).set(updatedVariantData);
            location.reload(); // Reload the page to reflect changes
        }
    }

    // Function to delete variant
    function deleteVariant(carKey, variantId) {
        if (confirm("Are you sure you want to delete this variant?")) {
            database.child(carKey).child('variants').child(variantId).remove();
            location.reload(); // Reload the page to reflect changes
        }
    }
  </script>
</body>
</html>
