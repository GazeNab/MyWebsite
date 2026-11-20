<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "simple_login";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

?>