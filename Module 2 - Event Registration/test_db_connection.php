<?php
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP uses no password by default for 'root'
$dbname = "event"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the 'event' database";
?>
