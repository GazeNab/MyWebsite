<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$errorMsg = "";

try {
    include "db.php";
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
}

if ($errorMsg != "") {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Error</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
    <h2>Database Error</h2>
    <p style="font-size: 20px;" class="message"><?php echo htmlspecialchars($errorMsg); ?></p>
</div>
</body>
</html>
<?php
    exit();
}