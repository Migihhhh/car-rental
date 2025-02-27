<?php
include 'db.php';

// Set JSON headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Query to count rentals per car brand
$query = "SELECT c.brand, COUNT(r.id) AS rented_count 
          FROM rentals r 
          JOIN cars c ON r.car_id = c.id 
          GROUP BY c.brand";

$result = $conn->query($query);

// Default values
$rentalData = [
    "bmw" => 0,
    "mercedes-benz" => 0,
    "audi" => 0
];

// Process results
while ($row = $result->fetch_assoc()) {
    $brand = strtolower($row['brand']); // Convert to lowercase
    if (isset($rentalData[$brand])) {
        $rentalData[$brand] = (int) $row['rented_count'];
    }
}

// Return JSON response
echo json_encode($rentalData);

$conn->close();
?>
