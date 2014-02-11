<?php
include('header.php');
?>
<style>
div .last_div {
  margin: 0px;
  padding: 5px;
  width: 200px;
  text-align: center;
  display:inline-block;
}
.last_value {
  color: white;
  font-size: 35pt;
}
</style>
<div id="offsetDiv">
  <div style="text-align:center;">
    <div class="last_div" style="background: #910000">
      <span style="color: white; font-size: 10pt">Temp:<br></span>
      <span id="last_temp" class="last_value">loading last value...</span><br>
      <span id="last_temp_time" style="color: white; font-size: 10pt"></span>
    </div><div class="last_div" style="background: #77a1e5">
      <span style="color: white; font-size: 10pt">Rel. humidity:<br></span>
      <span id="last_humidity" class="last_value">loading last value...</span><br>
      <span id="last_humidity_time" style="color: white; font-size: 10pt"></span>
    </div><div class="last_div" style="background: #8bbc21">
      <span style="color: white; font-size: 10pt">Light:<br></span>
      <span id="last_light" class="last_value">loading last value...</span><br>
      <span id="last_light_time" style="color: white; font-size: 10pt"></span>
    </div><div class="last_div" style="background: #4572A7">
      <span style="color: white; font-size: 10pt">Wind speed:<br></span>
      <span id="last_wind_speed" class="last_value">loading last value...</span><br>
      <span id="last_wind_speed_time" style="color: white; font-size: 10pt"></span>
    </div><div class="last_div" style="background: #f28f43">
      <span style="color: white; font-size: 10pt">Wind direction:<br></span>
      <span id="last_wind_direction" class="last_value">loading last value...</span><br>
      <span id="last_wind_direction_time" style="color: white; font-size: 10pt"></span>
    </div>
  </div>
  <div id="temp-container" style="clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="humidity-container" style="min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="light-container" style="min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="wind_speed-container" style="min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="wind_direction-container" style="min-width: 600px; height: 150px; margin: 0 auto"></div>
</div>

<script>
setInterval(function() {
  getAllLast();
},50000);

function getLast(token, key, unit, dec) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': 'get.php?last=1&token=' + token + '&key=' + key,
          'dataType': "json",
          'success': function (data) {
              $('#last_' + key).html(Number(data[1]).toFixed(dec) + unit);
              $('#last_' + key + '_time').html(((new Date() - new Date(data[0]))/1000/60).toFixed(0) + " min ago");
          }
    });
  })();
}

function getAllLast() {
  getLast('<? echo $token ?>','temp','&deg;C',1);
  getLast('<? echo $token ?>','humidity','%',1);
  getLast('<? echo $token ?>','light','%',0);
  getLast('<? echo $token ?>','wind_speed','m/s',1);
  getLast('<? echo $token ?>','wind_direction','&deg;',0);
};
getAllLast();

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
    getData('get.php?token=<? echo $token ?>&key=temp'),
    {
        strokeWidth: 1,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Temperature (C)',
        labels: ['date', 'y'],
        colors: [ '#910000' ],
//        dateWindow: [ new Date() - ( 1000 * 60 * 60 * 7 ), new Date() ],
    }
);
new Dygraph(
    document.getElementById("humidity-container"),
    getData('get.php?token=<? echo $token ?>&key=humidity'),
    {
        strokeWidth: 1,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Humidity (rel.) (%)',
        valueRange: [ 0, null ],
        labels: ['date', 'y'],
        colors: [ '#77a1e5' ],
    }
);
new Dygraph(
    document.getElementById("light-container"),
    getData('get.php?token=<? echo $token ?>&key=light'),
    {
        strokeWidth: 1,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Light (%)',
        valueRange: [ 0, 100 ],
        labels: ['date', 'y'],
        colors: [ '#8bbc21' ],
    }
);
new Dygraph(
    document.getElementById("wind_speed-container"),
    getData('get.php?token=<? echo $token ?>&key=wind_speed'),
    {
        strokeWidth: 0.0,
        drawPoints: true,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Wind speed (m/s)',
        valueRange: [ 0, null ],
        labels: ['date', 'y'],
        colors: [ '#4572A7' ],
    }
);
new Dygraph(
    document.getElementById("wind_direction-container"),
    getData('get.php?token=<? echo $token ?>&key=wind_direction'),
    {
        strokeWidth: 0.0,
        drawPoints: true,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Wind direction (&deg;)',
        valueRange: [ 0, 360 ],
        labels: ['date', 'y'],
        colors: [ '#f28f43' ],
    }
);
</script>

<?php
require('footer.php');
?>
