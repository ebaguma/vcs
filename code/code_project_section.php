<?php

Class ProjectSection {

    //General parameters
    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    //read Expense Page Parameters
    public $proj_sect_page_rows = 5;
    public $proj_sect_last_page;
    public $proj_sect_data_offset;
    public $proj_sect_record_num;
    public $proj_sect_load_page;

    public $proj_sect_num_rows;

    // Expense Details
    // ID,SECT_NAME,SECT_LENGTH,PROJ_ID,OTHER_DTL,UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE
    public $proj_sect_id;
    public $proj_sect_name;
    public $proj_sect_length;
    public $proj_sect_proj_id;
    public $proj_sect_other_dtl;

    function LoadSection() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_SECT_LIMIT (@PROJ_ID_, @OFFSET_, @LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> proj_sect_proj_id);
        $mysqli -> query("SET @OFFSET_ = " . $this -> proj_sect_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> proj_sect_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $SECT_NAME = $row -> SECT_NAME;
            $SECT_LENGTH = $row -> SECT_LENGTH;
            $PROJ_ID = $row -> PROJ_ID;
            $OTHER_DTL = $row -> OTHER_DTL;
            $proj_sect_page = 1;

            $confirm = "Are You Sure?";
            $ACTION = '../ui/ui_project_detail.php?Mode=ViewSection&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&SectID=' . $ID . '#Sections';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteSection&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&SectID=' . $ID . '#Sections';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" ><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> proj_sect_record_num = $this -> proj_sect_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> proj_sect_record_num, $ACTION, $SECT_NAME, $DISP_NAME, $ROLE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectSection() {
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

    function InsertSection() {
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

    function UpdateSection() {
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

    function DeleteSection() {
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

    function ProjSectPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJ_STAFF_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> personnel_num_rows = $result -> num_rows;
        $this -> personnel_last_page = ceil($this -> personnel_num_rows / $this -> personnel_page_rows);
    }

}
?>