<?php
require('config.php');
require('db.php');

$d['token']           = $_GET["major"];
$d['device_id']       = get_device_id($con, 'token', $d['token']);
$d['data'][0]['key']  = "temp";
$d['data'][0]['val']  = floatval($_GET['temp']);
$d['data'][0]['time'] = intval(isset($_GET['time']) ? $_GET['time'] : time());

$sql = "INSERT INTO data (`device_id`, `key`, `val`, `time`) VALUES ('"
    .$d['device_id']."', '"
    .$d['data'][0]['key']."', "
    .$d['data'][0]['val'].", "
    .$d['data'][0]['time'].")";

mysqli_query($con,$sql);

mysqli_close($con);

print '<a href="/get/' . $d['token'] . '/' . $d['data'][0]['key'] . '/">last data</a>';
?>
