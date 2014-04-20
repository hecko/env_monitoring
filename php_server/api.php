<?php
header('Content-type: application/json');

require('config.php');
require('db.php');

$supported = array('get', 'last', 'list_keys', 'help');

if ($_GET['cmd']) {
    $cmd = 'help';
    if (isset($_GET['token'])) {
        $cmd               = $_GET['cmd'];
        $data['token']     = $_GET['token'];
        $data['device_id'] = get_device_id($con, 'token', $data['token']);
        header("X-did: " . $data['device_id']);
    } elseif ($_GET['cmd'] == 'help') {
        $data['token'] = '_help';
    }
    $data['cmd'] = $cmd;
} else {
    $data['error'] = "both cmd and token has to be defined";
    echo json_encode($data);
    return;
}

if (!in_array($cmd, $supported)) {
    $data['error'] = "command -$cmd- not supported";
}

if ($cmd == 'help') {
    $data['supported_cmds'] = $supported;
}

if ($cmd == 'list_keys') {
    $sql = "SELECT DISTINCT `key` FROM `data` WHERE `device_id`='" . mysqli_real_escape_string($con, $data['device_id']) . "'";
    if (!$result = mysqli_query($con,$sql)) {
        $data['db_error'] = mysqli_error($con);
        flush_now($data);
    };

    while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
        $data['items']['list'] = $row['key'];
    }

    mysqli_close($con);
}

if ($cmd == 'last') {
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
    };
};

if ($cmd == 'get') {
    $key = $_GET['key'];
    if (isset($_GET['days'])) {
        $last_days = $_GET['days'];
    } else {
        $last_days = 8;
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
