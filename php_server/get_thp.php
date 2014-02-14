<?php
// get temperature, humidy  in one array
header('Content-type: application/json');
require('config.php');
require('db.php');

if (isset($_GET['last'])) {
    $last  = $_GET['last'];
}
$token = $_GET['token'];
$since = time() - (3600*24*3); //last X days

$sql = "SELECT * FROM data WHERE `token`='$token' AND `key` IN ('temp', 'humidity') AND `time` >= $since ORDER BY `id` ASC";
$result = mysqli_query($con,$sql);

while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
    if ($row['key'] == 'temp') {
        $temp        = floatval($row['val']);
        $humidity    = null;
    } elseif ($row['key'] == 'humidity') {
        $temp        = null;
        $humidity    = floatval($row['val']);
    } else {
        next;
    }
    $data[] = array(intval($row['time'] * 1000), $temp, $humidity);
};

echo json_encode($data);

?>
