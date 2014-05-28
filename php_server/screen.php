<?php

if (isset($_GET['token'])) {
  $token = $_GET['token'];
  $keys  = $_GET['key'];
}

if (isset($_GET['days'])) {
  $days = $_GET['days'];
} else {
  $days = 1;
}

foreach ($keys as &$key) {
  $key = "'" . $key . "'";
}

include('header.php');
?>
<meta http-equiv="refresh" content="60">
<style>
div .last_div {
  margin: 0px;
  padding: 5px;
  width: 160px;
  text-align: center;
  display:none;
}
.last_value {
  color: white;
  font-size: 30pt;
}
</style>
<div id="offsetDiv">
  <div style="text-align:center; margin-bottom: 50px;">
    <div id="cubes">
      <div id="last_div_temp" class="last_div" style="background: #910000">
        <span style="color: white; font-size: 10pt">Temp:<br></span>
        <span id="last_temp" class="last_value">loading last value...</span><br>
        <span id="last_temp_time" style="color: white; font-size: 10pt"></span>
      </div>
    </div>
  </div>
  <div id="dygraphs">
    <!-- populated by jQuery -->
  </div>
</div>

<script>

var token = "<? echo $token; ?>";
var keys  = [ <? echo implode(",", $keys); ?> ]; //)[ 's_humidity', 's_temp', 's_probe' ];

init();

function init() {
    var index;
    for (index = 0; index < keys.length; ++index) {
        $( "#dygraphs" ).append( '<div id="' + keys[index] + '-container" style="clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>' );
        new Dygraph(
            document.getElementById(keys[index] + "-container"),
            getData('/api/get?token=' + token + '&key=' + keys[index] + '&days=<? echo $days ?>'),
            {
                strokeWidth: 0.0,
                drawPoints: true,
                pointsize: 1,
                rollPeriod: 1,
                showRoller: true,
                ylabel: keys[index],
                labels: ['date', 'y'],
                colors: [ '#910000' ],
            }
        );
    };
};

setInterval(function() {
    getAllLast();
},50000);

function getLast(token, key) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/api/last?token=' + token + '&key=' + key,
          'dataType': "json",
          'success': function (data) {
              if (isNaN(data['last'])) {
                  $('#last_' + key).html(data['last'][1] + unit);
              } else {
                  $('#last_' + key).html(Number(data['last'][1]).toFixed(dec) + unit);
              }
              $('#last_' + key + '_time').html(((new Date() - new Date(data['last'][0]))/1000/60).toFixed(0) + " min ago");
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
