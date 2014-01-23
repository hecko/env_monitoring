<?php
$con=mysqli_connect("localhost", $dbuser, $dbpass, "marcel.env");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
