<?php
if (isset($_GET['node_serial'], $_GET['key'])) {
  $node_serial = $_GET['node_serial'];
  $key   = $_GET['key'];
} else {
  print_r($_GET);
  die;
}

if (isset($_GET['days'])) {
  $days = $_GET['days'];
} else {
  $days = 1;
}

include('header.php');
?>
<meta http-equiv="refresh" content="600">
<div id="offsetDiv">
  <div style="text-align:center; margin-bottom: 50px;">
  <span id="node_name" style="font-size: 18pt">loading node name...</span><br><br>
    <div class="last_div" style="background: #910000">
      <span style="color: white; font-size: 10pt">Last <? echo $key; ?> value:<br></span>
      <span id="last_<? echo $key ?>" class="last_value">loading last value...</span><br>
      <span id="last_<? echo $key ?>_time" style="color: white; font-size: 10pt"></span><br>
      <span id="last_<? echo $key ?>_time_date" style="color: white; font-size: 9pt"></span>
    </div>
  </div>
  <div id="<? echo $key ?>-container" style="clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
</div>

<script>
setInterval(function() {
    getLast('<? echo $node_serial ?>','<? echo $key ?>','&deg;C',1);
    g.updateOptions({
        file: getData('/api/get?node_serial=<? echo $node_serial ?>&key=<? echo $key ?>&days=<? echo $days ?>'),
    });
},50000);

getLast('<? echo $node_serial ?>','<? echo $key ?>');

g = new Dygraph(
    document.getElementById("<? echo $key ?>-container"),
    getData('/api/get?node_serial=<? echo $node_serial ?>&key=<? echo $key ?>&days=<? echo $days ?>'),
    {
        strokeWidth: 0.0,
        drawPoints: true,
        pointsize: 1,
        rollPeriod: 100,
        showRoller: true,
        ylabel: 'Temp. (&deg;C)',
        labels: ['date', 'y'],
        colors: [ '#910000' ],
    }
);

function getLast(node_serial, key) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/api/last?node_serial=' + node_serial + '&key=' + key,
          'dataType': "json",
          'success': function (data) {
              $('#last_' + key).html(data['last'][1] + data['unit']);
              $('#last_' + key + '_time').html((new Date(data['last_ago_ms'])/1000/60).toFixed(0) + " minutes ago");
              $('#last_' + key + '_time_date').html(new Date(data['last'][0]));
              $('#node_name').html(data['node_name']);
          }
    });
  })();
}


function getData(query) {
  var json = [];
  $.ajax({
          'async': false,
          'global': false,
          'url': query,
          'dataType': "json",
          'success': function (data) {
              json = $.map(data['set'], function(n) {
                  return [ [ new Date(n[0]), n[1] ] ];
              });
          }
  });
  return json;
};
</script>

<?php
require('footer.php');
?>
