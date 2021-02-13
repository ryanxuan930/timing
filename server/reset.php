<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$conn->query("TRUNCATE TABLE timing.result");
if($conn->error){
    echo $conn->error;
}else{
    echo "完成清除";
}
?>