<?php
require('config.php');
?>
<!DOCTYPE html>
<html>
<?php
$since = time() - (3600*24*3); //last three days
$sql = "SELECT * FROM data WHERE `key`='temp' AND `timestamp` >= $since ORDER BY `id` ASC";
$result = mysqli_query($con,$sql);
$label_count = 0;
$time_diff = 0;
$last_time = 0;

while ( $row = mysqli_fetch_array($result, MYSQL_ASSOC) ) {
    $dataset[] = $row;
};

$i = 0;
$avg = 40;
foreach ($dataset as $d) {
    $arr['val'][]       = $d['val'];
    $arr['timestamp'][] = $d['timestamp'];
    if (count($arr['val']) >= $avg) {
        $nds[$i]['val']       = array_sum($arr['val']) / $avg;
        $nds[$i]['timestamp'] = round(array_sum($arr['timestamp']) / $avg);
        unset($arr);
        $i++;
    }
};

foreach ($nds as $row) {
    $label_count++;
    $graph_data_array[] = round($row['val'],2);
    if (($label_count % 3) == 0) {
        $label = '"'.date("j. M H:i", $row['timestamp']).'"';
        $label_count = 0;
    } else {
        $label = '""';
    }
    $graph_labels_array[] = $label;
};
mysqli_close($con);

$graph_labels = implode(',', $graph_labels_array);
$graph_data   = implode(',', $graph_data_array);
?>
<head>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//www.chartjs.org/docs/Chart.js"></script>
</head>
<body>
<div class="container">
<h2><small>Config</small></h2>
Timezone: <strong><?php echo date_default_timezone_get(); ?></strong>
<br>
Location: <strong><?php echo $app_location ?></strong>
<h2>Current wind speed</h2>
<p class="lead">
<span id="current_wind">
loading...
</span>
</p>

<h2>Current temp</h2>
<p class="lead">
<span id="current_temp">
loading...
</span>
</p>

<h3>Historical temp</h3>
<canvas id="wind_canvas" data-type="Line" width="900" height="300" style="width: 600px; height: 400px;"></canvas>

<script>
var data = {
    labels : [ <? echo $graph_labels ?> ],
    datasets : [
        {
            fillColor : "rgba(255,100,100,0.7)",
            strokeColor : "rgba(255,100,100,1)",
            pointColor : "rgba(220,220,220,1)",
            pointStrokeColor : "rgba(255,100,100,1)",
            data : [ <? echo $graph_data ?> ]
        },
    ]
}

var options = {
    pointDotRadius : 3,
    datasetStrokeWidth: 3,
    animationSteps : 300,
}

var ctx = $("#wind_canvas").get(0).getContext("2d");
new Chart(ctx).Line(data,options);

$(function() {
    windRefresh();
    tempRefresh();
});

function windRefresh() {
    setTimeout(windRefresh,6000);
    $.get('current_wind.php', function(data) {
        $('#current_wind').html(data);
    });
}

function tempRefresh() {
    setTimeout(tempRefresh,6000);
    $.get('current_temp.php', function(data) {
        $('#current_temp').html(data);
    });
}
</script>
</div>
</body>
</html>
