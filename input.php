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
    :root{
        --main: #0D47A1;
        --sub: #0091EA;
        --light: #00B0FF;
    }
    #root{
        margin: 5px;
        height: 500px;
        overflow-y: scroll;
        font-size: 16pt;
    }
    #root li{
        margin: 10px 0;
    }
    .result span{
        margin: 0 10px;
    }
    .result input{
        text-align: center;
        width: 80px;
        font-size: 16pt;
    }
</style>
</head>
<body>
    <div class="content" style="padding: 50px 0 0 0">
        <h2>成績紀錄系統</h2>
        <button class="general_button" onClick="submitResult()" >送出成績</button>
        <hr>
        <ol id="root">
        </ol>
    </div>
<script>
var i, tr, num=0;
function input(el){
    if(el.value==""){
        $(el).next("span").html('請填寫成績');
        $(el).focus();
    }
    if(el.value.length==6){
        $(el).parent().next().children().focus();
        $.post("server/insert_result.php",{ranking: ($(el).parent().index()+1), bib: el.value},function(data){
            $(el).next("span").html(data);
        });
    }
}

function submitResult(){
    $.post("server/submit_result.php",function(data){
        alert(data);
    });
}    
    
function fetch(){
    $.post("server/get_result.php", function(data){
        console.log(num);
        var list = JSON.parse(data);
        if(list.length==0){
            $("#root").html('');
        }else{
            console.log(num);
            if(list.length>num){
                for(i=num; i<list.length; i++){
                    tr = list[i];
                    console.log(i);
                    console.log(tr);
                    $("#root").append('<li class="result"><span>'+tr[0]+'</span><input type="text" maxlength="6" value="'+tr[1]+'" required onKeyup="input(this)"><span></span></li>');
                }
            }
        }
        num = list.length;
    });
}
fetch();
setInterval(function(){
    fetch();
},4000);
</script>
</body>
</html>