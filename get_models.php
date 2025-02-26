<?php
include 'db.php';

if (isset($_GET['brand'])) {
    $brand = $_GET['brand'];

    $sql = "SELECT id, model FROM cars WHERE brand = ? AND availability = 'Available'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $brand);
    $stmt->execute();
    $result = $stmt->get_result();

    $models = [];
    while ($row = $result->fetch_assoc()) {
        $models[] = $row;
    }

    echo json_encode($models);
}
?>
