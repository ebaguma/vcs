<?php

Class ProjectPapList {
    
    //General parameters
    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    //read Expense Page Parameters
    public $pap_page_rows = 15;
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
    
    function LoadPaps() {
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
            $DESIGN = $row -> DESIGN;
            $PAP_TYPE = $row -> PAP_TYPE;

            $pap_page = 1;

            $ACTION = '../ui/ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID ;
            $DEL_URL = '../ui/ui_pap_info.php?Mode=DeletePap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID ;
            $DEL_ACTION = '<a href="' . $DEL_URL . '" ><i class="bk-trashcan bk-2x"></i></a>';

            $this -> pap_record_num = $this -> pap_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $HHID, $ACTION, $PAP_NAME, $PLOT_REF, $DESIGN, $PAP_TYPE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SearchPaps() {
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

            $ACTION = '../ui/ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID . '#BasicInfo';
            $DEL_URL = '../ui/ui_pap_info.php?Mode=DeletePap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $HHID . '#BasicInfo';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" ><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> pap_record_num = $this -> pap_record_num + 1;
            printf("<tr><td>%s</td><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_record_num, $HHID, $ACTION, $PAP_NAME, $PLOT_REF, $DESIGN, $PAP_TYPE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectPap() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_INFO (@HHID_)";
        $mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);

        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> pap_name = $row -> PAP_NAME;
            $this -> pap_plot_ref = $row -> PLOT_REF;
            $this -> pap_design = $row -> DESIGN;
            $this -> pap_type = $row -> PAP_TYPE;
        }
    }

    function ReadPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PAP_INFO_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> pap_num_rows = $result -> num_rows;
        $this -> pap_last_page = ceil($this -> pap_num_rows / $this -> pap_page_rows);
    }

    function SearchPageParams() {
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