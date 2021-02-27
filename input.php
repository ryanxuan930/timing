<?php
session_start();
if(!isset($_SESSION["account"])){
    echo '<script>alert("尚未登入");location.href="index.html"</script>';
    exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="Rikujo Timing System">
<meta name="author" content="Ryan Po-Hsuan Chang from Rikujo Labs">
<meta name="copyright" content="Ryan Po-Hsuan Chang All Rights Reserved">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="icon" href="https://sports.hs.ntnu.edu.tw/hsnua3/image/logo.ico" type="image/x-icon"/>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap" as="style" onload="this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap" rel="stylesheet"></noscript>
<link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons" as="style" onload="this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"></noscript>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="layout.css">
<link rel="stylesheet" href="button.css">
<title>Rikujo Timing System</title>
</head>
<body>
    <div class="content" style="padding: 50px 0 0 0">
        <h2>成績紀錄系統</h2>
        <hr>
        <ol id="root">
        </ol>
    </div>
<script>
var i, tr, num=0, id;
function submitResult(){
    id = $(this).val()
    alert(id);
}
function fetch(){
    $.post("server/get_result.php", function(data){
        var list = JSON.parse(data);
        num = list.ranking;
        if($("#result").length<num){
            for(i=0; i<(num-$("#result").length); i++){
                $("#root").append('<li><input class="result" type="text" maxlength="8" required onBlur="submitResult()"></li>');
            }
        }
    });
}
fetch();
</script>
</body>
</html>