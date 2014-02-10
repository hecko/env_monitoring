<?php
$con=mysqli_connect("localhost",$db_user,$db_pass,$db_db);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
