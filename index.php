<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showroom Employee Dashboard</title>
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
            <img src="images\Tata.png" alt="Showroom Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Inventory</a></li>
                <li><a href="#">Offers</a></li>
                <li><a href="carvenoadmin.php">Profile</a></li>
            </ul>
        </nav>
        <div class="sign-out">
            <a href="#">Sign Out</a>
        </div>
    </header>

    <!-- Banner Section -->
    <section class="banner">
        <h1>Welcome to the Showroom Dashboard</h1>
        <p>Stay updated with the latest inventory and offers.</p>
    </section>

    <!-- Quick Access Links -->
    <section class="quick-links">
        <div class="link">
            <a href="p.php">Inventory</a>
            <p>Manage your showroom inventory</p>
        </div>
        <div class="link">
            <a href="viewoffer.php">Offers</a>
            <p>Create and manage special offers</p>
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
            <li>New car added to inventory</li>
            <li>Special discount offer created</li>
            <!-- Add more notifications here -->
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
            <!-- Show recent activities performed by the showroom employee -->
        </div>
    </section>

    <!-- Quick Actions Section -->
    <section class="quick-actions">
        <button onclick="window.location.href='inventory.php'">Add New Car</button>
        <button onclick="window.location.href='addoffer.php'">Create Offer</button>
        <button onclick="window.location.href='view_enquiry.php'">View Analytics</button>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="contact-info">
            <p>Contact: showroom@example.com | Phone: 123-456-7890</p>
            <p>123 Showroom Street, City, Country</p>
        </div>
        <div class="legal">
            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
        </div>
    </footer>
</body>
</html>
