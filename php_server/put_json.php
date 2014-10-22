<?php
require('config.php');
require('db.php');

syslog(LOG_INFO, "put_post call");

$json = file_get_contents('php://input');

syslog(LOG_INFO, "Incomming data: ". $json);

$json = preg_replace('/\'/', '"', $json);

$d = json_decode($json, true);

foreach ($d['data'] as $i) {
    if (!$device_id = get_node_id($con, 'node_serial', $i['node_serial'])) {
        header("X-cube_cx: Node with that serial not found!");
        header('HTTP/1.1 400');
        return;
    }

    $sql = "INSERT INTO data (`device_id`, `key`, `val`, `unit`, `time`) VALUES ('"
        .mysqli_real_escape_string($con,$device_id)."', '"
        .mysqli_real_escape_string($con,$i['key'])."', "
        .mysqli_real_escape_string($con,$i['val']).", '"
        .mysqli_real_escape_string($con,$i['unit'])."', "
        .mysqli_real_escape_string($con,$i['timestamp']).")";

    syslog(LOG_INFO, $sql);
    mysqli_query($con,$sql);
}

mysqli_close($con);

?>
