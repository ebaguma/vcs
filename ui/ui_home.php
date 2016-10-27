<! doctype html>

<?php

if(isset($_GET['LogOut'])){ LogOut(); }

?>

<html>
<head>
<meta name="Me" content="Index">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Home</title>

<?php
include ('ui_header.php');

function CheckReturnUser() {
    # include ('../code/code_index.php');
    $time = $_SERVER['REQUEST_TIME'];

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (session_status() == PHP_SESSION_ACTIVE && $time < $_COOKIE["session_expire"]) {
        if (($time - $_SESSION['Last_Activity']) < 1800) {
            include_once ('../code/code_index.php');
            $CheckReturnUser = new LogInOut();
            $CheckReturnUser->user_id = $_SESSION['session_user_id'];
            $CheckReturnUser->CheckLoginStatus();
            if ($CheckReturnUser->return_session_id == session_id() && $CheckReturnUser->login_status == "TRUE") {
                // header('Location: ui/ui_project_list.php?PageNumber=1');
                echo 'SetActivePage()';
                $_SESSION['Last_Activity'] = $time;
            } else {
                session_unset();
                session_destroy();
                header('Location: ../index.php?Message=DB_Session_Expired');
            }
        } else {
            include_once ('../code/code_index.php');
            $InactiveReturnUser = new LogInOut();
            $InactiveReturnUser->user_id = $_SESSION['session_user_id'];
            $InactiveReturnUser->LogOff();
            session_unset();
            session_destroy();
            header('Location: ../index.php?Message=Inactive_Session_Expired');
        }
    } else if ($time > $_COOKIE["session_expire"]) {
        include_once ('../code/code_index.php');
        $InactiveReturnUser = new LogInOut();
        # $InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
        $InactiveReturnUser->user_id = $_COOKIE["last_user"];
        $InactiveReturnUser->LogOff();
        session_unset();
        session_destroy();
        header('Location: ../index.php?Message=Session_Expired');
    } else {
        session_unset();
        session_destroy();
        header('Location: ../index.php?Message=Session_Expired');
    }
}

function LogOut() {
    include_once ('../code/code_index.php');

    $logout = new LogInOut();
    //session_start();
    if (isset($_SESSION['session_user_id'])) {
        $logout->user_id = $_SESSION['session_user_id'];
    } else {
        $logout->user_id = $_COOKIE["last_user"];
    }
    $logout->LogOff();
}
?>

<div class="ContentParent">
  <div class="Content">
    
    
    <div class="ContentTitle2">Dashboard, Quick Status</div>
    
   <br>
   
    <div class="ContentStatus"> 
    <p>Doughnut Chart showing consolidated status
    
    </p>
    <img src="images/doughnut_chart.jpg" width="480px" height="300px" alt=""/> </div>
    <div class="ContentStatus">
    <p>Bar Chart showing progress</p>
    <img src="images/bar_chart.png" width="480px" height="300px" alt=""/> </div>
  </div>
</div>

<?php include ('ui_footer.php'); ?>

</body>
</html>
