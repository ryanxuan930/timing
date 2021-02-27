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
        height: 500px;
        overflow-y: scroll;
    }
    #root li{
        margin: 5px 0;
    }
    .result span{
        margin: 0 10px;
    }
    .result input{
        text-align: right;
        width: 60px;
    }
</style>
</head>
<body>
    <div class="content" style="padding: 50px 0 0 0">
        <h2>成績紀錄系統</h2>
        <hr>
        <ol id="root"></ol>
    </div>
<script>
var i, tr;
/*
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
*/
function input(el){
    if(el.value==""){
        $(el).next("span").html('請填寫成績');
        $(el).focus();
    }
    if(el.value.length==6){
        $(el).parent().next().children().focus();
        $(el).next("span").html('已送出');
        alert($(el).parent().index()+1);
    }
}

function fetch(){
    $.post("server/get_result.php", function(data){
        var list = JSON.parse(data);
        $("#root").html('');
        for(i=0; i<list.length; i++){
            tr = list[i];
            $("#root").prepend('<li class="result"><span>'+tr[0]+'</span><input type="text" maxlength="6" value="'+tr[1]+'" required onKeyup="input(this)"><span></span></li>');
        }
    });
}
fetch();
setInterval(function(){
    fetch();
},3000);
</script>
</body>
</html>