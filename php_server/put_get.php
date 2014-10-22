<?php
require('config.php');
require('db.php');

syslog(LOG_INFO, "incomming data: ". $_GET);

$i['serial'] = $_GET['serial'];
$i['key']    = $_GET['key'];
$i['unit']   = $_GET['unit'];
$i['val']    = floatval($_GET['val']);
$i['time']   = intval(isset($_GET['time']) ? $_GET['time'] : time());

if (!$device_id = get_device_id($con, 'serial', $i['serial'])) {
    header("X-cube_cx: Device with that serial not found!");
    header('HTTP/1.1 400');
    return;
}

$sql = "INSERT INTO data (`device_id`, `key`, `val`, `unit`, `time`) VALUES ('"
    .mysqli_real_escape_string($con,$device_id)."', '"
    .mysqli_real_escape_string($con,$i['key'])."', "
    .mysqli_real_escape_string($con,$i['val']).", '"
    .mysqli_real_escape_string($con,$i['unit'])."', "
    .mysqli_real_escape_string($con,$i['time']).")";


echo("sql insert: " . $sql);

mysqli_query($con,$sql);

mysqli_close($con);
?>
