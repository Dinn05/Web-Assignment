<?php
// Database connection details
$host = "localhost"; // Database host
$username = "root"; // Database username (default for XAMPP/WAMP is "root")
$password = ""; // Database password (default for XAMPP/WAMP is empty)
$database = "event_dat"; // Your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch upcoming events from the database
$sql = "SELECT event_name, event_date, event_location, event_advisor FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>
