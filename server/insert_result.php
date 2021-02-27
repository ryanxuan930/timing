<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$rank = $_POST["ranking"];
$bib = $_POST["bib"];
$stmt = $conn->prepare("INSERT INTO timing.result (rank,bib) VALUES (?,?)");
$stmt->bind_param("is",$rank,$result);
$stmt->execute();
if($conn->error){
    echo "儲存失敗：";
    echo $conn->error;
}else{
    echo "已儲存";
}
?>