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

    function LoadSections() {
        include ('code_connect.php');
        // @PROJ_ID_, @LIMIT_, @OFFSET_
        $sql = "CALL USP_GET_PROJ_SECT_LIMIT (@PROJ_ID_, @LIMIT_, @OFFSET_ )";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @LIMIT_ = " . $this -> proj_sect_page_rows);
        $mysqli -> query("SET @OFFSET_ = " . $this -> proj_sect_data_offset);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            // ID,SECT_NAME,SECT_LENGTH,PROJ_ID,OTHER_DTL,UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE
            
            $ID = $row -> ID;
            $SECT_NAME = $row -> SECT_NAME;
            $SECT_LENGTH = $row -> SECT_LENGTH;
            $PROJ_ID = $row -> PROJ_ID;
            $OTHER_DTL = $row -> OTHER_DTL;
            
            // $proj_sect_page = 1;

            // $confirm = "Are You Sure?";
            
            $ACTION = '../ui/ui_project_detail.php?Mode=ViewSection&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&SectID=' . $ID . '#ProjSections';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteSection&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&SectID=' . $ID . '#ProjSections';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Section?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> proj_sect_record_num = $this -> proj_sect_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>", $this -> proj_sect_record_num, $ACTION, $SECT_NAME, $OTHER_DTL, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectSection() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_SECT (@ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> proj_sect_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> proj_sect_id = $row -> ID;
            $this -> proj_sect_name = $row -> SECT_NAME;
            $this -> proj_sect_length = $row -> SECT_LENGTH;
            $this -> proj_sect_other_dtl = $row -> OTHER_DTL;
            $this -> proj_sect_proj_id = $row -> PROJ_ID;

        }

    }

    function InsertSection() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_PROJ_SECT(@SECT_NAME_, @SECT_LENGTH_, @PROJ_ID_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @SECT_NAME_ = " . "'" . $this -> proj_sect_name . "'");
        $mysqli -> query("SET @SECT_LENGTH_ = " . $this -> proj_sect_length);
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> proj_sect_other_dtl . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

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
        $sql = "CALL USP_UPD_PROJ_SECT(@ID_, @SECT_NAME_, @SECT_LENGTH_, @OTHER_DTL_, @UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> proj_sect_id);
        $mysqli -> query("SET @SECT_NAME_ = " . "'" . $this -> proj_sect_name . "'");
        $mysqli -> query("SET @SECT_LENGTH_ = " . $this -> proj_sect_length);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> proj_sect_other_dtl . "'");
        $mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

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
        $sql = "CALL USP_DEL_PROJ_SECT(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> proj_sect_id);
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
        $sql = "CALL USP_GET_PROJ_SECT_ALL (@PROJ_ID_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        
        $result = $mysqli -> query($sql);
        $this -> proj_sect_num_rows = $result -> num_rows;
        $this -> proj_sect_last_page = ceil($this -> proj_sect_num_rows / $this -> proj_sect_page_rows);
    }

}
?>