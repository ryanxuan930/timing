<?php
include_once("C:\wamp64\www\hsnua3\dbclass.php");
$db = new database("timing");
$db->table("result");
$result = $db->select_order("rank,result,bib","rank DESC");
$array = array();
$i=0;
while($row = $result->fetch_row()){
    $array[$i][0]==$row[1];
    if($row[1]){
        $array[$i][1]==$row[2];
    }else{
        $array[$i][1]=="";
    }
    $i++;
}
$obj = json_encode($array);
echo $obj;
?>