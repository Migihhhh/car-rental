<?php
session_start();
include 'db.php'; // Ensure this connects to your database

// Step 1: Collect Customer Information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_step'])) {
    $_SESSION['customer_name'] = $_POST['name'];
    $_SESSION['customer_address'] = $_POST['address'];
    $_SESSION['customer_contact'] = $_POST['contact'];
    $_SESSION['customer_email'] = $_POST['email'];

    // Redirect to Step 2 (Car Selection)
    header("Location: add.php?step=2");
    exit();
}

// Step 2: Select Car Brand & Model
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rental_step'])) {
    if (!isset($_SESSION['customer_name'])) {
        header("Location: add.php");
        exit();
    }

    $name = $_SESSION['customer_name'];
    $address = $_SESSION['customer_address'];
    $contact = $_SESSION['customer_contact'];
    $email = $_SESSION['customer_email'];
    $car_model_id = $_POST["car_model_id"];
    $rental_due = $_POST["rental_due"];

    // Check if customer exists
    $customer_check = $conn->query("SELECT id FROM customers WHERE email = '$email'");
    if ($customer_check->num_rows > 0) {
        $customer = $customer_check->fetch_assoc();
        $customer_id = $customer["id"];
    } else {
        // Insert new customer
        $conn->query("INSERT INTO customers (name, address, contact_number, email) 
                      VALUES ('$name', '$address', '$contact', '$email')");
        $customer_id = $conn->insert_id;
    }

    // Insert rental record
    $conn->query("INSERT INTO rentals (customer_id, car_id, rental_start, rental_due) 
                  VALUES ('$customer_id', '$car_model_id', NOW(), '$rental_due')");

    // Mark car as unavailable
    $conn->query("UPDATE cars SET availability = 'Unavailable' WHERE id = '$car_model_id'");

    // Clear session and redirect to main page
    session_destroy();
    header("Location: index.php");
    exit();
}

// Fetch only available brands
$brands = $conn->query("SELECT DISTINCT brand FROM cars WHERE availability = 'Available'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Rental</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <?php if (!isset($_GET['step']) || $_GET['step'] != 2) { ?>
        <!-- Step 1: Customer Information -->
        <h1>Step 1: Enter Your Details</h1>
        <form action="add.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" name="name" required>

            <label for="address">Address:</label>
            <input type="text" name="address" required>

            <label for="contact">Contact Number:</label>
            <input type="text" name="contact" required>

            <label for="email">Email Address:</label>
            <input type="email" name="email" required>

            <button type="submit" name="customer_step">Next: Select Car</button>
        </form>
    <?php } else { ?>
        <!-- Step 2: Select Car Brand & Model -->
        <h1>Step 2: Select Your Car</h1>
        <form action="add.php" method="POST">
            <label for="car_brand">Select Car Brand:</label>
            <select name="car_brand" id="car_brand" required>
                <option value="">Select Brand</option>
                <?php while ($brand = $brands->fetch_assoc()) { ?>
                    <option value="<?= $brand['brand'] ?>"><?= $brand['brand'] ?></option>
                <?php } ?>
            </select>

            <label for="car_model">Select Car Model:</label>
            <select name="car_model_id" id="car_model" required>
                <option value="">Select Model</option>
            </select>

            <label for="rental_due">Rental Due Date:</label>
            <input type="date" name="rental_due" required>

            <button type="submit" name="rental_step">Confirm Rental</button>
        </form>
    <?php } ?>
</div>

<script>
    $(document).ready(function () {
        $("#car_brand").change(function () {
            var brand = $(this).val();
            $.ajax({
                url: "get_models.php",
                type: "GET",
                data: { brand: brand },
                dataType: "json",
                success: function (data) {
                    var modelSelect = $("#car_model");
                    modelSelect.empty();
                    modelSelect.append('<option value="">Select Model</option>');

                    if (data.length > 0) {
                        data.forEach(function (item) {
                            modelSelect.append('<option value="' + item.id + '">' + item.model + '</option>');
                        });
                    } else {
                        modelSelect.append('<option value="">No available models</option>');
                    }
                }
            });
        });
    });
</script>

</body>
</html>
