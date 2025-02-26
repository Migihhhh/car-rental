<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"];
    $car_brand = $_POST["car_brand"];
    $model = $_POST["model"];
    $rental_due = $_POST["rental_due"];

    $availability = "Rented";

    // Insert data into the database
    $sql = "INSERT INTO rentals (customer_name, car_brand, model, rental_due, availability) 
            VALUES ('$customer_name', '$car_brand', '$model', '$rental_due', '$availability')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Redirect to the rental list page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Rental</title>
    <link rel="stylesheet" href="addstyle.css">
</head>
<body>
    <div class="container">
        <h1>Add New Rental</h1>
        <form method="post">
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" id="customer_name" name="customer_name" required>
            </div>

            <div class="form-group">
                <label for="car_brand">Car Brand:</label>
                <input type="text" id="car_brand" name="car_brand" required>
            </div>

            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>

            <div class="form-group">
                <label for="rental_due">Rental Due:</label>
                <input type="date" id="rental_due" name="rental_due">
            </div>

            <button type="submit">Add Rental</button>
        </form>
        <a href="index.php" class="back-link">← Back to Rental List</a>
    </div>
</body>
</html>
