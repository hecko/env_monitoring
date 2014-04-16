<html>
<head>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
    <style>
        body {
            font-family: Lato;
            font-weight: 300;
        }
    </style>
</head>
<body>
<?php
#print_r($_POST);
#print_r($_GET);

$node['sn']   = $_GET['sn'];
$node['user'] = 'test';
?>
Register node:          </td><td><strong><?php echo $node['sn'] ?></strong> to this account:
<br>
<blockquote>
<form>
Your mail <input value="your@email.com">
and password <input type="password" value="test">
Node's MAC:   <input value="AA:B3:44:45:82:C2">
<input type="Submit" value="Register">
</form>
</blockquote>
</body>
</html>
