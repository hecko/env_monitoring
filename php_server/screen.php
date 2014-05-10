<?php
if (isset($_GET['token'])) {
  $token = $_GET['token'];
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
    <? if ($has_gps) { ?>
    <div style="margin-bottom: 10px">Last location: <a id="last_location_link" href="#">
        <span id="last_location">loading location data...</span></a> (<span id="last_location_time"></span>)
    </div>
    <div style="margin-bottom: 40px">Last <?php echo $token ?> beat <span id="last_beat_time"></span></div>
    <? }; ?>
    <div id="cubes">
    <div id="last_div_temp" class="last_div" style="background: #910000">
      <span style="color: white; font-size: 10pt">Temp:<br></span>
      <span id="last_temp" class="last_value">loading last value...</span><br>
      <span id="last_temp_time" style="color: white; font-size: 10pt"></span>
    </div><div id="last_div_humidity" class="last_div" style="background: #2F9DAD">
      <span style="color: white; font-size: 10pt">Rel. humidity:<br></span>
      <span id="last_humidity" class="last_value">loading last value...</span><br>
      <span id="last_humidity_time" style="color: white; font-size: 10pt"></span>
    </div><div id="last_div_pressure" class="last_div" style="background: #8bbc21">
      <span style="color: white; font-size: 10pt">Pressure:<br></span>
      <span id="last_pressure" class="last_value">loading last value...</span><br>
      <span id="last_pressure_time" style="color: white; font-size: 10pt"></span>
    </div><div id="last_div_wind_speed" class="last_div" style="background: #4572A7">
      <span style="color: white; font-size: 10pt">Wind speed:<br></span>
      <span id="last_wind_speed" class="last_value">loading last value...</span><br>
      <span id="last_wind_speed_time" style="color: white; font-size: 10pt"></span>
    </div><div id="last_div_wind_direction" class="last_div" style="background: #f28f43">
      <span style="color: white; font-size: 10pt">Wind direction:<br></span>
      <span id="last_wind_direction" class="last_value">loading last value...</span><br>
      <span id="last_wind_direction_time" style="color: white; font-size: 10pt"></span>
    </div><div id="last_div_light" class="last_div" style="background: #F035AE">
      <span style="color: white; font-size: 10pt">Light:<br></span>
      <span id="last_light" class="last_value">loading last value...</span><br>
      <span id="last_light_time" style="color: white; font-size: 10pt"></span>
    </div>
    </div>
  </div>
  <div id="temp-container" style="display: none; clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="humidity-container" style="display: none; clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="pressure-container" style="display: none; min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="wind_speed-container" style="display: none; min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="wind_direction-container" style="display: none; min-width: 600px; height: 150px; margin: 0 auto"></div>
  <div id="light-container" style="display: none; min-width: 600px; height: 150px; margin: 0 auto"></div>
</div>

<script>

var token = "<? echo $token; ?>";

setInterval(function() {
  getAllLast();
},50000);

getKeys();

function getKeys() {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/api/list_keys?token=' + token,
          'dataType': "json",
          'success': function (data) {
              var index;
              for (index = 0; index < data['keys'].length; ++index) {
                  getLast(token, data['keys'][index], '', 1);
                  $('#last_div_' + data['keys'][index]).css('display', 'inline-block');
                  $('#' + data['keys'][index] + "-container").css('display', '');
              }
          }
    });
  })();
}

function getLast(token, key, unit, dec) {
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

function getLastLocation(token) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/get/' + token + '/location',
          'dataType': "json",
          'success': function (data) {
              $('#last_location').html(data[1]);
              $('#last_location_time').html(((new Date() - new Date(data[0]))/1000/60).toFixed(0) + " min ago");
              $('#last_location_link').attr('href', function(i,a) { return 'location.php?location=' + data[1]; });
          }
    });
  })();
}

function getLastBeat(token) {
  var json = (function () {
    var json = null;
    $.ajax({
          'async': false,
          'global': false,
          'url': '/get/' + token + '/beat/?last=1',
          'dataType': "json",
          'success': function (data) {
              // $('#last_beat').html(data[1]);
              $('#last_beat_time').html(((new Date() - new Date(data[0]))/1000/60).toFixed(0) + " min ago");
          }
    });
  })();
}

//getLastLocation(token);
//getLastBeat(token);

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

new Dygraph(
    document.getElementById("temp-container"),
    getData('/api/get?token=' + token + '&key=temp'),
    {
        strokeWidth: 0.0,
        drawPoints: true,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Temp. (&deg;C)',
        labels: ['date', 'y'],
        colors: [ '#910000' ],
    }
);

new Dygraph(
    document.getElementById("humidity-container"),
    getData('/api/get?token=' + token + '&key=humidity'),
    {
        strokeWidth: 0.7,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 5,
        showRoller: true,
        ylabel: 'Humidity (%)',
        labels: ['date', 'y'],
        colors: [ '#2F9DAD' ],
    }
);

new Dygraph(
    document.getElementById("pressure-container"),
    getData('/api/get?token=' + token + '&key=pressure'),
    {
        strokeWidth: 0.7,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Pressure (hPa)',
        labels: ['date', 'y'],
        colors: [ '#8bbc21' ],
    }
);
new Dygraph(
    document.getElementById("light-container"),
    getData('/api/get?token=' + token + '&key=light'),
    {
        strokeWidth: 0.7,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Light (%)',
        valueRange: [ 0, 100 ],
        labels: ['date', 'y'],
        colors: [ '#F035AE' ],
    }
);
new Dygraph(
    document.getElementById("wind_speed-container"),
    getData('/api/get?token=' + token + '&key=wind_speed'),
    {
        strokeWidth: 0.7,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 5,
        showRoller: true,
        ylabel: 'Wind speed (m/s)',
        valueRange: [ 0, null ],
        labels: ['date', 'y'],
        colors: [ '#4572A7' ],
    }
);
new Dygraph(
    document.getElementById("wind_direction-container"),
    getData('/api/get?token=' + token + '&key=wind_direction'),
    {
        strokeWidth: 0.7,
        drawPoints: false,
        pointsize: 1,
        rollPeriod: 1,
        showRoller: true,
        ylabel: 'Wind direction (&deg;)',
        valueRange: [ 0, 1100 ],
        labels: ['date', 'y'],
        colors: [ '#f28f43' ],
    }
);
</script>

<?php
require('footer.php');
?>
