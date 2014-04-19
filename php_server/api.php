<?php
header('Content-type: application/json');

require('config.php');
require('db.php');

$supported = array('list_keys', 'help');

if ($_GET['cmd']) {
    $cmd = 'help';
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $cmd = $_GET['cmd'];
    } elseif ($_GET['cmd'] == 'help') {
        $token = 'help';
    }
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
    $sql = "SELECT DISTINCT `key` FROM `data` WHERE `token`='$token'";
    mysqli_real_escape_string($con,$sql);
    if (!$result = mysqli_query($con,$sql)) {
        $data['db_error'] = mysqli_error($con);
        flush_now($data);
    };

    $data['token'] = $token;

    while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
        $data['items']['list'] = $row['key'];
    }

    mysqli_close($con);
}

$data['timestamp'] = time();
flush_now($data);

function flush_now($data) {
    echo json_encode($data);
    flush();
    die;
}

?>
