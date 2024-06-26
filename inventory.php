<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; 
}
$databaseURL = "https://carapp-b9bc9-default-rtdb.firebaseio.com/";

$currentVariant = isset($_GET['current_variant']) ? $_GET['current_variant'] : 0;
$numVariants = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numVariants = $_POST['num_variants'];
    $currentVariant = isset($_GET['current_variant']) ? $_GET['current_variant'] : 0;

    $data = array(
        'car_name' => $_POST['car_name'],
        'image_url1' => $_POST['image_url1'],
        'image_url2' => $_POST['image_url2'],
        'image_url3' => $_POST['image_url3'],
        'image_url4' => $_POST['image_url4'],
        'image_url5' => $_POST['image_url5'],
        'num_variants' => $_POST['num_variants'],
        'body_type' => $_POST['body_type']
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $databaseURL . "/$username.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if ($response === false) {
        die(curl_error($ch));
    }

    curl_close($ch);

    $responseData = json_decode($response, true);
    $key = key($responseData);

    $variantData = array(
        'conditions' => "",
        'engine' => "",
        'exterior_color' => "",
        'interior_color' => "",
        'mileage' => "",
        'model' => "",
        'transmission' => "",
        'price' => "",
        'year' => "",
        'body_type' => ""
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $databaseURL . "/$username/$key/variants/variant1.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($variantData));

    $response = curl_exec($ch);

    if ($response === false) {
        die(curl_error($ch));
    }

    curl_close($ch);

    if ($currentVariant >= $numVariants) {
        header("Location: success_page.php");
        exit();
    } else {
        $nextVariant = $currentVariant + 1;
        // Pass the key and car name to next_page.php
        header("Location: next_page.php?key=$key&car_name=" . urlencode($_POST['car_name']) . "&num_variants=$numVariants&current_variant=$nextVariant");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car Details</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <h1>Add Car Details</h1>
    <form method="post">
        <!-- Input fields for car details -->
        <label for="car_name">Car Name:</label><br>
        <input type="text" id="car_name" name="car_name" required><br><br>
        <label for="image_url1">Car Image URL 1:</label><br>
        <input type="text" id="image_url1" name="image_url1" required><br><br>
        <label for="image_url2">Car Image URL 2:</label><br>
        <input type="text" id="image_url2" name="image_url2" required><br><br>
        <label for="image_url3">Car Image URL 3:</label><br>
        <input type="text" id="image_url3" name="image_url3" required><br><br>
        <label for="image_url4">Car Image URL 4:</label><br>
        <input type="text" id="image_url4" name="image_url4" required><br><br>
        <label for="image_url5">Car Image URL 5:</label><br>
        <input type="text" id="image_url5" name="image_url5" required><br><br>

        <!-- Dropdown for selecting number of variants -->
        <label for="num_variants">Number of Variants:</label><br>
        <select id="num_variants" name="num_variants" required>
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
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
        </select><br><br>
        <label for="body_type">Body Type:</label><br>
        <select id="body_type" name="body_type" required>
            <option value = "Hatchback">Hatchback</option>
            <option value = "Sedan">Sedan</option>
            <option value = "SUV">SUV</option>
            <option value = "MUV">MUV</option>
            <option value = "Convertible">Convertible</option>
            <option value = "Coupe">Coupe</option>
            <option value = "Pickup Truck">Pickup Truck</option>
        </select><br><br>    
        <!-- Submit button -->
        <input type="submit" value="Next">
    </form>
</body>
</html>
