<?php
// Firebase Realtime Database URL
$databaseUrl = 'https://carapp-b9bc9-default-rtdb.firebaseio.com/';

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize cURL session
    $ch = curl_init();

    // Construct the URL for checking email existence
    $emailCheckUrl = $databaseUrl . 'Emails.json';

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $emailCheckUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check if cURL request was successful
    if ($response === false) {
        die('Error: ' . curl_error($ch));
    }

    // Decode the JSON response
    $emails = json_decode($response, true);

    // Check if the provided email exists in the Firebase database
    $emailExists = false;
    foreach ($emails as $dbEmail) {
        if ($dbEmail === $email) {
            $emailExists = true;
            break;
        }
    }

    if ($emailExists) {
        // Proceed with registration
        // Check if username already exists
        $usernameExistsUrl = $databaseUrl . 'registered/' . urlencode($username) . '.json';

        // Set cURL options for checking username existence
        curl_setopt($ch, CURLOPT_URL, $usernameExistsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request to check username existence
        $usernameExistsResponse = curl_exec($ch);

        // Check if cURL request was successful
        if ($usernameExistsResponse === false) {
            die('Error: ' . curl_error($ch));
        }

        // If the username doesn't exist, create a new node for it
        if ($usernameExistsResponse === 'null') {
            // Construct the URL for storing user data
            $registeredUrl = $databaseUrl . 'registered/' . urlencode($username) . '.json';

            // Set user data to be stored
            $userData = json_encode($email);

            // Set cURL options for storing user data
            curl_setopt($ch, CURLOPT_URL, $registeredUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL request to store user data
            $storeResponse = curl_exec($ch);

            // Check if cURL request was successful
            if ($storeResponse === false) {
                die('Error: ' . curl_error($ch));
            }

            // Create a new node with the username as its name
            // Construct the URL for creating the new node
            $newNodeUrl = $databaseUrl . urlencode($username) . '.json';

            // Set dummy data for the new node (you can change this to whatever you want)
            $dummyData = json_encode(['registered' => true]);

            // Set cURL options for creating the new node
            curl_setopt($ch, CURLOPT_URL, $newNodeUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dummyData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL request to create the new node
            $createNodeResponse = curl_exec($ch);

            // Check if cURL request was successful
            if ($createNodeResponse === false) {
                die('Error: ' . curl_error($ch));
            }

            // Redirect or do something else after successful registration
            // For example, redirect to a success page
            header("Location: fmajor_login.php");
            exit();
        } else {
            // Username already exists, show an error message or handle it accordingly
            $error = "Username already exists. Please choose a different username.";
        }
    } else {
        // Email doesn't exist in the database, show an error message or handle it accordingly
        $error = "Email not found. Please use a valid email.";
    }

    // Close cURL session
    curl_close($ch);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body class="register-body">
    <div class="container">
        <form id="registration_form" action="fmajor_register.php" method="post" class="form">
            <h2>Registration</h2>
            <?php if(isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" class="password" name="confirm_password" required>
                <span id="confirm_password_error" style="color: red;"></span>
            </div>
            <button type="submit">Register</button>
            <p class="login-link">Already have an account? <a href="major_login.php">Sign In</a></p>
        </form>
    </div>
</body>
</html>
