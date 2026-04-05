<?php
$connection = new mysqli("localhost", "root", "", "remittance_system");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
