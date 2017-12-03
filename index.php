<?php
ob_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel='shortcut icon' type='image/x-icon' href='ui/images/favicon.png' />
        <meta charset="utf-8">
            <meta name="Me" content="Index" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Login</title>
            <link href="ui/css/index.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" type="text/css"
                  href="ui/lib/opensans_regular/stylesheet.css" />
    </head>

    <body onload="<?php CheckReturnUser(); ?>" >

        <!-- @formatter:off -->    
        <?php
        $userid = $password = "";
        $userid_error = $password_error = "";
        $userid_box = "userid_text";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["userid"])) {
                $userid_error = '<img src="ui/images/error.png" title="Check User ID!" class="ErrorImage" />';
            } else {
                $userid = $_POST["userid"];
            }
            if (empty($_POST["password"])) {
                $password_error = '<img src="ui/images/error.png" title="Check Password!" class="ErrorImage" />';
            } else {
                $password = $_POST["password"];
            }
        }

        function CheckReturnUser() {

            session_start();

            if (isset($_SESSION['session_user_id'])) {
                include ('code/code_index.php');
                $CheckReturnUser = new LogInOut();
                $CheckReturnUser->user_id = $_SESSION['session_user_id'];
                $CheckReturnUser->CheckLoginStatus();
                if ($CheckReturnUser->return_session_id == session_id() && $CheckReturnUser->login_status == "TRUE") {
                    header('Location: ui/ui_project_list.php?Mode=Read&PageNumber=1');
                }
            }
        }
        ?>

        <!-- @formatter:off -->
        <div id="loginWrapper"> <span>
                <table width="325" border="0" cellspacing="10">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                        <input type="hidden" name="requester" value="Web"/>
                        <tr>
                            <td class="loginFields" id="userid_text"><input name="userid"
                                                                            type="text" placeholder="Enter Username"
                                                                            value="<?php echo $userid; ?>" class="LoginBoxes" />
                                <span
                                    class="ErrorMessage"> <?php echo $userid_error; ?></span></td>
                        </tr>
                        <tr>
                            <td class="loginFields" id="password_text"><input name="password"
                                                                              type="password" placeholder="Enter Password"
                                                                              value="<?php echo $password; ?>" class="LoginBoxes" />
                                <span
                                    class="ErrorMessage"> <?php echo $password_error; ?> </span></td>
                        </tr>
                        <tr>
                            <td class="submitArea">
                                <input name="Login" 
                                       type="submit" value="Sign In" />
                                <span class="forgotPass"><a
                                        href="#">Forgot Password?</a></span></td>
                        </tr>
                        <tr>
                            <td class="wrongCredentials">

                                <?php
                                if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["requester"] == "Web") {
                                    if (!empty($userid) && !empty($password)) {
                                        include_once ('code/code_index.php');
                                        $login = new LogInOut();
                                        $login->userid = $_POST["userid"];
                                        $login->password = $_POST["password"];
                                        $login->connect_device = $_POST["requester"];
                                        $login->CheckLoginStuff();
                                        if ($login->credential_status == 'TRUE') {
                                            if ($login->login_status == 'TRUE') {
                                                echo '<script>alert("User  (' . $login->userid . ')  Already Logged In !");</script>';
                                            } else {
                                                $login->SaveSessionInfo();
                                                header('Location: ui/ui_project_list.php?Mode=Read&PageNumber=1');
                                            }
                                            /* echo '<script>alert("' . $login->message . '");</script>'; */
                                        } else {
                                            echo "*** Invalid Credentials ***";
                                        }
                                    }
                                }
                                ?>

                            </td>
                        </tr>
                    </form>
                </table>
            </span> </div>
    </body>
</html>