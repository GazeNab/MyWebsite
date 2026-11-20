<?php

try {
    include __DIR__ . "/db.php";

} catch (mysqli_sql_exception $e) {
    
    die("Database Error: Unable to connect to the database. Please check your settings.");
}

?>