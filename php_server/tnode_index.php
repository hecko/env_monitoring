<?php
if (isset($_GET['token'])) {
  $token = $_GET['token'];
}

$title = "token: $token";

include('header.php');
?>
<meta http-equiv="refresh" content="600">
<style>
div .last_div {
  margin: 0px;
  padding: 5px;
  width: 160px;
  text-align: center;
  display:inline-block;
}
.last_value {
  color: white;
  font-size: 30pt;
}
</style>
<div id="offsetDiv">
  <div style="text-align:center; margin-bottom: 50px;">
    <div class="last_div" style="background: #910000">
      <span style="color: white; font-size: 10pt">Temp:<br></span>
      <span id="last_temp" class="last_value">loading last value...</span><br>
      <span id="last_temp_time" style="color: white; font-size: 10pt"></span>
    </div>
  </div>
  <div id="temp-container" style="clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
</div>

<script>
setInterval(function() {
    getLast('<? echo $token ?>','temp','&deg;C',1);
},50000);

getLast('<? echo $token ?>','temp','&deg;C',1);

function getLast(token, key, unit, dec) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/get/' + token + '/' + key + '/?last=1',
          'dataType': "json",
          'success': function (data) {
              if (isNaN(data[1])) {
                  $('#last_' + key).html(data[1]);
              } else {
                  $('#last_' + key).html(Number(data[1]).toFixed(dec) + unit);
              }
              $('#last_' + key + '_time').html(((new Date() - new Date(data[0]))/1000/60).toFixed(0) + " min ago");
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
              json = $.map(data, function(n) {
                  return [ [ new Date(n[0]), n[1] ] ];
              });
          }
  });
  return json;
};

</script>

<script type="text/javascript">

new Dygraph(
    document.getElementById("temp-container"),
    getData('/get/<? echo $token ?>/temp'),
    {
        strokeWidth: 0.7,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 10,
        showRoller: true,
        ylabel: 'Temp. (&deg;C)',
        labels: ['date', 'y'],
        colors: [ '#910000' ],
    }
);

</script>

<?php
require('footer.php');
?>
