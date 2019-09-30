<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["requester"] == "Mobile") {

    include_once ('code/code_index.php');
    $login = new LogInOut();
    $login -> userid = $_POST["userid"];
    $login -> password = $_POST["password"];
    $login -> connect_device = $_POST["requester"];
    $login -> CheckLoginStuff();
    if ($login -> credential_status == 'TRUE') {
        //$login -> SaveSessionInfo();
        //$mobile_login_status = "TRUE";
        echo 'TRUE';
    } else {
        //$mobile_login_status = "FALSE";
        echo 'FALSE';
    }
}
?>