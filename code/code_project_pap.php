<?php

class ProjectPap {
    //General parameters
    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    //read Expense Page Parameters
    public $pap_page_rows = 10;
    public $pap_last_page;
    public $pap_data_offset;
    public $pap_record_num;
    public $pap_load_page;

    public $pap_num_rows;

    // pap definition Details
    public $pap_hhid;
    public $pap_name;
    public $pap_plot_ref;
    public $pap_ref_no;
    public $pap_status_id;
    public $pap_design;
    public $pap_type;
    public $pap_proj_id;
    
    public $pap_search; 

    function LoadProjectPaps() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_INFO_LIMIT (@PROJ_ID_, @OFFSET_, @LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @OFFSET_ = " . $this -> pap_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> pap_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $HHID = $row -> HHID;
            $PAP_NAME = $row -> PAP_NAME;
            $PLOT_REF = $row -> PLOT_REF;
            $REF_NO = $row -> REF_NO;
            // $PAP_STATUS_ID,
            $DESIGN = $row -> DESIGN;
            $PAP_TYPE = $row -> PAP_TYPE;

            $pap_page = 1;

            $ACTION = '../ui/ui_project_detail.php?Mode=ViewPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID . '#ProjPAP';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeletePap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID . '#ProjPAP';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete PAP?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> pap_record_num = $this -> pap_record_num + 1;
            printf("<tr><td>%s</td><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_record_num, $HHID, $ACTION, $PAP_NAME, $PLOT_REF, $DESIGN, $PAP_TYPE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SearchProjectPaps(){
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_INFO_SEARCH_LIMIT (@SEARCH_, @PROJ_ID_, @OFFSET_, @LIMIT_)";
        $mysqli -> query("SET @SEARCH_ = " . "'" . $this -> pap_search . "'");
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @OFFSET_ = " . $this -> pap_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> pap_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $HHID = $row -> HHID;
            $PAP_NAME = $row -> PAP_NAME;
            $PLOT_REF = $row -> PLOT_REF;
            $REF_NO = $row -> REF_NO;
            // $PAP_STATUS_ID,
            $DESIGN = $row -> DESIGN;
            $PAP_TYPE = $row -> PAP_TYPE;

            $pap_page = 1;

            $ACTION = '../ui/ui_project_detail.php?Mode=ViewPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID . '#ProjPAP';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeletePap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID . '#ProjPAP';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" ><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> pap_record_num = $this -> pap_record_num + 1;
            printf("<tr><td>%s</td><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_record_num, $HHID, $ACTION, $PAP_NAME, $PLOT_REF, $DESIGN, $PAP_TYPE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectProjectPap() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_INFO (@HHID_)";
        $mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);

        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> pap_name = $row -> PAP_NAME;
            $this -> pap_plot_ref = $row -> PLOT_REF;
            // REF_NO,
            // PAP_STATUS_ID,
            $this -> pap_design = $row -> DESIGN;
            $this -> pap_type = $row -> PAP_TYPE;
            //PROJ_ID

        }
    }

    function InsertProjectPap() {
        include('code_connect.php');
        $sql = "CALL USP_INS_PAP_INFO(@PAP_NAME_, @PLOT_REF_, @DESIGN_, @PAP_TYPE_, @PROJ_ID_, @CREATED_BY_)";

        $mysqli -> query("SET @PAP_NAME_ = " . "'" . $this -> pap_name . "'");
        $mysqli -> query("SET @PLOT_REF_ = " . "'" . $this -> pap_plot_ref . "'");
        $mysqli -> query("SET @DESIGN_ = " . "'" . $this -> pap_design . "'");
        $mysqli -> query("SET @PAP_TYPE_ = " . "'" . $this -> pap_type . "'");
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Insert Unsuccessful !");</script>';
        }
    }

    function UpdateProjectPap() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PAP_INFO(@HHID_, @PAP_NAME_, @PLOT_REF_, @DESIGN_, @PAP_TYPE_, @UPDATED_BY_)";

        $mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);
        $mysqli -> query("SET @PAP_NAME_ = " . "'" . $this -> pap_name . "'");
        $mysqli -> query("SET @PLOT_REF_ = " . "'" . $this -> pap_plot_ref . "'");
        $mysqli -> query("SET @DESIGN_ = " . "'" . $this -> pap_design . "'");
        $mysqli -> query("SET @PAP_TYPE_ = " . "'" . $this -> pap_type . "'");
        $mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Unsuccessful !");</script>';
        }
    }

    function DeleteProjectPap() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_PAP_INFO(@HHID_)";

        $mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
        }
    }

    function ObsoleteProjectPap() {

    }

    function ProjectPapGridParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PAP_INFO_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> pap_num_rows = $result -> num_rows;
        $this -> pap_last_page = ceil($this -> pap_num_rows / $this -> pap_page_rows);
    }
    
    function ProjectPapSearchParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PAP_INFO_SEARCH_ALL (@SEARCH_, @PROJ_ID_)";
        $mysqli -> query("SET @SEARCH_ = " . "'" . $this -> pap_search . "'");
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        
        $result = $mysqli -> query($sql);
        $this -> pap_num_rows = $result -> num_rows;
        $this -> pap_last_page = ceil($this -> pap_num_rows / $this -> pap_page_rows);
    }

}
?>