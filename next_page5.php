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
        'exterior_color' => $_POST['exterior_color'],
        'interior_color' => $_POST['interior_color'],
        'engine' => $_POST['engine'],
        'transmission' => $_POST['transmission'],
        'price' => $_POST['price']

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
        
        <label for="exterior_color">Exterior Color:</label><br>
        <input type="text" id="exterior_color" name="exterior_color" required><br><br>
        
        <label for="interior_color">Interior Color:</label><br>
        <input type="text" id="interior_color" name="interior_color" required><br><br>
        
        <label for="engine">Engine:</label><br>
        <input type="text" id="engine" name="engine" required><br><br>
        
        <label for="transmission">Transmission:</label><br>
        <input type="text" id="transmission" name="transmission" required><br><br>
        
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" required><br><br>
        <!-- Add other input fields for car details -->

        <!-- Submit button -->
        <input type="submit" value="Submit">
    </form>
</body>
</html>
