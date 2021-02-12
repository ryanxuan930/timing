var currentLatency=0, currentShift=0;
var timeSerial = new Array();
var shiftSerial = new Array();
var latencyArray = new Array();
var shiftArray = new Array();
function timeSync(){
    var initialTime = Date.now();
    $.post("server/time_sync.php",function(data){
        var sync = JSON.parse(data);
        var currentTime = Date.now();
        var latency = (Number(currentTime) - Number(initialTime)) - (Number(sync.timeSent) - Number(sync.timeGet));
        var timeShift = ((Number(sync.timeGet)-Number(initialTime)) + (Number(sync.timeSent)-Number(currentTime)))/2;
        console.log(initialTime + "/" + sync.timeGet + "/" + sync.timeSent + "/" + currentTime + "/" + latency);
        timeSerial.push(latency);
        shiftSerial.push(timeShift);
    });
}
var i=0;
setInterval(function(){
    if(!currentLatency && !currentShift){
        if(i%2){
            $("#latency").html("計算中");
            $("#shift").html("計算中");
        }else{
            $("#latency").html("計算中...");
            $("#shift").html("計算中...");
        }
    }
    if(i==9){
        timeSync();
        i=0;
        var sum;
        var arrSort = timeSerial.sort();
        var median = (arrSort[4] + arrSort[3]) / 2
        latencyArray.push(median);
        if(latencyArray.length==3){
            console.log(latencyArray);
            sum = latencyArray.reduce((sum, val) => (sum += val));
            currentLatency = sum/3;
            $("#latency").html(currentLatency+" ms");
            latencyArray=[];
        }
        arrSort = shiftSerial.sort();
        median = (arrSort[4] + arrSort[3]) / 2
        shiftArray.push(median);
        if(shiftArray.length==3){
            sum = shiftArray.reduce((sum, val) => (sum += val));
            currentShift = sum/3;
            $("#shift").html(currentShift+" ms");
            shiftArray=[];
        }
        timeSerial = [];
        shiftSerial = [];
    }else{
        timeSync();
        i++;
    }
},1000);