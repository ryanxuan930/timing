<?php
include_once("C:\wamp64\www\hsnua3\database.php");
$rank = $_POST["ranking"];
$bib = $_POST["bib"];
$stmt = $conn->prepare("UPDATE timing.result SET bib=? WHERE rank=?");
$stmt->bind_param("si",$bib,$rank);
$stmt->execute();
if($conn->error){
    echo "儲存失敗：";
    echo $conn->error;
}else{
    echo "已儲存";
}
?>