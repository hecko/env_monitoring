<?php
header('Content-type: application/json');
require('config.php');
require('db.php');

if (isset($_GET['last'])) {
    $last  = $_GET['last'];
}
$token = $_GET['token'];
$key   = $_GET['key'];
$since = time() - (3600*24*1); //last X days

$sql = "SELECT * FROM data WHERE `token`='$token' AND `key`='$key' AND `time` >= $since ORDER BY `id` ASC";
$result = mysqli_query($con,$sql);

while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
    $dataset[] = array(intval($row['time'] * 1000), $row['val'] );
};

if (isset($last)) {
  echo json_encode(array_pop($dataset));
} else {
  echo json_encode($dataset);
}

?>
