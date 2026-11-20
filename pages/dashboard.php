<?php

session_start();
include "../config/db.php";

if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add'])) {
    $product = trim($_POST['product']);

    if ($product != "") {
        $sql = "INSERT INTO products (product_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product);

        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $message = "Unable to add product.";
        }

        $stmt->close();
    } else {
        $message = "Please enter a product name.";
    }
}

if (isset($_POST['delete'])) {
    $product = trim($_POST['product']);

    if ($product != "") {
        $sql = "DELETE FROM products WHERE product_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $product);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Product deleted successfully!";
        } else {
            $message = "Product not found!";
        }

        $stmt->close();
    } else {
        $message = "Please enter a product name.";
    }
}

$sql = "SELECT product_name FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Product Dashboard</h2>

    <p>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</p>

    <p class="message"><?php echo htmlspecialchars($message); ?></p>

    <form action="" method="POST">
        <input type="text" name="product" placeholder="Enter product name">
        <button type="submit" name="add">Add Product</button>
        <button type="submit" name="delete" class="delete">Delete Product</button>
    </form>

    <div class="product-list">
        <h3>Product List</h3>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>" . htmlspecialchars($row['product_name']) . "</p>";
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </div><br>

    <a href="logout.php">Logout</a>
</div>

</body>
</html>