<?php
// Connect to your database
$servername = "localhost"; // replace with your database server name
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "mypetakom"; // replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get data from the form
  $title = $_POST['title'];
  $event_date = $_POST['event_date'];
  $location = $_POST['location'];
  $advisor_name = $_POST['description'];

  // Prepare the SQL query to insert data
  $sql = "INSERT INTO event (title, event_date, location, advisor_name) 
          VALUES ('$title', '$event_date', '$location', '$advisor_name')";

  // Execute the query and check if it's successful
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close the connection
  $conn->close();
}
?>
