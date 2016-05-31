<?php

Class ProjectDispute {
    //General parameters
    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    //read Expense Page Parameters
    public $proj_disp_page_rows = 10;
    public $proj_disp_last_page;
    public $proj_disp_data_offset;
    public $proj_disp_record_num;
    public $proj_disp_load_page;

    public $proj_disp_num_rows;

    // pap definition Details
    public $proj_disp_id;
    public $proj_disp_pap_id;
    public $proj_disp_pap_name;
    public $proj_disp_catg_id;
    public $proj_disp_catg;
    public $proj_disp_other_dtl;
    public $proj_disp_date_log;
    public $proj_disp_log_by;
    public $proj_disp_status;

    function LoadProjectDisputes() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_DISPUTE_LIMIT (@PROJ_ID_, @OFFSET_, @LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @OFFSET_ = " . $this -> proj_disp_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> proj_disp_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PAP_ID = $row -> PAP_ID;
            $PAP_NAME = $row -> PAP_NAME;
            $CATG_ID = $row -> CATG_ID;
            $DISP_CATG = $row -> DISP_CATG;
            $OTHER_DTL = $row -> OTHER_DTL;
            $DATE_LOG = $row -> DATE_LOG;
            // $DISP_NAME,
            $DISP_STATUS = $row -> DISP_STATUS;
            $LOG_BY = $row -> LOG_BY;

            $disp_page = 1;

            $ACTION = '../ui/ui_project_detail.php?Mode=ViewDispute&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&DispID=' . $ID . '&HHID=' . $PAP_ID . '&CatID=' . $CATG_ID . '&UserID=' . $LOG_BY . '&DispStatus=' . $DISP_STATUS . '#Disputes';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteDispute&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&DispID=' . $ID . '#Disputes';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" ><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> proj_disp_record_num = $this -> proj_disp_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> proj_disp_record_num, $ACTION, $PAP_NAME, $DISP_CATG, $DATE_LOG, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectProjectDispute() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_DISPUTE (@ID_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> proj_disp_id);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> proj_disp_pap_id = $row -> PAP_ID;
            $this -> proj_disp_pap_name = $row -> PAP_NAME;
            $this -> proj_disp_catg_id = $row -> CATG_ID;
            $this -> proj_disp_status = $row -> DISP_STATUS;
            $this -> proj_disp_date_log = $row -> DATE_LOG;
            $this -> proj_disp_log_by = $row -> LOG_BY;
            $this -> proj_disp_other_dtl = $row -> OTHER_DTL;

        }
    }

    function InsertProjectDispute() {

    }

    function UpdateProjectDispute() {

    }

    function DeleteProjectDispute() {

    }

    function BindProjectDisputes() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_DISP_CATG()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $DISP_CATG = $row -> DISP_CATG;

            if (isset($_GET['CatID']) && $_GET['CatID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $DISP_CATG);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $DISP_CATG);
            }

        }
        //recuperate resources
        $result -> free();
    }

    function BindProjectUsers() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_USER_ALL()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $USER_NAME = $row -> USER_NAME;
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

    function BindProjectPaps() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_INFO_SEARCH_DISP (@SEARCH_, @PROJ_ID_)";
        $mysqli -> query("SET @SEARCH_ = " . "'" . $this -> pap_search . "'");
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $HHID = $row -> HHID;
            $PAP_NAME = $row -> PAP_NAME;

            if (isset($_GET['HHID']) && $_GET['HHID'] == $HHID) {
                printf("<option value='%s' selected >%s</option>", $HHID, $HHID . '  ' . $PAP_NAME);
            } else {
                printf("<option value='%s' >%s</option>", $HHID, $HHID . '  ' . $PAP_NAME);
            }

        }
        //recuperate resources
        $result -> free();
    }

    function BindAllProjectPaps() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_INFO_ALL (@PROJ_ID_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $HHID = $row -> HHID;
            $PAP_NAME = $row -> PAP_NAME;

            if (isset($_GET['HHID']) && $_GET['HHID'] == $HHID) {
                printf("<option value='%s' selected >%s</option>", $HHID, $HHID . '  ' . $PAP_NAME);
            } else {
                printf("<option value='%s' >%s</option>", $HHID, $HHID . '  ' . $PAP_NAME);
            }

        }
        //recuperate resources
        $result -> free();
    }

    function DisputePageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJ_DISPUTE_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> proj_disp_num_rows = $result -> num_rows;
        $this -> proj_disp_last_page = ceil($this -> proj_disp_num_rows / $this -> proj_disp_page_rows);
    }

}
?>