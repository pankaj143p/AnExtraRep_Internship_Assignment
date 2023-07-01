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

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO user_details (name, age, weight, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("siss", $name, $age, $weight, $email);

// Get the form data from POST request
$name = $_POST["name"];
$age = $_POST["age"];
$weight = $_POST["weight"];
$email = $_POST["email"];

// Execute the SQL statement
if ($stmt->execute()) {
  // If the user details are inserted successfully, upload the health report PDF
  $last_inserted_id = $conn->insert_id;
  $upload_directory = "health_reports/";
  $target_file = $upload_directory . basename($_FILES["healthReport"]["name"]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Check if the uploaded file is a valid PDF
  if ($fileType != "pdf") {
    echo json_encode(array("success" => false));
    exit;
  }

  // Move the uploaded file to the server's health_reports directory
  if (move_uploaded_file($_FILES["healthReport"]["tmp_name"], $target_file)) {
    // Update the health report file path in the database
    $stmt_update = $conn->prepare("UPDATE user_details SET health_report_path = ? WHERE id = ?");
    $stmt_update->bind_param("si", $healthReportPath, $userId);

    $healthReportPath = $target_file;
    $userId = $last_inserted_id;

    if ($stmt_update->execute()) {
      echo json_encode(array("success" => true));
    } else {
      echo json_encode(array("success" => false));
    }
  } else {
    echo json_encode(array("success" => false));
  }
} else {
  echo json_encode(array("success" => false));
}

// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>
