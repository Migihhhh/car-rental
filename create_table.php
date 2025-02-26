<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rental";

// Create connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    car_brand VARCHAR(255) NOT NULL,
    model VARCHAR(255) NOT NULL,
    rental_due DATE,
    availability ENUM('Available', 'Unavailable') NOT NULL DEFAULT 'Available'
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'rentals' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
