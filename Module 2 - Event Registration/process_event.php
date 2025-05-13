<?php
// Database connection details
$host = "localhost"; // Database host
$username = "root"; // Database username (default for XAMPP/WAMP is "root")
$password = ""; // Database password (default for XAMPP/WAMP is empty)
$database = "event_dat"; // Your database name

// Create a connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the database exists, if not, create it
$db_check_query = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($db_check_query) === TRUE) {
    // Use the database
    $conn->select_db($database);
} else {
    echo "Error creating database: " . $conn->error;
    exit;
}

// Create the events table if it doesn't exist
$table_check_query = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_description TEXT NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    event_advisor VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($table_check_query) !== TRUE) {
    echo "Error creating table: " . $conn->error;
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $event_name = $_POST['event-name'];
    $event_description = $_POST['event-description'];
    $event_location = $_POST['event-location'];
    $event_date = $_POST['event-date'];
    $event_advisor = $_POST['event-advisor'];

    // Simple validation
    if (empty($event_name) || empty($event_description) || empty($event_location) || empty($event_date) || empty($event_advisor)) {
        echo "All fields are required!";
        exit;
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO events (event_name, event_description, event_location, event_date, event_advisor) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $event_name, $event_description, $event_location, $event_date, $event_advisor);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Event registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
}
?>