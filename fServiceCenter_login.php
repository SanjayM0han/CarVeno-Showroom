<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Center Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body class="register-body">
    <div class="container">
        <form action="ServiceCenter_login.php" method="post" class="form">
            <h2>Service Center Login</h2>
            
            <div class="form-group">
                <label for="username">Service Center Name</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <p class="login-link">Don't have an account? <a href="ServiceCenter_register.php">Register</a></p>
        </form>
    </div>
    <?php
    session_start();
    $connection = mysqli_connect("localhost", "root", "", "major");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check if the username and password match in the "service_center_login" table
        $query = "SELECT COUNT(*) as count FROM service_center_login WHERE service_center_name = '$username' AND password = '$password'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($connection));
        }

        $row = mysqli_fetch_assoc($result);

        // Check if login credentials are valid
        if ($row['count'] > 0) {
            echo "Login successful!"; // Valid credentials
            $_SESSION['username'] = $username;
            header("Location: servicecenter.php"); // Redirect to the homepage or dashboard
        } else {
            echo "Invalid service center name or password."; // Invalid credentials
        }
    }
    ?>
</body>
</html>
