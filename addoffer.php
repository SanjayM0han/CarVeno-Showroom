<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your Firebase Realtime Database URL
    $databaseURL = "https://carapp-b9bc9-default-rtdb.firebaseio.com/";

    // Prepare data to be sent to Firebase Realtime Database
    $data = array(
        'car_model' => $_POST['car_model'],
        'car_variant' => $_POST['car_variant'],
        'discount_price' => $_POST['discount_price'],
        'offer_description' => $_POST['offer_description'], // Add offer description here
        'image_url' => $_POST['image_url']
    );

    $data1 = array(
       'image_url' => $_POST['image_url'],
       'username' => $username

    );

    //Function to send data to Firebase
    function sendDataToFirebase($path, $data, $databaseURL) {
        $firebaseEndpoint = $databaseURL . $path . '.json';
        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($firebaseEndpoint, false, $context);
        return $result;
    }

    // Find the node for the car model
    $carModel = $_POST['car_model'];
    $databaseRef = $databaseURL . $username . '.json';
    $json = file_get_contents($databaseRef);
    $dataArray = json_decode($json, true);

    $carNodeKey = null;
    foreach ($dataArray as $key => $value) {
        if (isset($value['car_name']) && $value['car_name'] === $carModel) {
            $carNodeKey = $key;
            break;
        }
    }

    if ($carNodeKey !== null) {
        sendDataToFirebase("offers", $data, $databaseURL);
        // Add to the specific car model node
        sendDataToFirebase($username . '/' . $carNodeKey . '/offers', $data, $databaseURL);
        sendDataToFirebase($username . '/' . $carNodeKey . '/variants/' . $_POST['car_variant'] . '/offers', $data, $databaseURL);
        sendDataToFirebase($username . '/offers', $data, $databaseURL);
        sendDataToFirebase('offerbanner', $data1, $databaseURL);

        $statusMsg = "Offer added successfully!";
    } else {
        $statusMsg = "Car model not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Offer</title>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var firebaseConfig = {
                apiKey: "AIzaSyD_tF8IzqgVFLoqhTGPLrNUGtkcskJ8Zgs",
                authDomain: "carapp-b9bc9.firebaseapp.com",
                databaseURL: "https://carapp-b9bc9-default-rtdb.firebaseio.com",
                projectId: "carapp-b9bc9",
                storageBucket: "carapp-b9bc9.appspot.com",
                messagingSenderId: "515913279706",
                appId: "1:515913279706:web:bff09fb4f12513d9bb1de3",
                measurementId: "G-PH78XD02K9"
            };
            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            var carModelSelect = document.getElementById('car_model');
            var carVariantSelect = document.getElementById('car_variant');
            var originalPriceInput = document.getElementById('original_price');

            // Fetch car models from Firebase
            var databaseRef = firebase.database().ref("<?php echo $username; ?>");
            databaseRef.once('value', function(snapshot) {
                snapshot.forEach(function(childSnapshot) {
                    var carName = childSnapshot.val().car_name;
                    if (carName) {
                        var option = document.createElement('option');
                        option.value = carName;
                        option.textContent = carName;
                        carModelSelect.appendChild(option);
                    }
                });
            });

            // Function to fetch and populate car variants
            function fetchCarVariants() {
                var selectedCarModel = carModelSelect.value;
                carVariantSelect.innerHTML = ''; // Clear previous variants

                var variantsRef = databaseRef.orderByChild('car_name').equalTo(selectedCarModel);
                variantsRef.once('value', function(snapshot) {
                    snapshot.forEach(function(childSnapshot) {
                        var variants = childSnapshot.val().variants;
                        for (var key in variants) {
                            if (variants.hasOwnProperty(key)) {
                                var carVariant = variants[key].model;
                                if (carVariant) {
                                    var option = document.createElement('option');
                                    option.value = key; // Use key to easily find the variant later
                                    option.textContent = carVariant;
                                    carVariantSelect.appendChild(option);
                                }
                            }
                        }
                    });
                });
            }

            // Update car variants based on selected car model
            carModelSelect.addEventListener('change', fetchCarVariants);

            // Display original price based on selected car variant
            carVariantSelect.addEventListener('change', function() {
                var selectedVariantKey = carVariantSelect.value;
                var selectedCarModel = carModelSelect.value;

                var variantPriceRef = databaseRef.orderByChild('car_name').equalTo(selectedCarModel);
                variantPriceRef.once('value', function(snapshot) {
                    snapshot.forEach(function(childSnapshot) {
                        var variants = childSnapshot.val().variants;
                        if (variants && variants[selectedVariantKey]) {
                            var price = variants[selectedVariantKey].price;
                            originalPriceInput.value = price ? price : '';
                        }
                    });
                });
            });
        });
    </script>
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

        .container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .container form {
            margin-bottom: 20px;
        }

        .container label {
            font-weight: bold;
        }

        .container input[type="text"],
        .container input[type="submit"],
        .container select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Ensure padding and border are included in width */
        }

        .container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .container p {
            margin: 10px 0;
            color: #ff0000; /* Error message color */
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Add Offer</h2>
    <form method="post">
        <p><?php echo isset($statusMsg) ? $statusMsg : ''; ?></p>
        <label for="car_model">Car Model:</label><br>
        <select id="car_model" name="car_model" onchange="fetchCarVariants()">
            <option value="">Select a car model</option>
        </select><br><br>

        <label for="car_variant">Car Variant:</label><br>
        <select id="car_variant" name="car_variant">
            <option value="">Select a car variant</option>
        </select><br><br>

        <label>Original Price:</label><br>
        <input type="text" id="original_price" readonly><br><br>

        <label for="discount_price">Discount Price:</label><br>
        <input type="text" id="discount_price" name="discount_price"><br><br>

        <label for="image_url">Image URL (Mandatory):</label><br>
        <input type="text" id="image_url" name="image_url" required><br><br>

        <label for="offer_description">Offer Description:</label><br>
        <textarea id="offer_description" name="offer_description" rows="4" cols="50"></textarea><br><br>

        <input type="submit" name="submit" value="Submit">
    </form>
</div>
</body>
</html>
