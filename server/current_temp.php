<?php
require('config.php');

$sql = "SELECT * FROM data WHERE `key`='temp' ORDER BY `id` DESC LIMIT 1";

$result = mysqli_query($con,$sql);

$row = mysqli_fetch_array($result, MYSQL_ASSOC);
$ago = time() - $row['timestamp'];

if ( $ago >= 3600 * 10 ) {
    echo "<small>Data not available!</small><br>";
} else {
    echo round($row['val'],2) . '&deg;C<small> @ ' . date("Y/m/d h:iA", $row['timestamp']) . '</small>';
    echo '<br><small><small class="text-muted">serial: '.$row['serial'].'</small></small>';
    // echo "<br><small><small>".$ago."s ago</em></small></small>";
}

mysqli_close($con);
?>
