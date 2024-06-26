<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<section class="appointments">
        <h2>View Appointments</h2>
        <div id="appointments-container">
            <!-- Appointments will be displayed here -->
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <!-- Your footer content here -->
    </footer>

    <!-- Firebase JavaScript SDK -->
    <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.14.6/firebase-database.js"></script>
    <script>
        // Firebase configuration
        var firebaseConfig = {
            apiKey: "AIzaSyA8vr_5_3HpqcrM747m7GteKdMJhOvnlJI",
  authDomain: "car1-a63f8.firebaseapp.com",
  databaseURL: "https://car1-a63f8-default-rtdb.firebaseio.com",
  projectId: "car1-a63f8",
  storageBucket: "car1-a63f8.appspot.com",
  messagingSenderId: "258993009816",
  appId: "1:258993009816:web:7b57f4c18fca542d1a8ced"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        // Reference to the appointments node in the Firebase Realtime Database
        var appointmentsRef = firebase.database().ref('appointments');

        // Function to display appointments
        function displayAppointments(snapshot) {
            var appointmentsContainer = document.getElementById('appointments-container');
            appointmentsContainer.innerHTML = ''; // Clear previous appointments

            snapshot.forEach(function(childSnapshot) {
                var appointmentData = childSnapshot.val();

                // Create appointment element
                var appointmentDiv = document.createElement('div');
                appointmentDiv.classList.add('appointment');
                appointmentDiv.innerHTML = '<p><strong>Name:</strong> ' + appointmentData.customer_name + '</p>' +
                                           '<p><strong>Contact:</strong> ' + appointmentData.contact_number + '</p>' +
                                           '<p><strong>Car Make:</strong> ' + appointmentData.car_make + '</p>' +
                                           '<p><strong>Car Model:</strong> ' + appointmentData.car_model + '</p>' +
                                           '<p><strong>Service Date:</strong> ' + appointmentData.service_date + '</p>';
                
                appointmentsContainer.appendChild(appointmentDiv);
            });
        }

        // Fetch appointment details when the page loads and whenever there's a change in appointments
        appointmentsRef.on('value', function(snapshot) {
            displayAppointments(snapshot);
        });
    </script>
</body>
</html>
