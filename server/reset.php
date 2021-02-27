<?php
include_once("C:\wamp64\www\hsnua3\database.php");
include_once("C:\wamp64\www\hsnua3\dbclass.php");
$db = new database("timing");
$db->table("status");
$db->update("submit=0","submit=1");
$conn->query("TRUNCATE TABLE timing.result");
if($conn->error){
    echo $conn->error;
}else{
    echo "完成清除";
}
?>