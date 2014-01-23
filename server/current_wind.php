<?php
require('config.php');

$sql = "SELECT * FROM data WHERE `key`='wind_freq' ORDER BY `id` DESC LIMIT 1";

$result = mysqli_query($con,$sql);

$row = mysqli_fetch_array($result, MYSQL_ASSOC);
$ago = time() - $row['timestamp'];

$hz  = $row['val'];
$kmh = $hz * 1.6 * 2.5;
echo round($kmh,2) . 'km/h <small>(' . round($hz,2) . 'Hz) @ ' . date("Y/m/d h:iA", $row['timestamp']) . '</small>';
if ( $ago >= 3600 ) {
    echo "<br><small>Old data; please check the anemometer attached to ".
         "the client with serial number ".$row['serial']."!</small><br>";
}

mysqli_close($con);
?>
