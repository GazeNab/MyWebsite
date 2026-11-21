<?php

session_start();
include "../config/db_error.php";

if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add'])) {
    $product = trim($_POST['product']);
    $price = trim($_POST['price']);

    if ($product != "" && $price != "" && is_numeric($price) && $price >= 0) {
        $priceVal = (float)$price;

        $checkSql = "SELECT id FROM products WHERE product_name = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $product);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $message = "Product already exists!";
        } else {
            $sql = "INSERT INTO products (product_name, price) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sd", $product, $priceVal);

            if ($stmt->execute()) {
                $message = "Product added successfully!";
            } else {
                $message = "Unable to add product.";
            }

            $stmt->close();
        }

        $checkStmt->close();
    } else {
        $message = "Please enter a valid product name and price.";
    }
}

if (isset($_POST['update'])) {
    $product = trim($_POST['product']);
    $newProduct = trim($_POST['new_product']);
    $newPrice = trim($_POST['new_price']);

    if ($product != "" && $newProduct != "" && $newPrice != "" && is_numeric($newPrice) && $newPrice >= 0) {
        $newPriceVal = (float)$newPrice;

        $checkSql = "SELECT id FROM products WHERE product_name = ? AND product_name != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $newProduct, $product);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $message = "A product with that new name already exists!";
        } else {
            $sql = "UPDATE products SET product_name = ?, price = ? WHERE product_name = ? LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sds", $newProduct, $newPriceVal, $product);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "Product updated successfully!";
            } else {
                $message = "Product not found!";
            }

            $stmt->close();
        }

        $checkStmt->close();
    } else {
        $message = "Please enter product name, new product name, and valid new price.";
    }
}

if (isset($_POST['delete'])) {
    $product = trim($_POST['product']);

    if ($product != "") {
        $sql = "DELETE FROM products WHERE product_name = ? LIMIT 1";
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

$sql = "SELECT product_name, price FROM products";
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
        <div style="display: flex; gap: 8px;">
            <input type="text" name="product" placeholder="Enter product name" style="flex: 1; min-width: 0;">
            <input type="number" step="0.01" min="0" name="price" placeholder="Price" style="flex: 1; min-width: 0;">
        </div>
        <div style="display: flex; gap: 8px;">
            <input type="text" name="new_product" placeholder="New product name" style="flex: 1; min-width: 0;">
            <input type="number" step="0.01" min="0" name="new_price" placeholder="New price" style="flex: 1; min-width: 0;">
        </div>
        <button type="submit" name="add" class="add">Add Product</button>
        <button type="submit" name="update">Update Product</button>
        <button type="submit" name="delete" class="delete">Delete Product</button>
    </form>

    <div class="product-list">
        <h3>Product List</h3>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p>" . htmlspecialchars($row['product_name']) . " - ₱" . number_format((float)$row['price'], 2) . "</p>";
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