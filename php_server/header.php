<?php
require('config.php');
require('db.php');
?>
<!DOCTYPE html>
  <head>
    <meta charset="utf-8">
    <title><? echo $node_serial ?></title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
    <link href='/css/style.css' rel='stylesheet' type="text/css">
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="http://dygraphs.com/1.0.1/dygraph-combined.js"></script>
  </head>

<style>
body {
  overflow-y:scroll;
  margin: 0;
  padding: 0;
  font-family: 'Open Sans', sans-serif;
  font-weight: 300;
}

svg {
  overflow: hidden;
}

div {
  border: 0;
  margin: 0;
}

#offsetDiv {
  margin-left: 100px;
  margin-right: 100px;
  margin-top: 80px;
}

#test1 {
  margin: 0;
}

#test1 svg {
  height: 300px;
  width:  690px;
  stroke-opacity: 1;
}

</style>
<body>
<h2><? //echo get_device_name($con, 'token', $token); ?></h2>
