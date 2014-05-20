<?php
if (isset($_GET['token'])) {
  $token = $_GET['token'];
  $key   = $_GET['key'];
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
      <span id="last_<? echo $key ?>" class="last_value">loading last value...</span><br>
      <span id="last_<? echo $key ?>_time" style="color: white; font-size: 10pt"></span>
    </div>
  </div>
  <div id="<? echo $key ?>-container" style="clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
</div>

<script>
setInterval(function() {
    getLast('<? echo $token ?>','<? echo $key ?>','&deg;C',1);
    g.updateOptions({
        file: getData('/api/get?token=<? echo $token ?>&key=<? echo $key ?>'),
    });
},50000);

getLast('<? echo $token ?>','<? echo $key ?>','&deg;C',1);

g = new Dygraph(
    document.getElementById("<? echo $key ?>-container"),
    getData('/api/get?token=<? echo $token ?>&key=<? echo $key ?>'),
    {
        strokeWidth: 0.0,
        drawPoints: true,
        pointsize: 1,
        rollPeriod: 10,
        showRoller: true,
        ylabel: 'Temp. (&deg;C)',
        labels: ['date', 'y'],
        colors: [ '#910000' ],
    }
);

function getLast(token, key, unit, dec) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/api/last?token=' + token + '&key=' + key,
          'dataType': "json",
          'success': function (data) {
              if (isNaN(data[1])) {
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
