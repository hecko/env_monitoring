<?php
require('config.php');
openlog("windlog", LOG_PID | LOG_PERROR, LOG_LOCAL0);

if ($_SERVER['REMOTE_ADDR'] != '86.179.123.68') {
  echo "dragons here";
  syslog(LOG_INFO, "unauth: ". $_SERVER['REMOTE_ADDR']);
  die;
}


$json = file_get_contents('php://input');
$data = json_decode($json, true);
syslog(LOG_INFO, "this is an input: ". $json);

$sql = "INSERT INTO data (`serial`, `key`, `val`, `timestamp`) VALUES ('"
        .$data['serial']."', '"
        .$data['key']."', '"
        .$data['val']."', "
        .$data['time'].")";

syslog(LOG_INFO, $sql);

mysqli_query($con,$sql);

mysqli_close($con);
?>
