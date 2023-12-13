<?php
// Assuming you have a MySQL database setup
$servername = "localhost";
$username = "dre";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Extract form data
$name = $data['name'];
$email = $data['email'];
$isTransactionSuccessful = $data['isTransactionSuccessful'];

// Insert data into the database
$sql = "INSERT INTO applications (name, email, transaction_success) VALUES ('$name', '$email', '$isTransactionSuccessful')";

if ($conn->query($sql) === TRUE) {
    $response = array('success' => true);
} else {
    $response = array('success' => false);
}

// Close the database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
