<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Center Employee Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body>
    <?php
        session_start();
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];}
    ?>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <img src="images\Tata.png" alt="Service Center Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Service</a></li>
                <li><a href="view_appointment.php">View Appointment</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </nav>
        <div class="sign-out">
            <a href="#">Sign Out</a>
        </div>
    </header>

    <!-- Banner Section -->
    <section class="banner">
        <h1>Welcome to the Service Center Dashboard</h1>
        <p>Stay updated with the latest Service Features and offers.</p>
    </section>

    <!-- Quick Access Links -->
    <section class="quick-links">
        <div class="link">
            <p>Manage your Service Center</p>
        </div>
        
        <div class="link">
            <a href="#">Profile</a>
            <p>Update your personal information</p>
        </div>
    </section>

    <!-- Latest Updates or Notifications -->
    <section class="notifications">
        <h2>Latest Updates</h2>
        <ul>
            <li>Cars Booked appointments</li>
            
        </ul>
        <a href="#">View All Notifications</a>
    </section>

    <!-- Dashboard Section -->
    <section class="dashboard">
        <div class="metrics">
            <h2>Key Metrics</h2>
            <!-- Add charts or graphs for key metrics here -->
        </div>
        <div class="activities">
            <h2>Recent Activities</h2>
            <!-- Show recent activities performed by the Service Center employee -->
        </div>
    </section>

    
    <footer>
        <div class="contact-info">
            <p>Contact: servicecenter@example.com | Phone: 123-456-7890</p>
            <p>123 Service Center Street, City, Country</p>
        </div>
        <div class="legal">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
        </div>
    </footer>
</body>
</html>
