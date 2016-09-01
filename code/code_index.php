<?php

class LogInOut {

    // login variables
    public $userid;
    public $password;
    public $credential_status;
    public $connect_device;
    public $callerFunction;

    // Session Variables to Use
    private $username;
    public $user_id;
    private $displayname;
    private $email;
    public $role;
    public $message;

    // Database Session Variables to use
    public $login_status;
    public $logout_status;
    public $return_session_id;

    public function CheckLoginStuff() {
        include ('code_connect.php');
        $sql = "CALL USP_MST_USER_LOGIN('" . $this -> userid . "','" . $this -> password . "')";
        $results = $mysqli -> query($sql);
        if ($mysqli -> errno) {
            die($mysqli -> errno . " " . $mysqli -> error);
        } else {
            $row = $results -> fetch_object();
        }
        if ($row -> LOGIN_STATUS == "FALSE") {
            $this -> credential_status = "FALSE";
        } else {

            // expose variables for session to pick up
            $this -> credential_status = "TRUE";
            $this -> userid = $row -> USERNAME_;
            $this -> user_id = $row -> ID_;
            $this -> displayname = $row -> DISPLAYNAME_;
            $this -> email = $row -> EMAIL_;
            $this -> role = $row -> ROLENAME_;

            // $this->SaveSessionInfo();
            if ($this -> connect_device == "Web") {
                if ($row -> ROLENAME_ == 'System Admin') {
                    session_start();
                    $this -> LogOff();
                } else {
                    $this -> CheckLoginStatus();
                }
            }
        }
    }

    public function SaveSessionInfo() {
        include ('code_connect.php');

        // start the user session
        session_set_cookie_params(5*60, "/");
        session_start();
        // session_name('vcs');
        
        // session variables to use
        $_SESSION['session_user_id'] = $this -> user_id;
        $_SESSION['display_name'] = $this -> displayname;
        $_SESSION['email'] = $this -> email;
        $_SESSION['Expire']=time() + (12*60*60);
        $_SESSION['Last_Activity'] = time();
        $_SESSION['post_id'] = "start";
        $_SESSION['client_page_number'] = 1;

        $this -> message = $_SESSION['session_user_id'] . "&Session=" . session_id();

        // variables to use
        $user = $this -> user_id;
        $device_ip = $_SERVER['REMOTE_ADDR'];
        $device = $this -> connect_device;
        $session_id = session_id();
        $is_deleted = "false";
        $other_dtl = "User Login";

        //Set Remember me cookies
        setcookie("last_user", $this -> user_id, time() + (7200), "/");
        setcookie("last_session", session_id(), time() + (7200), "/");

        // send USERID_, LOGINDATE_, DEVICEMAC_, UPDATEDBY_, UPDATEDDATE_, ISDELETED_ to the database
        $sql = "CALL USP_INS_SYS_SESSION(" . $user . ",'" . $device_ip . "','" . $device . "','" . $session_id . "','" . $other_dtl . "'," . $user . ",'" . $is_deleted . "')";

        $results = $mysqli -> query($sql);
        if ($mysqli -> errno) {
            die($mysqli -> errno . " " . $mysqli -> error);
        } else {
            $row = $results -> fetch_object();
        }
    }

    public function CheckLoginStatus() {
        //session_start();
        include ('code_connect.php');

        $sql = "CALL USP_GET_SYS_SESSION(" . $this -> user_id . ")";

        $results = $mysqli -> query($sql);
        if ($mysqli -> errno) {
            die($mysqli -> errno . " " . $mysqli -> error);
        } else {
            $row = $results -> fetch_object();
        }
        $this -> login_status = $row -> LOGIN_STATUS;
        $this -> return_session_id = $row -> SESSIONID_;
        $this -> role = $row -> ROLENAME_;
    }

    public function LogOff() {
        include ('code_connect.php');
        $user = $this -> user_id;
        // $_SESSION['session_user_id'];
        $sql = "CALL USP_DEL_SYS_SESSION(" . $user . ")";
        $results = $mysqli -> query($sql);
        if ($mysqli -> errno) {
            die($mysqli -> errno . " " . $mysqli -> error);
        } else {
            $row = $results -> fetch_object();
        }
        $this -> message = $row -> LOGOUT_STATUS;

        //Delete Cookies

        //Destroy the seession
        session_unset();
        session_destroy();
        header('Location: ../index.php?logout_status=' . $this -> message);
    }

}

?>