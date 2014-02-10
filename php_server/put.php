<?php
require('config.php');
require('db.php');
openlog("weather_log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

#if ($_SERVER['REMOTE_ADDR'] != '86.179.123.68') {
#  echo "dragons here";
#  syslog(LOG_INFO, "unauth: ". $_SERVER['REMOTE_ADDR']);
#  die;
#}

$json = file_get_contents('php://input');
syslog(LOG_INFO, "incomming data: ". $json);
$d = json_decode($json, true);

$sql = "INSERT INTO data (`token`, `key`, `val`, `time`) VALUES ('"
    .$d['token']."', '"
    .$d['data'][0]['key']."', '"
    .$d['data'][0]['val']."', "
    .$d['data'][0]['timestamp'].")";

syslog(LOG_INFO, "sql insert: " . $sql);

mysqli_query($con,$sql);

mysqli_close($con);
?>
