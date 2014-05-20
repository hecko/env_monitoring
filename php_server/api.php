<?php
header('Content-type: application/json');

require('config.php');
require('db.php');

$supported = array('get', 'last', 'list_keys', 'list_sensors', 'help');

if ($_GET['cmd']) {
    if (isset($_GET['alias'])) {
        $data['alias'] = $_GET['alias'];
    }
    if (isset($_GET['major'])) {
        $_GET['token'] = $_GET['major'];
    }
    if (isset($_GET['token'])) {
        $data['token']     = $_GET['token'];
        $data['device_id'] = get_device_id($con, 'token', $data['token']);
    }
    $data['cmd'] = $_GET['cmd'];
} else {
    $data['error'] = "command has to be defined";
    echo json_encode($data);
    return;
}

if (!in_array($data['cmd'], $supported)) {
    $data['error'] = "command -$cmd- not supported";
}

if ($data['cmd'] == 'help') {
    $data['supported_cmds'] = $supported;
}

if ($data['cmd'] == 'list_keys') {
    if (isset($data['token'])) {
        $sql = "SELECT DISTINCT `key` FROM `data` WHERE `device_id`='" . mysqli_real_escape_string($con, $data['device_id']) . "'";
        if (!$result = mysqli_query($con,$sql)) {
            $data['db_error'] = mysqli_error($con);
            flush_now($data);
        };

        while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
            $data['keys'][] = $row['key'];
        }
    }
    mysqli_close($con);
}

if ($data['cmd'] == 'list_sensors') {
    $sql = "SELECT `token` FROM `devices` WHERE `user_id` IN (".
           "SELECT `id`    FROM `users`   WHERE `alias`='".$data['alias']."')";
//    $data['x'][] = $sql;
    if (!$result = mysqli_query($con,$sql)) {
        $data['db_error'] = mysqli_error($con);
        flush_now($data);
    };

    while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
        $data['sensor_tokens'][] = $row['token'];
    }
    mysqli_close($con);
}

if ($data['cmd'] == 'last') {
    $data['key'] = $_GET['key'];
    $sql = "SELECT * FROM data WHERE `device_id`='" . $data['device_id'] . "' AND `key`='" . $data['key'] . "' ORDER BY `id` DESC LIMIT 1";
    $result = mysqli_query($con,$sql);

    while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
        if (is_numeric($row['val'])) {
            $val = floatval($row['val']);
        } else {
            $val = $row['val'];
        }
        $data['last'] = array(intval($row['time'] * 1000), $val );
        $data['unit'] = $row['unit'];
    };

    $data['device_name'] = get_device_name($con, 'id', $data['device_id']);
};

if ($data['cmd'] == 'get') {
    $key = $_GET['key'];
    if (isset($_GET['days'])) {
        $last_days = $_GET['days'];
    } else {
        $last_days = 3;
    }
    $data['since'] = time() - (3600*24*$last_days); //last X days

    $sql = "SELECT * FROM data WHERE `device_id`='" . $data['device_id'] . "' AND `key`='$key' AND `time` >= " . $data['since'] . " ORDER BY `id` ASC";
    $result = mysqli_query($con,$sql);

    while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
        if (is_numeric($row['val'])) {
            $val = floatval($row['val']);
        } else {
            $val = $row['val'];
        }
        $data['set'][] = array(intval($row['time'] * 1000), $val );
    };
};

flush_now($data);

function flush_now($data) {
    unset($data['device_id']);
    $data['timestamp'] = time();
    header("X-Info: :)", false);
    echo json_encode($data);
    flush();
    die;
}

?>
