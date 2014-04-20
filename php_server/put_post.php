<?php
require('config.php');
require('db.php');

$json = file_get_contents('php://input');

syslog(LOG_INFO, "Incomming data: ". $json);

$json = preg_replace('/\'/', '"', $json);

$d = json_decode($json, true);

if (!isset($d['token'])) {
    die("token not set\n");
}

if (!isset($d['data'][0]['timestamp'])) {
    $d['data'][0]['timestamp'] = time();
}

if (!$device_id = get_device_id($con, 'token', $d['token'])) {
    $errors .= " Device ID not found. ";
}

$sql = "INSERT INTO data (`device_id`, `key`, `val`, `time`) VALUES ('"
    .mysqli_real_escape_string($con,$device_id)."', '"
    .mysqli_real_escape_string($con,$d['data'][0]['key'])."', '"
    .mysqli_real_escape_string($con,$d['data'][0]['val'])."', "
    .mysqli_real_escape_string($con,$d['data'][0]['timestamp']).")";

if (!mysqli_query($con,$sql)) {
  header("X-Status: Problem inserting into database!", false);
} else {
  header("X-Status: OK inserting into DB!", false);
}
header("X-SQL: $sql");
header("X-Errors: $errors");

mysqli_close($con);

?>
