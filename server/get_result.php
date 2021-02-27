<?php
include_once("C:\wamp64\www\hsnua3\dbclass.php");
$db = new database("timing");
$db->table("result");
$result = $db->select("rank");
$array = array();
$array["ranking"] = $result->num_rows;
$obj = json_encode($array);
echo $obj;
?>