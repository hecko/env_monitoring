<?php
require('config.php');
require('db.php');
openlog("weather_log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

$json = file_get_contents('php://input');
syslog(LOG_INFO, "incomming data: ". $json);
$d = json_decode($json, true);

$sql = "INSERT INTO data (`token`, `key`, `val`, `time`) VALUES ('"
    .$d['token']."', '"
    .$d['data'][0]['key']."', '"
    .$d['data'][0]['val']."', "
    .$d['data'][0]['timestamp'].")";

syslog(LOG_INFO, "sql insert: " . $sql);

if (!mysqli_query($con,$sql)) {
  header("X-Status: Problem inserting into database!", false);
} else {
  header("X-Status: OK inserting into DB!", false);
}
header("X-SQL: $sql");

mysqli_close($con);

?>
