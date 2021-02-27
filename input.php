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
<style>
    #root{
        margin: 5px;
    }
    #root li{
        margin: 5px 0;
    }
    .result{
        text-align: right;
        width: 100px;
    }
</style>
</head>
<body>
    <div class="content" style="padding: 50px 0 0 0">
        <h2>成績紀錄系統</h2>
        <hr>
        <ol id="root" reversed></ol>
    </div>
<script>
var i, tr;

function format(el){
    if(el.value.length==2){
        el.value=el.value+":";
    }
    if(el.value.length==5){
        el.value=el.value+".";
    }
    if(el.value.length==8){
        $(el).parent().next().children().focus();
        $(el).parent().append('<span>已送出</span>');
    }
}

function fetch(){
    $.post("server/get_result.php", function(data){
        var list = JSON.parse(data);
        for(i=0; i<list.length); i++){
            $("#root").prepend('<li><input class="result" type="text" maxlength="8" required onKeyup="format(this)"></li>');
        }
    });
}
setInterval(fetch(),2000);
fetch();
</script>
</body>
</html>