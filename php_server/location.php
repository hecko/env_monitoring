<?php
$location = $_GET['location'];
$l        = explode(',', $location);
$latlng   = $l[0].','.$l[1];
$error    = $l[2];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Location</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
function initialize() {
  var myLatlng = new google.maps.LatLng(<? echo $latlng ?>);
  var mapOptions = {
    zoom: 11,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map
  });

  var circle = new google.maps.Circle({
      strokeColor: '#ff0000',
      strokeOpacity: 0.6,
      strokeWeight: 1,
      fillColor: '#ff0000',
      fillOpacity: 0.2,
      map: map,
      center: myLatlng,
      radius: <? echo $error ?>
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>


