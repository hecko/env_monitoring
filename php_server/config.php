<?php
include("functions.php");

openlog("temperme_log", LOG_PID | LOG_PERROR, LOG_LOCAL0);

date_default_timezone_set("Europe/London");
$app_location = 'Edinburgh';
$db_user      = 'iboredco_mac';
$db_pass      = 'testing123';
$db_db        = 'iboredco_weather';

$has_gps      = 0; // 0 - NO, 1 - YES
?>
