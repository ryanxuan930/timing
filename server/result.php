<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$rank = $_POST["rank"];
$result = $_POST["result"];
$stmt = $conn->prepare("INSERT INTO timing.result (rank,result) VALUES (?,?)");
$stmt->bind_param("is",$rank,$result);
$stmt->execute();
if($conn->error){
    echo $conn->error;
}else{
    echo $rank.'-'.$result;
}
?>