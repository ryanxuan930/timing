<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$timestamp = intval($_POST["timestamp"]);
$status = intval($_POST["status"]);
$conn->select_db("timing");
$conn->query("UPDATE status SET timestamp={$timestamp}, status={$status}");
if($conn->error){
    echo $conn->error;
}
$conn->close();
?>