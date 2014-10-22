<?php
require('config.php');
require('db.php');

$p = $_POST;

// print_r($p);

if (!$node_id = get_node_id($con, 'node_serial', $p['node_serial'])) {
    echo "No such node_serial " . $p['node_serial'];
    die;
}

$sql = "INSERT INTO data (`node_id`, `key`, `val`, `unit`, `timestamp`) VALUES ('"
    .mysqli_real_escape_string($con,$node_id)."', '"
    .mysqli_real_escape_string($con,$p['key'])."', "
    .mysqli_real_escape_string($con,$p['val']).", '"
    .mysqli_real_escape_string($con,$p['unit'])."', "
    .mysqli_real_escape_string($con,$p['timestamp']).")";

// echo $sql;

if (mysqli_query($con,$sql)) {
  echo "OK";
} else {
  echo mysql_error();
}
mysqli_close($con);

?>
