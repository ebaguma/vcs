<?php

class MastersBio {
    //geeneral parameters
    public $read_num_rows;

    //read Occupation Page parameters
    public $occup_page_rows = 5;
    public $occup_last_page;
    public $occup_data_offset;
    public $occup_record_num;
    public $occup_load_page;

    //read Curr Page Parameters
    public $curr_page_rows = 5;
    public $curr_last_page;
    public $curr_data_offset;
    public $curr_record_num;
    public $curr_load_page;
    public $curr_num_rows;

    //MASTERS occupation Details
    public $occupation_id;
    public $occupation_name;
    public $occupation_other;

    //Project Client Staff Details
    public $curr_id;
    public $curr_name;
    public $curr_country;
    public $curr_other;

    //session variables to keep
    public $select_occupation_id;
   	public $select_curr_id;

    function LoadBioOccupation() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_OCCUPATION_LIMIT ('" . $this -> occup_page_rows . "','" . $this -> occup_data_offset . "')";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $OCCUP_ID = $row -> ID;
            $OCCUP_NAME = $row -> NAME;
            $OCCUP_OTHER = $row -> OTHER_DTL;
            $occup_page = 1;
            $ACTION = '../ui/ui_masters_detail.php?Mode=ViewOccupation&OccupationPage=' . $occup_page . '&OccupationID=' . $OCCUP_ID . '#Occupations';
            $DEL_URL = '../ui/ui_masters_detail.php?Mode=DeleteOccupation&OccupationID=' . $OCCUP_ID . '#Occupations';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> occup_record_num = $this -> occup_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> occup_record_num, $ACTION, $OCCUP_NAME, $OCCUP_OTHER, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();

    }
    function SelectBioOccupation() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_OCCUPATION (" . $this -> occupation_id . ")";
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            $this -> occupation_id = $row -> ID;
            $this -> occupation_name = $row -> NAME;
            $this -> occupation_other = $row -> OTHER_DTL;
        }

    }
    function InsertBioOccupation() {
        include ('code_connect.php');
        #$sql = "CALL USP_INS_PROJECT_CLIENT(@PROJ_ID_, @CLIENT_NAME_, @CLIENT_NBR_, @CLIENT_EMAIL_, @CLIENT_WEBSITE_, @CONTACT_PERSON_, @CLIENT_ADDR_)";
		$sql = "CALL USP_INS_MST_OCCUPATION(@NAME_, @OTHER_DTL_)";

        $mysqli -> query("SET @NAME_ = " . $this -> occupation_name);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> occupation_other . "'");
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
    function UpdateBioOccupation() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_OCCUPATION(@ID_,@NAME_,@OTHER_DTL_)";

        $mysqli -> query("SET @ID_ = " . $this -> occupation_id);
        $mysqli -> query("SET @NAME_ = " . "'" . $this -> occupation_name . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . $this -> occupation_other);
        //$mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id']);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function DeleteBioOccupation() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_OCCUPATION(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> occupation_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( occupation Still In Use )");</script>';
        }
    }
	public function ReadPageParamsOccup() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_OCCUPATION_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> read_num_rows = $result -> num_rows;
        $this -> occup_last_page = ceil($this -> read_num_rows / $this -> occup_page_rows);
    }
	
	
	
    function LoadBioCurr() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_CURR_LIMIT (@CURR_ID,@CURR_PAGE_ROWS,@CURR_DATA_OFFSET)";
        $mysqli -> query("SET @CLIENT_ID = " . $this -> curr_id);
        $mysqli -> query("SET @STAFF_PAGE_ROWS = " . $this -> curr_page_rows);
        $mysqli -> query("SET @STAFF_DATA_OFFSET = " . $this -> curr_data_offset);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            #$CLIENT_ID = $row -> ID;
            $CURR_NAME = $row -> NAME;
            $CURR_CTRY = $row -> CTRY;
            $CURR_OTHER = $row -> OTHER_DTL;
            $curr_page = 1;
            $ACTION = '../ui/ui_project_detail.php?Mode=ViewCurr&CurrID=' . $ID . '#Curr';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteCurr&CurrID=' . $ID . '#Curr';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> curr_record_num = $this -> curr_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> curr_record_num, $ACTION, $CURR_NAME, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }
    function SelectBioCurr() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_CURR (@ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> curr_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            $this -> curr_name = $row -> NAME;
            $this -> curr_country = $row -> CTRY;
            $this -> curr_other = $row -> OTHER_DTL;
        }
    }
    function UpdateBioCurr() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_MST_CURR(@ID_, @NAME_, @CTRY_, @OTHER_DTL_)";

        $mysqli -> query("SET @ID_ = " . $this -> 	curr_id);
        $mysqli -> query("SET @NAME_ = " . "'" . $this -> curr_name . "'");
        $mysqli -> query("SET @CTRY_ = " . $this -> curr_country);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> curr_other . "'");
        // $mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id'] );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
    function InsertBioCurr() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_MST_CURR(@NAME_,@CTRY_,@OTHER_DTL_)";

        #$mysqli -> query("SET @ID_ = " . $this -> 	curr_id);
        $mysqli -> query("SET @NAME_ = " . "'" . $this -> curr_name . "'");
        $mysqli -> query("SET @CTRY_ = " . $this -> curr_country);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> curr_other . "'");
        // $mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id'] );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Insert Not Successful");</script>';
        }

    }
    function DeleteBioCurr() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_CURR(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> curr_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
        }
    }
    public function ReadPageParamsCurr() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_CURR_ALL (" . $this -> select_curr_id . ")";
        $result = $mysqli -> query($sql);
        $this -> curr_num_rows = $result -> num_rows;
        $this -> curr_last_page = ceil($this -> curr_num_rows / $this -> curr_page_rows);
    }

}




?>