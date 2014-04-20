<hr style="width: 100%; background-color: #aaaaaa; border: 0; height: 1px;">
<style>
#footer-div {
  text-align: center;
}

.footer {
    font-size: 8pt;
}
</style>
<div id="footer-div">
<?php
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
print '<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&choe=UTF-8&chl='.urlencode($actual_link).'">';
?>
</div>
<div id="footer-div">
  <span class="footer">cube.cx / <a href="mailto:maco@blava.net">Marcel Hecko</a> (2014)</span>
  <br>
</div>
</body>
</html>
