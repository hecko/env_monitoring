<?php
header('Content-type: application/json');
require('config.php');
require('db.php');

if (isset($_GET['last'])) {
    $last  = $_GET['last'];
}
$token = $_GET['token'];
$key   = $_GET['key'];
if (isset($_GET['days'])) {
    $last_days = $_GET['days'];
} else {
    $last_days = 8;
}
$since = time() - (3600*24*$last_days); //last X days

$sql = "SELECT * FROM data WHERE `token`='$token' AND `key`='$key' AND `time` >= $since ORDER BY `id` ASC";
$result = mysqli_query($con,$sql);

while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
    if (is_numeric($row['val'])) {
        $val = floatval($row['val']);
    } else {
        $val = $row['val'];
    }
    $dataset[] = array(intval($row['time'] * 1000), $val );
};

if (isset($last)) {
  header("X-Info: All grand!", false);
  echo json_encode(array_pop($dataset));
} else {
  if (!isset($dataset)) {
    header("X-Info: Something went wrong!", false);
    header("X-SQL: $sql", false);
    echo "$sql";
  } else {
    header("X-Info: All grand!", false);
    echo json_encode($dataset);
  }
}

?>
