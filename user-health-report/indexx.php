<?php
// Database connection details
$host = "localhost";
$username = "your_mysql_username";
$password = "your_mysql_password";
$dbname = "your_database_name";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the email ID from the GET request
$email = $_GET["email"];

// Prepare and bind the SQL statement
$stmt = $conn->prepare("SELECT health_report_path FROM user_details WHERE email = ?");
$stmt->bind_param("s", $email);

// Execute the SQL statement
$stmt->execute();
$result = $stmt->get_result();

// Fetch the health report file path from the database
if ($row = $result->fetch_assoc()) {
  $healthReportPath = $row["health_report_path"];
  header("Content-type: application/pdf");
  header("Content-Disposition: inline; filename=health_report.pdf");
  @readfile($healthReportPath);
} else {
  echo "Health report not found for the given email ID.";
}

// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>
