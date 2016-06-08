<?php
<<<<<<< HEAD
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

#$servername = "192.168.7.3";
$servername = "localhost";
=======
$servername = "192.168.7.3";
>>>>>>> bd5e3fa0efd989c0071c01ec33304d8e710f6619
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
?>

