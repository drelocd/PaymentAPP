<?php
// Database connection parameters
$servername = "localhost";
$username = "dre";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database
$sql = "CREATE DATABASE IF NOT EXISTS daraja_application_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("daraja_application_db");

// Create the job_applications table
$sql = "CREATE TABLE IF NOT EXISTS job_applications (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    cv VARCHAR(255) NOT NULL,
    resume VARCHAR(255) NOT NULL,
    age INT(3) NOT NULL,
    location VARCHAR(50) NOT NULL,
    kra_pin VARCHAR(15) NOT NULL,
    id_number VARCHAR(15) NOT NULL,
    position VARCHAR(50) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close connection
$conn->close();
?>
