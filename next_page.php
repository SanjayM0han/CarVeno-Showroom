<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

// Firebase Realtime Database configuration
$databaseURL = "https://carapp-b9bc9-default-rtdb.firebaseio.com/";

if (!isset($_GET['car_name']) || !isset($_GET['num_variants']) || !isset($_GET['current_variant'])) {
    // Redirect back to inventory.php if car_name, num_variants, or current_variant is not set
    header("Location: inventory.php");
    exit();
}

$carName = $_GET['car_name'];
$currentVariant = $_GET['current_variant'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare data to be sent to Firebase Realtime Database
    $data = array(
        'model' => $_POST['model'],
        'year' => $_POST['year'],
        'mileage' => $_POST['mileage'],
        'conditions' => $_POST['conditions'],
        'exterior_colors' => $_POST['exterior_colors'],
        'interior_colors' => $_POST['interior_colors'],
        'no_of_exterior_colors' => $_POST['no_ext_colors'],
        'no_of_interior_colors' => $_POST['no_int_colors'],
        'engine' => $_POST['engine'],
        'transmission' => $_POST['transmission'],
        'price' => $_POST['price'],
        'no_of_cylinders' => $_POST['no_of_cylinders'],
        'no_of_airbags' => $_POST['no_of_airbags'],
        'seating_capacity' => $_POST['seating_capacity']
        // Add other fields as needed
    );

    // Initialize cURL session
    $ch = curl_init();

    // Set the cURL options
    curl_setopt($ch, CURLOPT_URL, $databaseURL . "/$username.json"); // Firebase endpoint under specific node
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // Retrieve data using GET method

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        die(curl_error($ch));
    }

    // Close cURL session
    curl_close($ch);

    // Convert JSON response to associative array
    $carData = json_decode($response, true);

    // Check if the car entry exists
    $carKey = null;
    foreach ($carData as $key => $value) {
        if ($value['car_name'] === $carName) {
            $carKey = $key;
            break;
        }
    }

    if ($carKey !== null) {
        // Add the variants node under the car entry
        curl_setopt($ch, CURLOPT_URL, $databaseURL . "/$username/$carKey/variants.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Add data using POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Data to be sent in JSON format

        // Execute cURL session
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            die(curl_error($ch));
        }

        // Close cURL session
        curl_close($ch);

        // Redirect to the next variant page or success page based on the number of variants
        $numVariants = $_GET['num_variants'];
        if ($currentVariant < $numVariants) {
            $nextVariant = $currentVariant + 1;
            header("Location: next_page{$nextVariant}.php?car_name=$carName&num_variants=$numVariants&current_variant=$nextVariant");
            exit();
        } else {
            header("Location: success_page.php");
            exit();
        }
    } else {
        // Car entry not found, redirect back to inventory.php
        header("Location: inventory.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car Details - Variant <?php echo $currentVariant; ?></title>
    <style>
        /* Your CSS styles */
    </style>
    <script>
        function updateColorInputs() {
            // Update exterior color inputs
            var numExtColors = document.getElementById('no_ext_colors').value;
            var extColorDiv = document.getElementById('extColorInputs');
            extColorDiv.innerHTML = '';

            for (var i = 0; i < numExtColors; i++) {
                var label = document.createElement('label');
                label.for = 'exterior_color_' + (i + 1);
                label.textContent = 'Exterior Color ' + (i + 1) + ':';

                var input = document.createElement('input');
                input.type = 'color';
                input.id = 'exterior_color_' + (i + 1);
                input.name = 'exterior_colors[]';
                input.required = true;

                extColorDiv.appendChild(label);
                extColorDiv.appendChild(document.createElement('br'));
                extColorDiv.appendChild(input);
                extColorDiv.appendChild(document.createElement('br'));
            }

            // Update interior color inputs
            var numIntColors = document.getElementById('no_int_colors').value;
            var intColorDiv = document.getElementById('intColorInputs');
            intColorDiv.innerHTML = '';

            for (var i = 0; i < numIntColors; i++) {
                var label = document.createElement('label');
                label.for = 'interior_color_' + (i + 1);
                label.textContent = 'Interior Color ' + (i + 1) + ':';

                var input = document.createElement('input');
                input.type = 'color';
                input.id = 'interior_color_' + (i + 1);
                input.name = 'interior_colors[]';
                input.required = true;

                intColorDiv.appendChild(label);
                intColorDiv.appendChild(document.createElement('br'));
                intColorDiv.appendChild(input);
                intColorDiv.appendChild(document.createElement('br'));
            }
        }
    </script>
</head>
<body>
    <h1>Add Car Details - Variant <?php echo $currentVariant; ?></h1>
    <form method="post">
        <!-- Input fields for car details -->
        <label for="model">Model:</label><br>
        <input type="text" id="model" name="model" required><br><br>

        <label for="year">Year:</label><br>
        <input type="number" id="year" name="year" required><br><br>
        
        <label for="mileage">Mileage:</label><br>
        <input type="number" id="mileage" name="mileage" required><br><br>
        
        <label for="conditions">Conditions:</label><br>
        <input type="text" id="conditions" name="conditions" required><br><br>

        <label for="no_ext_colors">Number of Exterior Colors:</label><br>
        <select id="no_ext_colors" name="no_ext_colors" onchange="updateColorInputs()" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select><br><br>

        <div id="extColorInputs">
            <label for="exterior_color_1">Exterior Color 1:</label><br>
            <input type="color" id="exterior_color_1" name="exterior_colors[]" required><br><br>
        </div>

        <label for="no_int_colors">Number of Interior Colors:</label><br>
        <select id="no_int_colors" name="no_int_colors" onchange="updateColorInputs()" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select><br><br>

        <div id="intColorInputs">
            <label for="interior_color_1">Interior Color 1:</label><br>
            <input type="color" id="interior_color_1" name="interior_colors[]" required><br><br>
        </div>

        <label for="engine">Engine:</label><br>
        <input type="text" id="engine" name="engine" required><br><br>
        
        <label for="transmission">Transmission:</label><br>
        <input type="text" id="transmission" name="transmission" required><br><br>
        
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" required><br><br>

        <label for="no_of_cylinders">Number of Cylinders:</label><br>
        <input type="number" id="no_of_cylinders" name="no_of_cylinders" required><br><br>

        <label for="no_of_airbags">Number of Airbags:</label><br>
        <input type="number" id="no_of_airbags" name="no_of_airbags" required><br><br>

        <label for="seating_capacity">Seating Capacity:</label><br>
        <input type="text" id="seating_capacity" name="seating_capacity" required><br><br>

        <!-- Submit button -->
        <input type="submit" value="Submit">
    </form>
</body>
</html>
