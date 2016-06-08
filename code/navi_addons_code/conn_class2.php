<?php
#$servername = "192.168.7.3";
$servername = "localhost";
$username = "root";
$password = "navi7274";
$database = 'vcs_db';
#$status = "Failed";

$mysqli = new mysqli();
$mysqli -> connect($servername, $username, $password);
$mysqli -> select_db($database);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}else{
	
	echo 'yes connection up';
	
}
?>