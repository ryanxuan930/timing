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
<style>
    *{
        box-sizing: border-box;
    }
    @font-face {
        font-family: Digital;
        src: url("font/digital-7 (mono).ttf");
    }
    :root{
        --main: #0D47A1;
        --sub: #0091EA;
        --light: #00B0FF;
    }
    .grid{
        margin: 20px;
        display: grid;
        grid-gap: 10px;
        grid-template-columns: 60% 40%;
    }
    .grid > div {
        background-color: #212121;
        padding: 10px;
    }
    .grid_header{
        grid-column: 1/3;
    }
    .grid_right{
        grid-column: 2/3;
        grid-row: 2/4;
        height: 70vh;
        overflow-y: scroll;
    }
    li{
        font-size: 20px;
    }
    .grid_center{
        text-align: center;
    }
    .stopwatch{
        font-family: Digital, sans-serif;
        color: var(--light);
        font-size: 20vh;
    }
</style>
<link rel="stylesheet" href="layout.css">
<link rel="stylesheet" href="button.css">
<title>Rikujo Timing System</title>
</head>
<body>
    <div class="grid">
        <div class="grid_header">
            <h1>Rikujo Timing System (Main Terminal)</h1>
        </div>
        <div class="grid_center">
            <h1>師大附中74週年校慶迷你馬拉松</h1>
            <div class="stopwatch"></div>
            <h1>OFFICIAL TIME</h1>
            <h2>大會時間</h2>
        </div>
        <div class="grid_right">
            <div style="margin: 5px;">
                <h2>終點時間列表</h2>
                <div>
                    <ol class="results" reversed ></ol>
                </div>
            </div>
            
        </div>
        <div class="grid_bottom">
            <button class="arrow_button" onClick="stopwatch.start();"><span>開始 </span></button>
            <button class="arrow_button" onClick="stopwatch.lap();"><span>紀錄 </span></button>
            <button class="arrow_button" onClick="stopwatch.stop();"><span>停止 </span></button>
            <button class="arrow_button" onClick="stopwatch.clean();"><span>歸零 </span></button>
            <button class="arrow_button" onClick="stopwatch.clear();"><span>清除 </span></button>
            <div>
                <span>終端和主機時間相差：</span>
                <span id="shift"></span>
                <br>
                <span>終端和主機傳輸延遲：</span>
                <span id="latency"></span>
                <br>
                <span id="starting_time"></span>
            </div>
            <div>
                <button class="general_button" onClick="location.href='input.php'">成績紀錄頁面</button>
                <button class="general_button" onClick="location.href='start.html'">起跑電槍頁面</button>
            </div>
        </div>
    </div>
<script src="time_sync.js"></script>
<script>
function signal(status){
    var timestamp = Date.now() + currentShift;
    var time = new Date(timestamp);
    if(status==1){
        $("#starting_time").html("開始計時時間："+ time.toLocaleString("zh-TW"));
    }else if(status==0){
        $("#starting_time").html("停止計時");
    }else if(status==3){
        $("#info").html("起跑犯規");
    }
    $.post("server/signal.php",{timestamp: timestamp, status: status},function(data){
        console.log(data);
    });
}
    
function result(rank,input){
    $.post("server/result.php",{rank: rank, result: input},function(data){
        console.log(data);
    })
}

function cleanResult(){
    $.post("server/reset.php", function(data){
        console.log(data);
    })
}

class Stopwatch {
    constructor(display, results) {
        this.running = false;
        this.display = display;
        this.results = results;
        this.laps = [];
        this.reset();
        this.print(this.times);
        this.order = 0;
    }
    
	ini(m,s,f){
		this.times = [ m, s, f ];
	}
    reset() {
        this.times = [ 0, 0, 0 ];
    }
    
    start() {
        this.times = [11, 0, 0]
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
            signal(1);
        }
    }
    
    lap() {
        let times = this.times;
        let li = document.createElement('li');
        li.innerText = this.format(times);
        this.results.prepend(li);
        $(".results").scrollTop();
        this.order++;
        result(this.order, this.format(times));
    }
    
    stop() {
        this.running = false;
        this.time = null;
        signal(0);
    }

    restart() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
        this.reset();
    }
    
    clear() {
        var check = confirm("確定清除紀錄？");
        if(check){
            clearChildren(this.results);
            this.order=0;
            cleanResult();
        }
    }
    
    step(timestamp) {
        if (!this.running) return;
        this.calculate(timestamp);
        this.time = timestamp;
        this.print();
        requestAnimationFrame(this.step.bind(this));
    }
    
    calculate(timestamp) {
        var diff = timestamp - this.time;
        // Hundredths of a second are 100 ms
        this.times[2] += diff / 10;
        // Seconds are 100 hundredths of a second
        if (this.times[2] >= 100) {
            this.times[1] += 1;
            this.times[2] -= 100;
        }
        // Minutes are 60 seconds
        if (this.times[1] >= 60) {
            this.times[0] += 1;
            this.times[1] -= 60;
        }
    }
    
    print() {
        this.display.innerText = this.format(this.times);
    }
    
    clean(){
        this.stop();
		this.reset();
		this.print();
        signal(2);
        signal(2);
    }
    
    format(times) {
        return `\
${pad0(times[0], 2)}:\
${pad0(times[1], 2)}:\
${pad0(Math.floor(times[2]), 2)}`;
    }
}

function pad0(value, count) {
    var result = value.toString();
    for (; result.length < count; --count)
        result = '0' + result;
    return result;
}

function clearChildren(node) {
    while (node.lastChild)
        node.removeChild(node.lastChild);
}

let stopwatch = new Stopwatch(
    document.querySelector('.stopwatch'),
    document.querySelector('.results')
);
document.addEventListener('keypress', function(event) {
	if (event.code == 'Space') {
		stopwatch.lap();
	}
    if (event.code == 'KeyG') {
		stopwatch.start();
	}
	if (event.code == 'KeyS') {
		stopwatch.stop();
	}
	if (event.code == 'KeyR') {
		stopwatch.clean();
	}
	if (event.code == 'KeyC') {
		stopwatch.clear();
		stopwatch.print();
	}
});

var i=0, starting=0;
setInterval(function fetch(){
    if(1==1){
        $.post("server/fetch.php",function(data){
            var fetch = JSON.parse(data);
            console.log(fetch);
            if(fetch[1]==1){
                if(!starting){
                    var diff = Number(Date.now() + currentShift) - Number(fetch[0]);
                    var time = new Date(diff);
                    stopwatch.ini(time.getMinutes(), time.getSeconds(), Math.floor(time.getMilliseconds()/100));
                    stopwatch.start();
                    starting = 1;
                }
            }else if(fetch[1]==3){
                stopwatch.stop();
            }else{
                starting = 0;
                stopwatch.stop();
            }
        });
    }
},500);
</script>
</body>
</html>