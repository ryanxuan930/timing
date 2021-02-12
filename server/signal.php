<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$timestamp = $_POST["timestamp"];
$status = $_POST["status"];
$conn->select_db("timing");
$conn->query("UPDATE status SET timestamp={$timestamp}, status={$status}");
$conn->close();
?>