<?php

class MastersBio {
    //general parameters
    public $selected_project_id;
   	public $selected_project_code;
    public $session_user_id;

    //read Occupation Page parameters
    public $occupn_page_rows = 5;
    public $occupn_last_page;
    public $occupn_data_offset;
    public $occupn_record_num;
    public $occupn_load_page;
    public $occupn_num_rows;

    
    //read Curr Page Parameters
    public $curr_page_rows = 5;
    public $curr_last_page;
    public $curr_data_offset;
    public $curr_record_num;
    public $curr_load_page;
    public $curr_num_rows;

    //MASTERS occupation Details
    public $masters_occupation_id;
    public $masters_occupation_name;
    public $masters_occupation_job;
    public $masters_occupation_other;
    //MASTERS Curr Details
    public $masters_curr_id;
    public $masters_curr_name;
    public $masters_curr_ctry;
    public $masters_curr_other;

    //session variables to keep
   
    //Occupation section
    function LoadBioOccupation() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_OCCUPATION_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> occupn_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> occupn_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            #ID,`NAME`,OTHER_DTL,IS_DELETED,UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE
            $ID = $row -> ID;
            $NAME = $row -> OCCUPN_NAME;
            $OTHER_DTL = $row -> OTHER_DTL;
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewOccupation&OccupationID=' . $ID . '#BioID';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteOccupation&OccupationID=' . $ID . '#BioID';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> occupn_record_num = $this -> occupn_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>", $this -> occupn_record_num, $ACTION, $NAME, $OTHER_DTL, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();

    }
    function SelectBioOccupation() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_OCCUPATION (@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_occupation_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            #$this -> masters_occupation_id = $row -> ID;
            $this -> masters_occupation_name = $row -> OCCUPN_NAME;
            $this -> masters_occupation_other = $row -> OTHER_DTL;
        }

    }
    function InsertBioOccupation() {
        #NAME_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_OCCUPATION(@NAME_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @NAME_ = " . "'" . $this -> masters_occupation_name . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_occupation_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
    function UpdateBioOccupation() {
        #ID_ INT(11), NAME_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_ VARCHAR(200)
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_OCCUPATION(@ID_,@NAME_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_occupation_id);
        $mysqli -> query("SET @NAME_ = " . "'" . $this -> masters_occupation_name . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_occupation_other  . "'");
        $mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);
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

        $mysqli -> query("SET @ID_ = " . $this -> masters_occupation_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( occupation Still In Use )");</script>';
        }
    }
	public function ReadPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_OCCUPATION_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> occupn_num_rows = $result -> num_rows;
        $this -> occupn_last_page = ceil($this -> occupn_num_rows / $this -> occupn_page_rows);
    }
	//end of Occupation section
	
	
    function LoadBioCurr() {
        include ('code_connect.php');
       #LIMIT_ INT, OFFSET_ INT
        $sql = "CALL USP_GET_MST_CURR_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> curr_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> curr_data_offset);
        $result = $mysqli -> query($sql);

        while ($row = $result -> fetch_object()) {
            #ID,CURR_NAME,CTRY,OTHER_DTL
            $ID         = $row -> ID;
            $NAME       = $row -> CURR_NAME;
            $CTRY       = $row -> CTRY;
            $OTHER_DTL  = $row -> OTHER_DTL;
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewCurr&CurrID=' . $ID . '#BioID';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteCurr&CurrID=' . $ID . '#BioID';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> curr_record_num = $this -> curr_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> curr_record_num, $ACTION, $NAME, $CTRY, $OTHER_DTL, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }
    function SelectBioCurr() {
         include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_CURR (@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_curr_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            #$this -> masters_curr_id = $row -> ID;
            $this -> masters_curr_name = $row -> CURR_NAME;
            $this -> masters_curr_CTRY = $row -> CTRY;
            $this -> masters_curr_other = $row -> OTHER_DTL;
        }
    }
    function InsertBioCurr() {
       include ('code_connect.php');
        #NAME_ VARCHAR(40), CTRY_ VARCHAR(20), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
		$sql = "CALL USP_INS_MST_CURR(@NAME_, @CTRY_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @NAME_ = " . "'" . $this -> masters_curr_name . "'");
        $mysqli -> query("SET @CTRY_ = " . "'" . $this -> masters_curr_ctry . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_curr_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
    function UpdateBioCurr() {
        include ('code_connect.php');
        #ID_ INT(11), NAME_ VARCHAR(40), CTRY_ VARCHAR(20), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
        $sql = "CALL USP_UPD_MST_CURR(@ID_,@NAME_,@CTRY_@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_curr_id);
        $mysqli -> query("SET @NAME_ = " . "'" . $this -> masters_curr_name . "'");
         $mysqli -> query("SET @CTRY_ = " . "'" . $this -> masters_curr_ctry . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_curr_other  . "'");
        $mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);
        //$mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id']);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
    function DeleteBioCurr() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_CURR(@ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> masters_occupation_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( occupation Still In Use )");</script>';
        }
    }
    public function ReadPageParamsCurr() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_CURR_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> curr_num_rows = $result -> num_rows;
        $this -> curr_last_page = ceil($this -> curr_num_rows / $this -> curr_page_rows);
    }

}




?>