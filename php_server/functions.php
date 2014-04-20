<?php

function get_device_id($con, $field, $match) {
    $sql = "SELECT * FROM `devices` WHERE `$field`='$match' LIMIT 1";
    if (!$result = mysqli_query($con, $sql)) {
        syslog(LOG_ERR, "DB error: " . mysqli_error($con));
        die("db issue");
    }
    $row = mysqli_fetch_assoc($result);
    if (!isset($row['id'])) {
        die("no such token");
    }
    return $row['id'];
}

?>
