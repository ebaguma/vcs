<?php

Class ProjectStaff {

    //General parameters
    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    //read Expense Page Parameters
    public $personnel_page_rows = 5;
    public $personnel_last_page;
    public $personnel_data_offset;
    public $personnel_record_num;
    public $personnel_load_page;

    public $personnel_num_rows;

    //Expense Details
    public $personnel_id;
    public $personnel_user_id;
    public $personnel_user_name;
    public $personnel_disp_name;
    public $personnel_role_id;
    public $personnel_role;
    public $personnel_other_dtl;

    // Sub Category Selection
    public $personnel_category_id;

    function LoadProjectStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_STAFF_LIMIT (@PROJ_ID_, @OFFSET_, @LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @OFFSET_ = " . $this -> personnel_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> personnel_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $USER_ID = $row -> USER_ID;
            $PROJ_ID = $row -> PROJ_ID;
            $USER_NAME = $row -> USER_NAME;
            $DISP_NAME = $row -> DISP_NAME;
            $ROLE_ID = $row -> ROLE_ID;
            $ROLE = $row -> ROLE;
            $OTHER_DTL = $row -> OTHER_DTL;
            $personnel_page = 1;

            $confirm = "Are You Sure?";
            $ACTION = '../ui/ui_project_detail.php?Mode=ViewPersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ID=' . $ID . '&UserID=' . $USER_ID . '&RoleID=' . $ROLE_ID . '#Personnel';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeletePersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ID=' . $ID . '#Personnel';
             $DEL_ACTION = '<a href="' . $DEL_URL . '" ><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> personnel_record_num = $this -> personnel_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> personnel_record_num, $ACTION, $USER_NAME, $DISP_NAME, $ROLE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectProjectStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_STAFF (@USER_ID_, @PROJ_ID_, @ROLE_ID_)";
        $mysqli -> query("SET @USER_ID_ = " . $this -> personnel_user_id);
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @ROLE_ID_ = " . $this -> personnel_role_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> personnel_id = $row -> ID;
            $this -> personnel_user_id = $row -> USER_ID;
            $this -> personnel_role_id = $row -> ROLE_ID;
            $this -> personnel_other_dtl = $row -> OTHER_DTL;

        }

    }

    function BindAllUsers() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_USER_ALL()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $USER_NAME = $row -> USER_NAME;
            // $PWD
            $DISP_NAME = $row -> DISP_NAME;
            $EMAIL = $row -> EMAIL;
            
            if (isset($_GET['UserID']) && $_GET['UserID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $DISP_NAME);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $DISP_NAME);
            }

        }
        //recuperate resources
        $result -> free();
    }

    function BindAllRoles() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_USER_ROLE_ALL()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $ROLE = $row -> ROLE;
            $OTHER_DTL = $row -> OTHER_DTL;
            
            if (isset($_GET['RoleID']) && $_GET['RoleID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $ROLE);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $ROLE);
            }

        }
        //recuperate resources
        $result -> free();
    }
    
    function UpdateProjectStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PROJ_STAFF(@ID_, @USER_ID_, @ROLE_ID_, @OTHER_DTL_, @UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> personnel_id);
        $mysqli -> query("SET @USER_ID_ = " . $this -> personnel_user_id );
        $mysqli -> query("SET @ROLE_ID_ = " . $this -> personnel_role_id);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this->personnel_other_dtl . "'");
        $mysqli -> query("SET @UPDATED_BY_ = " . $this->session_user_id );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Unsuccessful !");</script>';
        }
    }

    function InsertProjectStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_PROJ_STAFF(@USER_ID_, @PROJ_ID_, @ROLE_ID_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @USER_ID_ = " . $this -> personnel_user_id);
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id );
        $mysqli -> query("SET @ROLE_ID_ = " . $this -> personnel_role_id);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this->personnel_other_dtl . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this->session_user_id );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Insert Unsuccessful !");</script>';
        }
    }

    function DeleteProjectStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_PROJ_STAFF(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> personnel_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
        }
    }

    function ProjStaffPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJ_STAFF_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> personnel_num_rows = $result -> num_rows;
        $this -> personnel_last_page = ceil($this -> personnel_num_rows / $this -> personnel_page_rows);
    }

}
?>