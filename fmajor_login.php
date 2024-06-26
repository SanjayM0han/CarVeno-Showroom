<?php
session_start();

// Check if form is submitted
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Firebase Realtime Database URL
    $databaseUrl = 'https://carapp-b9bc9-default-rtdb.firebaseio.com/';

    // Initialize cURL session
    $ch = curl_init();

    // Construct the URL for checking if the username exists
    $usernameExistsUrl = $databaseUrl . urlencode($username) . '.json';

    // Set cURL options for checking if the username exists
    curl_setopt($ch, CURLOPT_URL, $usernameExistsUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request to check if the username exists
    $usernameExistsResponse = curl_exec($ch);

    // Check if cURL request was successful
    if ($usernameExistsResponse === false) {
        die('Error: ' . curl_error($ch));
    }

    // If the username exists, you can proceed with further authentication logic
    if ($usernameExistsResponse !== 'null') {
        // Here you can add further authentication logic, like checking the password
        // For now, let's just redirect to a success page
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        // Username doesn't exist, show an error message or handle it accordingly
        $error = "Invalid username. Please check your username and try again.";
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
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body class="register-body">
    <div class="container">
        <form action="fmajor_login.php" method="post" class="form">
            <h2>Login</h2>
            <?php if(isset($error)) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <p class="login-link">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
    
</body>
</html>
