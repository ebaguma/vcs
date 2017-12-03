<?php

/* $servername = "192.168.7.3";
$username = "application";
$password = "application";
$database = "VCS_DB"; */

$status = "Failed";

include ('code_config.php');

$mysqli = new mysqli();
$mysqli -> connect($database_server, $database_user, $database_pwd);
$mysqli -> select_db($database);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>

