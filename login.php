<?php
session_start();
require_once "config/db.php";

$message = "";

if (isset($_GET["registered"])) {
    $message = "Registration successful. Please log in.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // SELECT * with WHERE: find the user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Wrong email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="box">
    <h2>Login</h2>

    <?php if ($message): ?>
      <p class="<?= isset($_GET['registered']) ? 'success' : 'message' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <a href="register.php">No account yet? Register</a>
  </div>
</body>
</html>