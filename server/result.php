<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$result = $_POST["result"];
$rank = $_POST["rank"];
$stmt = $conn->prepare("INSERT INTO timing.result (rank,result) VALUES (?,?)");
$stmt->bind_param("is",$rank,$result);
$stmt->execute();
if($conn->error){
    echo $conn->error;
}else{
    echo $rank.'-'.$result;
}
?>