<?php
include 'db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Validate connection
    if ($conn->connect_error) {
        throw new Exception("DB Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT LOWER(TRIM(c.brand)) AS car_brand, COUNT(r.id) AS rented_count 
              FROM rentals r 
              JOIN cars c ON r.car_id = c.id 
              GROUP BY c.brand";

    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $rentalData = [
        'bmw' => 0,
        'mercedes-benz' => 0,
        'audi' => 0
    ];

    while ($row = $result->fetch_assoc()) {
        $brand = strtolower(trim($row['car_brand']));
        if (array_key_exists($brand, $rentalData)) {
            $rentalData[$brand] = (int)$row['rented_count'];
        }
    }

    echo json_encode($rentalData);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
exit;
?>