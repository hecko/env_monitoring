<?php
require('config.php');
require('db.php');

$d['token']           = $_GET['uuid'] . ":" . $_GET["major"];
$d['data'][0]['key']  = "temp";
$d['data'][0]['val']  = floatval($_GET['@temp']);
$d['data'][0]['time'] = intval(isset($_GET['time']) ? $_GET['time'] : time());

$sql = "INSERT INTO data (`token`, `key`, `val`, `time`) VALUES ('"
    .$d['token']."', '"
    .$d['data'][0]['key']."', "
    .$d['data'][0]['val'].", "
    .$d['data'][0]['time'].")";

mysqli_query($con,$sql);

mysqli_close($con);

print '<a href="/get/' . $d['token'] . '/' . $d['data'][0]['key'] . '/">last data</a>';
?>
