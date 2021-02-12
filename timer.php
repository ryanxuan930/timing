<!doctype html>
<html>
<head>
<meta name="description" content="Rikujo Timing System">
<meta name="author" content="Ryan Po-Hsuan Chang from Rikujo Labs">
<meta name="copyright" content="Ryan Po-Hsuan Chang All Rights Reserved">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<link rel="icon" href="https://sports.hs.ntnu.edu.tw/hsnua3/image/logo.ico" type="image/x-icon"/>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap" as="style" onload="this.rel=\'stylesheet\'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@500;700&display=swap" rel="stylesheet"></noscript>
<link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons" as="style" onload="this.rel=\'stylesheet\'">
<noscript><link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"></noscript>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<meta charset="UTF-8">
<style>
* {
  margin: 0;
  padding: 0;
}

html {
  background: #333;
  color: #bbb;
  font-family: Menlo;
}
	#title{
		position: fixed;
		font-family: 'Noto Sans TC', sans-serif;
		width: 100%;
		font-size: 24pt;
		text-align: center;
	}
.controls {
  position: fixed;
  text-align: center;
  top: 3em;
  width: 100%;
}

.button {
  color: #bbb;
  font-size: 4vw;
  margin: 0 0.5em;
  text-decoration: none;
}

.button:first-child {
    margin-left: 0;
}

.button:last-child {
    margin-right: 0;
}

.button:hover {
  color: white;
}

.stopwatch {
  font-size: 20vw;
  height: 100%;
  line-height: 100vh;
  text-align: center;
}

.results {
  border-color: lime;
  list-style: none;
  margin: 0;
  padding: 0;
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
}
	li{
		font-size: 18pt;
	}
</style>
<title>Rikujo Timing System</title>
</head>
<body>
    <div id="title">RIKUJO TIMING SYSTEM</div>
	<nav class="controls">
			<a href="#" class="button" onClick="stopwatch.start();">Start</a>
			<a href="#" class="button" onClick="stopwatch.lap();">Lap</a>
			<a href="#" class="button" onClick="stopwatch.stop();">Stop</a>
			<a href="#" class="button" onClick="stopwatch.clear();">Clear Laps</a>
			<a href="#" class="button" onClick="playSound();">Test</a>
	</nav>
    <div id="current"></div>
    <div id="get"></div>
	<div class="stopwatch"></div>
	<ul class="results"></ul>
	<h2 id="status"></h2>
<script>
class Stopwatch {
    constructor(display, results) {
        this.running = false;
        this.display = display;
        this.results = results;
        this.laps = [];
        this.reset();
        this.print(this.times);
    }
    
	ini(m,s,f){
		this.times = [ m, s, f ];
	}
    reset() {
        this.times = [ 0, 0, 0 ];
    }
    
    start() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
    }
    
    lap() {
        let times = this.times;
        let li = document.createElement('li');
        li.innerText = this.format(times);
        this.results.appendChild(li);
    }
    
    stop() {
        this.running = false;
        this.time = null;
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
        clearChildren(this.results);
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
	if (event.code == 'KeyS') {
		stopwatch.stop();
	}
	if (event.code == 'KeyR') {
		stopwatch.stop();
		stopwatch.reset();
		stopwatch.print();
	}
	if (event.code == 'KeyC') {
		stopwatch.clear();
		stopwatch.print();
	}
});
function playSound(){
	var go = document.getElementById("go");
	go.play();
}
setInterval(function(){
	$.post( "refresh.php", { query: 1}, function(data){
		if(data == 2){
			$.post( "current_time.php", { query: 1}, function(x){
                var input = JSON.parse(x);
                var current = new Date(parseInt(input.currentTime));
				var iniTime = new Date(parseInt(input.iniTime));
			stopwatch.ini(current.getMinutes()-iniTime.getMinutes(),current.getSeconds()-iniTime.getSeconds(),Math.floor((current.getMilliseconds()-iniTime.getMilliseconds())/100));
			stopwatch.start();
            $("#current").html("cur: " + current);
            $("#get").html("ini: " + iniTime);
			});
			
		}else if(data == 1){
			stopwatch.stop();
			stopwatch.reset();
			stopwatch.print();
		}else if(data == 3){
			var fstart = document.getElementById("false");
			fstart.play();
			stopwatch.stop();
		}else if(data == 4){
			stopwatch.stop();
			stopwatch.reset();
			stopwatch.print();
		}else if(data == 5){
			var test = document.getElementById("test");
			test.play();
		}
	})
}, 1000);
</script>
</body>
</html>