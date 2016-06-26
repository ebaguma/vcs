<?php

class ProjectClient {
    //geeneral parameters
    public $read_num_rows;

    //read Client Page parameters
    public $client_page_rows = 5;
    public $client_last_page;
    public $client_data_offset;
    public $client_record_num;
    public $client_load_page;

    //read Staff Page Parameters
    public $staff_page_rows = 5;
    public $staff_last_page;
    public $staff_data_offset;
    public $staff_record_num;
    public $staff_load_page;

    public $staff_num_rows;

    //Project Client Details
    public $client_id;
    public $project_id;
    public $client_name;
    public $client_email;
    public $client_addr;
    public $client_contact_person;
    public $client_website;
    public $client_number;

    //Project Client Staff Details
    public $staff_id;
    public $staff_name;
    public $staff_number;
    public $staff_email;
    public $staff_role;

    //session variables to keep
    public $select_project_id;
    public $select_project_code;

    function LoadProjectClients() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJECT_CLIENT_LIMIT (" . $this -> select_project_id . ",'" . $this -> client_page_rows . "','" . $this -> client_data_offset . "')";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJ_ID = $row -> PROJ_ID;
            $CLIENT_NAME = $row -> CLIENT_NAME;
            $CLIENT_ADDR = $row -> CLIENT_ADDR;
            $CLIENT_EMAIL = $row -> CLIENT_EMAIL;
            $CLIENT_WEBSITE = $row -> CLIENT_WEBSITE;
            $client_page = 1;
            $ACTION = '../ui/ui_project_detail.php?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $client_page . '&ClientID=' . $ID . '#Clients';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $ID . '#Clients';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Client?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> client_record_num = $this -> client_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> client_record_num, $ACTION, $CLIENT_NAME, $CLIENT_EMAIL, $CLIENT_WEBSITE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();

    }

    function SelectProjectClient() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJECT_CLIENT (" . $this -> client_id . ")";
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            $this -> project_id = $row -> PROJ_ID;
            $this -> client_name = $row -> CLIENT_NAME;
            $this -> client_addr = $row -> CLIENT_ADDR;
            $this -> client_email = $row -> CLIENT_EMAIL;
            $this -> client_website = $row -> CLIENT_WEBSITE;
            $this -> client_number = $row -> CLIENT_NBR;
            $this -> client_contact_person = $row -> CONTACT_PERSON;
        }

    }

    function InsertClient() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_PROJECT_CLIENT(@PROJ_ID_, @CLIENT_NAME_, @CLIENT_NBR_, @CLIENT_EMAIL_, @CLIENT_WEBSITE_, @CONTACT_PERSON_, @CLIENT_ADDR_)";

        $mysqli -> query("SET @PROJ_ID_ = " . $this -> select_project_id);
        $mysqli -> query("SET @CLIENT_NAME_ = " . "'" . $this -> client_name . "'");
        $mysqli -> query("SET @CLIENT_NBR_ = " . $this -> client_number);
        $mysqli -> query("SET @CLIENT_EMAIL_ = " . "'" . $this -> client_email . "'");
        $mysqli -> query("SET @CLIENT_WEBSITE_ = " . "'" . $this -> client_website . "'");
        $mysqli -> query("SET @CONTACT_PERSON_ = " . "'" . $this -> client_contact_person . "'");
        $mysqli -> query("SET @CLIENT_ADDR_ = " . "'" . $this -> client_addr . "'");
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }

    function UpdateClient() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PROJECT_CLIENT(@ID_,@CLIENT_NAME_,@CLIENT_NBR_,@CLIENT_EMAIL_,@CLIENT_WEBSITE_,@CONTACT_PERSON_,@CLIENT_ADDR_)";

        $mysqli -> query("SET @ID_ = " . $this -> client_id);
        $mysqli -> query("SET @CLIENT_NAME_ = " . "'" . $this -> client_name . "'");
        $mysqli -> query("SET @CLIENT_NBR_ = " . $this -> client_number);
        $mysqli -> query("SET @CLIENT_EMAIL_ = " . "'" . $this -> client_email . "'");
        $mysqli -> query("SET @CLIENT_WEBSITE_ = " . "'" . $this -> client_website . "'");
        $mysqli -> query("SET @CONTACT_PERSON_ = " . "'" . $this -> client_contact_person . "'");
        $mysqli -> query("SET @CLIENT_ADDR_ = " . "'" . $this -> client_addr . "'");
        //$mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id']);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }

    function DeleteClient() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_PROJECT_CLIENT(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> client_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Client Still In Use )");</script>';
        }
    }

    function LoadClientStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJECT_CLIENT_STAFF_LIMIT (@CLIENT_ID,@STAFF_PAGE_ROWS,@STAFF_DATA_OFFSET)";
        $mysqli -> query("SET @CLIENT_ID = " . $this -> client_id);
        $mysqli -> query("SET @STAFF_PAGE_ROWS = " . $this -> staff_page_rows);
        $mysqli -> query("SET @STAFF_DATA_OFFSET = " . $this -> staff_data_offset);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $CLIENT_ID = $row -> CLIENT_ID;
            $STAFF_NAME = $row -> STAFF_NAME;
            $STAFF_NBR = $row -> STAFF_NBR;
            $STAFF_EMAIL = $row -> STAFF_EMAIL;
            $STAFF_ROLE = $row -> STAFF_ROLE;
            $staff_page = 1;
            $ACTION = '../ui/ui_project_detail.php?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $CLIENT_ID . '&StaffID=' . $ID . '#Clients';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteStaff&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $CLIENT_ID . '&StaffID=' . $ID . '#Clients';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Client Staff?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> staff_record_num = $this -> staff_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> staff_record_num, $ACTION, $STAFF_NAME, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectClientStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJECT_CLIENT_STAFF (@ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> staff_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            $this -> staff_name = $row -> STAFF_NAME;
            $this -> staff_number = $row -> STAFF_NBR;
            $this -> staff_email = $row -> STAFF_EMAIL;
            $this -> staff_role = $row -> STAFF_ROLE;
        }
    }

    function UpdateClientStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PROJECT_CLIENT_STAFF(@ID_, @STAFF_NAME_, @STAFF_NBR_, @STAFF_EMAIL_, @STAFF_ROLE_)";

        $mysqli -> query("SET @ID_ = " . $this -> staff_id);
        $mysqli -> query("SET @STAFF_NAME_ = " . "'" . $this -> staff_name . "'");
        $mysqli -> query("SET @STAFF_NBR_ = " . $this -> staff_number);
        $mysqli -> query("SET @STAFF_EMAIL_ = " . "'" . $this -> staff_email . "'");
        $mysqli -> query("SET @STAFF_ROLE_ = " . "'" . $this -> staff_role . "'");
        // $mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id'] );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }

    function InsertClientStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_PROJECT_CLIENT_STAFF(@CLIENT_ID_,@STAFF_NAME_,@STAFF_NBR_,@STAFF_EMAIL_,@STAFF_ROLE_)";

        $mysqli -> query("SET @CLIENT_ID_ = " . $this -> client_id);
        $mysqli -> query("SET @STAFF_NAME_ = " . "'" . $this -> staff_name . "'");
        $mysqli -> query("SET @STAFF_NBR_ = " . $this -> staff_number);
        $mysqli -> query("SET @STAFF_EMAIL_ = " . "'" . $this -> staff_email . "'");
        $mysqli -> query("SET @STAFF_ROLE_ = " . "'" . $this -> staff_role . "'");
        // $mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id'] );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Insert Not Successful");</script>';
        }
    }

    function DeleteClientStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_PROJECT_CLIENT_STAFF(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> staff_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
        }
    }

    public function ReadPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJECT_CLIENT_ALL (" . $this -> select_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> read_num_rows = $result -> num_rows;
        $this -> client_last_page = ceil($this -> read_num_rows / $this -> client_page_rows);
    }

    public function StaffPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJECT_CLIENT_STAFF_ALL (" . $this -> client_id . ")";
        $result = $mysqli -> query($sql);
        $this -> staff_num_rows = $result -> num_rows;
        $this -> staff_last_page = ceil($this -> staff_num_rows / $this -> staff_page_rows);
    }

}
?>