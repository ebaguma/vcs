<?php
/*$servername = "192.168.7.3";
$username = "application";
$password = "application";
$database = "VCS_DB";
$status = "Failed";

$mysqli = new mysqli();
$mysqli -> connect($servername, $username, $password);
$mysqli -> select_db($database);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
*/

$servername = "192.168.7.3";
#$servername = "localhost";
$username = "application";
$password = "application";
$database = "VCS_DB_IVAN";
$status = "Failed";

$mysqli = new mysqli();
$mysqli -> connect($servername, $username, $password);
$mysqli -> select_db($database);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>

