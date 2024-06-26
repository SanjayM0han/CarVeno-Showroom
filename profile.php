<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; 
}
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your Firebase Realtime Database URL
    $databaseURL = "https://carapp-b9bc9-default-rtdb.firebaseio.com/";

    // Get the node name from the "Name" field
    $nodeName = $_POST['name'] ?? '';

    // Check if the node name is not empty
    if (!empty($nodeName)) {
        // Check if the node exists in Firebase
        $firebaseEndpoint = $databaseURL . 'service.json';
        $existingNodeData = file_get_contents($firebaseEndpoint);
        if ($existingNodeData !== false) {
            // Node exists, proceed to update profile data
            // Prepare data to be sent to Firebase Realtime Database
            $data = array(
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'photo_url' => isset($_POST['photo_url']) ? $_POST['photo_url'] : '', // Check if photo_url is set, set default to empty string if not
                'position' => $_POST['position'] ?? '', // Add the position field
                'username' => $username
                // Add other profile fields as needed
            );

            // Send data to Firebase Realtime Database
            $firebaseEndpoint = $databaseURL . 'service.json';
            $options = array(
                'http' => array(
                    'header'  => "Content-Type: application/json",
                    'method'  => 'POST',
                    'content' => json_encode($data)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($firebaseEndpoint, false, $context);

            // Check for errors
            if ($result === false) {
                $statusMsg = "Error sending data to Firebase Realtime Database.";
            } else {
                $statusMsg = "Profile updated successfully!";
            }
        } else {
            $statusMsg = "Error: Node does not exist in Firebase.";
        }
    } else {
        $statusMsg = "Error: Please enter a node name.";
    }
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
            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>
            
            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>
            
            <!-- Text input for position -->
            <label for="position">Position:</label><br>
            <input type="text" id="position" name="position" required><br><br>

            <!-- Text input for image URL -->
            <label for="photo_url">Photo URL:</label><br>
            <input type="text" id="photo_url" name="photo_url"><br><br>
            
            <input type="submit" name="submit" value="Update Profile">
        </form>
    </div>
</body>
</html>
