<?php

include "../config/db_error.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($fullname != "" && $username != "" && $password != "" && $confirm_password != "") {
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (fullname, username, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $fullname, $username, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can login now.";
            } else {
                $message = "Error registering user.";
            }

            $stmt->close();
        } else {
            $message = "Passwords do not match.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Register</h2>

    <p class="message"><?php echo htmlspecialchars($message); ?></p>

    <form action="" method="POST">
        <input type="text" name="fullname" placeholder="Full Name">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm_password" placeholder="Confirm Password">
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>