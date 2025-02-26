<?php
include 'db_connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM rentals WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"];
    $car_brand = $_POST["car_brand"];
    $model = $_POST["model"];
    $rental_due = $_POST["rental_due"];
    $availability = $_POST["availability"];

    $sql = "UPDATE rentals SET 
                customer_name='$customer_name', 
                car_brand='$car_brand', 
                model='$model', 
                rental_due='$rental_due', 
                availability='$availability'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rental</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            padding: 20px;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2em;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
            font-size: 0.95em;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            color: #333;
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #2c3e50;
            box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
        }

        select {
            background-color: white;
            cursor: pointer;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .update-btn {
            background-color: #2c3e50;
            color: white;
        }

        .update-btn:hover {
            background-color: #34495e;
        }

        .cancel-btn {
            background-color: #e74c3c;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #c0392b;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2c3e50;
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff4444;
            background-color: #ffe5e5;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9em;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 20px;
            }

            h1 {
                font-size: 1.6em;
                margin-bottom: 20px;
            }

            input, select, button {
                padding: 10px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Rental</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                <input type="text" id="customer_name" name="customer_name" value="<?= htmlspecialchars($row['customer_name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="car_brand">Car Brand:</label>
                <input type="text" id="car_brand" name="car_brand" value="<?= htmlspecialchars($row['car_brand']) ?>" required>
            </div>

            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?= htmlspecialchars($row['model']) ?>" required>
            </div>

            <div class="form-group">
                <label for="rental_due">Rental Due:</label>
                <input type="date" id="rental_due" name="rental_due" value="<?= htmlspecialchars($row['rental_due']) ?>">
            </div>

            <div class="form-group">
                <label for="availability">Availability:</label>
                <select id="availability" name="availability">
                    <option value="Available" <?= $row['availability'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Rented" <?= $row['availability'] == 'Rented' ? 'selected' : '' ?>>Rented</option>
                </select>
            </div>

            <div class="button-group">
                <button type="submit" class="update-btn">Update Rental</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
            </div>
        </form>
        <a href="index.php" class="back-link">← Back to Rental List</a>
    </div>
</body>
</html>