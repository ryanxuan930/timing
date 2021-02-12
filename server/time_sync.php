<?php
$time1 = microtime(true)*1000;
class timestamp{
    public $timeGet = 0;
    public $timeSent = 0;
}
$obj = new timestamp;
$obj->timeGet = $time1;
$obj->timeSent = microtime(true)*1000;
$json = json_encode($obj);
echo $json;
?>