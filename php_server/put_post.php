<?php
require('config.php');
require('db.php');

syslog(LOG_INFO, "put_post call");

$json = file_get_contents('php://input');

syslog(LOG_INFO, "Incomming data: ". $json);

$json = preg_replace('/\'/', '"', $json);

$d = json_decode($json, true);

//if (!isset($d['token'])) {
//    //die("token not set\n");
//    header("HTTP/1.1 200 OK");
//}

//if (!isset($d['data'][0]['timestamp'])) {
//    $d['data'][0]['timestamp'] = time();
//}

foreach ($d['data'] as $i) {
    if (!$device_id = get_device_id($con, 'token', $i['serial'])) {
        header("X-cube_cx: Device with that serial not found!");
        header('HTTP/1.1 400');
        return;
    }
    if ($i['key'] == 1) {
        $i['key'] = 'temp';
        $i['val'] = $i['val'] / 100;
    };
    if (($i['val'] >= 80) OR ($i['val'] < 0)) {
        header("X-cube_cx: Wrong value - either too high, or too low!");
        header('HTTP/1.1 400');
        return;
    }
    $sql = "INSERT INTO data (`device_id`, `key`, `val`, `time`) VALUES ('"
        .mysqli_real_escape_string($con,$device_id)."', '"
        .mysqli_real_escape_string($con,$i['key'])."', '"
        .mysqli_real_escape_string($con,$i['val'])."', "
        .mysqli_real_escape_string($con,$i['timestamp']).")";

    //syslog(LOG_INFO, $sql);
    mysqli_query($con,$sql);
}

mysqli_close($con);

?>
