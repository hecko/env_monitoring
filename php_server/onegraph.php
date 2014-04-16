<?php
if (isset($_GET['token'])) {
  $token = $_GET['token'];
  $key   = $_GET['key'];
} else {
  print "Please define token in 'token' GET param!";
}

$title = "$token: $key";

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
  <div id="<? echo $key ?>-container" style="clear: both; min-width: 600px; height: 150px; margin: 0 auto"></div>
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

function getAllLast() {
  getLast('<? echo $token ?>','<? echo $key ?>','&deg;C',1);
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

function getTHData(query) {
  var json = [];
  $.ajax({
          'async': false,
          'global': false,
          'url': query,
          'dataType': "json",
          'success': function (data) {
              json = $.map(data, function(n) {
                  return [ [ new Date(n[0]), n[1], n[2] ] ];
              });
          }
  });
  return json;
};
</script>

<script type="text/javascript">

new Dygraph(
    document.getElementById("<? echo $key ?>-container"),
    getData('/get/<? echo $token ?>/<? echo $key ?>?days=100'),
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
