<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "simple_login";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
}