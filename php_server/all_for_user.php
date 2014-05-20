<?php
if (isset($_GET['alias'])) {
  $alias = $_GET['alias'];
}

include('header.php');
?>
<meta http-equiv="refresh" content="600">
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
        <div id="last_data" style="text-align: right; background: #910000; color:white">
        </div>
    </div>
  </div>
</div>

<script>
var alias = "<? echo $alias; ?>";

setInterval(function() {
  getTokens();
},50000);

getTokens();

function getTokens() {
  $('#last_data').html("refreshing...");
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/api/list_sensors?alias=' + alias,
          'dataType': "json",
          'success': function (data) {
              $('#last_data').html("");
              var index;
              for (index = 0; index < data.sensor_tokens.length; ++index) {
                  getLast(data.sensor_tokens[index], 'temp');
                  $('#last_data').css('display', 'inline-block');
              }
          }
    });
  })();
}

function getLast(token, key) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/api/last?token=' + token + '&key=' + key,
          'dataType': "json",
          'success': function (data) {
              if (typeof data.last !== 'undefined') {
                  $('#last_data').append(data.device_name + ' ');
                  $('#last_data').append(Number(data.last[1]).toFixed(2) + data.unit + " ");
                  $('#last_data').append(((new Date() - new Date(data.last[0]))/1000/60).toFixed(0) + " min ago ");
                  $('#last_data').append('<a href=/screen/' + data.token + ">graph</a><br>");
              }
          }
    });
  })();
}
</script>

<?php
require('footer.php');
?>
