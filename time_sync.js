var currentLatency=0, timeShift=0;
var timeSerial = new Array();
var latencyArray = new Array();
function timeSync(){
    var initialTime = Date.now();
    $.post("server/time_sync.php",function(data){
        var sync = JSON.parse(data);
        var currentTime = Date.now();
        var latency = (Number(currentTime) - Number(initialTime)) - (Number(sync.timeSent) - Number(sync.timeGet));
        var timeShift = ((Number(sync.timeGet)-Number(initialTime)) + (Number(sync.timeSent)-Number(currentTime)))/2;
        $("#shift").html(timeShift);
        timeSerial.push(latency);
    });
}
var i=0;
setInterval(function(){
    if(!currentLatency){
        $("#latency").html("時間同步中");
        $("#shift").html("");
    }
    if(i==9){
        timeSync();
        i=0;
        var arrSort = timeSerial.sort();
        var median = (arrSort[4] + arrSort[3]) / 2
        latencyArray.push(median);
        if(latencyArray.length==5){
            sum = latencyArray.reduce((sum, val) => (sum += val));
            currentLatency = sum/5;
            $("#latency").html(latency);
            latencyArray=[];
        }
        timeSerial = [];
    }else{
        timeSync();
        i++;
    }
},1000);