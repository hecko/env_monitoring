<?php
require('config.php');
require('db.php');
openlog("weather_log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

syslog(LOG_INFO, "incomming data: ". $_GET);

$d['token']           = $_GET['token'];
$d['data'][0]['key']  = $_GET['key'];
$d['data'][0]['val']  = floatval($_GET['val']);
$d['data'][0]['time'] = intval(isset($_GET['time']) ? $_GET['time'] : time());

$sql = "INSERT INTO data (`token`, `key`, `val`, `time`) VALUES ('"
    .$d['token']."', '"
    .$d['data'][0]['key']."', "
    .$d['data'][0]['val'].", "
    .$d['data'][0]['time'].")";

syslog(LOG_INFO, "sql insert: " . $sql);

mysqli_query($con,$sql);

mysqli_close($con);
?>
