<?php
// Your Firebase Realtime Database URL
$databaseURL = "https://carapp-b9bc9-default-rtdb.firebaseio.com/";
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; 
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare data to be sent to Firebase Realtime Database
    $data = array(
        'email' => $_POST['email'] ?? '',
        'image' => $_POST['photo_url'] ?? '',
        'name' => $_POST['name'] ?? '',
        'position' => $_POST['position'] ?? '',
        'latitude' => $_POST['latitude'] ?? '',
        'longitude' => $_POST['longitude'] ?? '',
        'username' => $username // Add the username field
    );

    // Send data to Firebase Realtime Database
    $firebaseNode = "User"; // Construct the node path
    $firebaseEndpoint = $databaseURL . $firebaseNode . '.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    $result = curl_exec($ch);
    if ($result === false) {
        // Handle cURL error
        $error = curl_error($ch);
        // Output or log the error
        echo "cURL Error: " . $error;
    } else {
        $statusMsg = "Profile updated successfully!";
    }
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Profile</h2>
        <form method="post">
            <p><?php echo isset($statusMsg) ? $statusMsg : ''; ?></p>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>
            
            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>
            
            <label>Position:</label><br>
            <input type="text" name="position" required><br><br>
            
            <label for="photo_url">Photo URL:</label><br>
            <input type="text" id="photo_url" name="photo_url"><br><br>
            
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            
            <input type="submit" name="submit" value="Update Profile">
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            getLocation();
        });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            console.log(latitude);
            console.log(longitude)
        }

        function showError(error) {
            let errorMsg = "";
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = "User denied the request for Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = "Location information is unavailable.";
                    break;
                case error.TIMEOUT:
                    errorMsg = "The request to get user location timed out.";
                    break;
                case error.UNKNOWN_ERROR:
                    errorMsg = "An unknown error occurred.";
                    break;
            }
            alert(errorMsg);
        }
    </script>
</body>
</html>
