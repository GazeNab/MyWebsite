<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// SELECT with table: list all registered users
$result = $conn->query("SELECT id, username, email, created_at FROM users ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="box" style="width: 600px;">
    <h2>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</h2>

    <table>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Created</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row["id"] ?></td>
        <td><?= htmlspecialchars($row["username"]) ?></td>
        <td><?= htmlspecialchars($row["email"]) ?></td>
        <td><?= $row["created_at"] ?></td>
      </tr>
      <?php endwhile; ?>
    </table>

    <a href="logout.php">Logout</a>
  </div>
</body>
</html>