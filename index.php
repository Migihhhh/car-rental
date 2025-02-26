<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default for XAMPP
$password = ""; // No password for XAMPP by default
$dbname = "car_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch rental data
$sql = "SELECT id, customer_name, car_brand, model, rental_due, availability FROM rentals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Car Rental Management</title>
</head>
<body>
   
    <nav class="navbar">
        <div class="nav-container">
            <!-- Left side: Logo and text -->
            <div class="nav-left">
                <img class="logo" src="logo.png" alt="Logo">
                <span class="site-title">NU-Royal Rides</span>
            </div>
            <!-- Right side: Login and Signup -->
            <div class="nav-right">
                <a href="#" class="nav-link">Login</a>
                <a href="#" class="signup-btn">Signup</a>
            </div>
        </div>
    </nav>

    <div class="container">

        <h1>Car Rental Management</h1>

        <!-- Add New Rental Button -->
        <a href="add.php" class="add-btn">+ Add New Rental</a>
        <a href="http://127.0.0.1:5501" class="add-btn">Return to Home</a>

        <table class="rental-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Car Brand</th>
                    <th>Model</th>
                    <th>Rental Due</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . ($row["customer_name"] ?: "-") . "</td>
                                <td>{$row["car_brand"]}</td>
                                <td>{$row["model"]}</td>
                                <td>" . ($row["rental_due"] ?: "-") . "</td>
                                <td><span class='status " . ($row["availability"] == "Available" ? "available" : "unavailable") . "'>{$row["availability"]}</span></td>
                                <td class='action-buttons'>
                                    <a href='edit.php?id={$row["id"]}' class='edit-btn'>Edit</a>
                                    <a href='delete.php?id={$row["id"]}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No rentals found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
