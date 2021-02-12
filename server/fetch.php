<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$conn->select_db("timing");
$result = $conn->query("SELECT * FROM status");
$array = array();
while($row = $result->fetch_row()){
    $array[0]=$row[0];
    $array[1]=$row[1];
}
$obj = json_encode($array);
echo $obj;
?>