<?php
include_once("C:\wamp64\www\hsnua3\dbclass.php");
$db = new database("timing");
$db->table("status");
$db->update("submit=1","submit=0");
if($db->error()){
    echo $db->error();
}else{
    echo "已送出成績";
}
?>