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
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Get data from the form
  $title = $_POST['title'];
  $event_date = $_POST['event_date'];
  $location = $_POST['location'];
  $advisor_name = $_POST['description'];

  // Prepare the SQL query to insert data
  $sql = "INSERT INTO events (title, event_date, location, description) VALUES (?, ?, ?, ?)";

  // Prepare the statement
  if ($stmt = $conn->prepare($sql)) {
    // Bind the parameters
    $stmt->bind_param("ssss", $title, $event_date, $location, $advisor_name);

    // Execute the statement
    if ($stmt->execute()) {
      echo "Data inserted successfully!";
    } else {
      echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  } else {
    echo "Error preparing the statement: " . $conn->error;
  }
  // Close the connection
  $conn->close();
}
?>
