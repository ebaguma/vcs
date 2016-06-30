<?php

class MastersBio {
    //general parameters
    public $selected_project_id;
   	public $selected_project_code;
    public $session_user_id;
	
	#READ parameters
    
    
    
    #:BIO:#
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

	#::FAMILY::#
    //read relation Page parameters
    public $relation_page_rows = 5;
    public $relation_last_page;
    public $relation_data_offset;
    public $relation_record_num;
    public $relation_load_page;
    public $relation_num_rows;
    //read tribe Page Parameters
    public $tribe_page_rows = 5;
    public $tribe_last_page;
    public $tribe_data_offset;
    public $tribe_record_num;
    public $tribe_load_page;
    public $tribe_num_rows;
	//read religion Page Parameters
    public $religion_page_rows = 5;
    public $religion_last_page;
    public $religion_data_offset;
    public $religion_record_num;
    public $religion_load_page;
    public $religion_num_rows;
    
	#::VALUATION::#
	//read crop Page Parameters
    public $crop_page_rows = 5;
    public $crop_last_page;
    public $crop_data_offset;
    public $crop_record_num;
    public $crop_load_page;
    public $crop_num_rows;
	//read land Page Parameters
    public $land_page_rows = 5;
    public $land_last_page;
    public $land_data_offset;
    public $land_record_num;
    public $land_load_page;
    public $land_num_rows;
	//read impro Page Parameters
    public $impro_page_rows = 5;
    public $impro_last_page;
    public $impro_data_offset;
    public $impro_record_num;
    public $impro_load_page;
    public $impro_num_rows; //(not functional)
    
	#::PROJECT::#
	//read mstloc Page Parameters
    public $mstloc_page_rows = 5;
    public $mstloc_last_page;
    public $mstloc_data_offset;
    public $mstloc_record_num;
    public $mstloc_load_page;
    public $mstloc_num_rows;//master location details muliti_DBs(not functional)
	//read mstfin Page Parameters
    public $mstfin_page_rows = 5;
    public $mstfin_last_page;
    public $mstfin_data_offset;
    public $mstfin_record_num;
    public $mstfin_load_page;
    public $mstfin_num_rows; //master financials muliti_DBs(not functional)
	//read dispute Page Parameters
    public $dispute_page_rows = 5;
    public $dispute_last_page;
    public $dispute_data_offset;
    public $dispute_record_num;
    public $dispute_load_page;
    public $dispute_num_rows;
    
	#::REPORTS::#
	//read doc Page Parameters
    public $doc_page_rows = 5;
    public $doc_last_page;
    public $doc_data_offset;
    public $doc_record_num;
    public $doc_load_page;
    public $doc_num_rows;
	//read report Page Parameters
    public $report_page_rows = 5;
    public $report_last_page;
    public $report_data_offset;
    public $report_record_num;
    public $report_load_page;
    public $report_num_rows;
	
	#MASTERS parameters
    #:BIO:#
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

	#::FAMILY::#
    //MASTERS relation Details
    public $masters_relation_id;
    public $masters_relation_relation;
    public $masters_relation_other;
    //MASTERS tribe Details
    public $masters_tribe_id;
    public $masters_tribe_tribe;
    public $masters_tribe_other;
    public $masters_tribe_location;
	//MASTERS religion Details
    public $masters_religion_id;
    public $masters_religion_religion;
    public $masters_religion_other;
	
	#::VALUATION::#
	//MASTERS crop Details
    public $masters_crop_id;
    public $masters_crop_crop;
    public $masters_crop_other;
    //MASTERS land Details
    public $masters_land_id;
    public $masters_land_land;
    public $masters_land_other;
	//MASTERS improvement Details
    public $masters_impro_id;
    public $masters_impro_name;
    public $masters_impro_other; //(not functional)
	
	#::PROJECTS::#
	//MASTERS mstloc Details
    public $masters_mstloc_id;
    public $masters_mstloc_name;
    public $masters_mstloc_other;
    //MASTERS mstfin Details
    public $masters_mstfin_id;
    public $masters_mstfin_name;
    public $masters_mstfin_other;
	//MASTERS dispute Details
    public $masters_dispute_id;
    public $masters_dispute_dispute;
    public $masters_dispute_other;
	
	#::REPORTS::#
	//MASTERS doc Details
    public $masters_doc_id;
    public $masters_doc_doc;
    public $masters_doc_other;
    //MASTERS report Details
    public $masters_report_id;
    public $masters_report_report;
    public $masters_report_code;
    public $masters_report_other;
	
    
    //#:: BIO section ::#
    //=>read Occupation
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
    
	//=>read Curr
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

    
    
    //#:: FAMILY section ::# 
    //=>read relation
	#ID_  INT(11), RELATION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
    function LoadFamRelations() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_RELATION_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> relation_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> relation_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           #RELATION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200)
            $ID = $row -> ID;
            $RELATION = $row -> RELATION;
            $OTHER_DTL = $row -> OTHER_DTL;
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewRelation&RelationID=' . $ID . '#BioFamily';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteRelation&RelationID=' . $ID . '#BioFamily';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> relation_record_num = $this -> relation_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>", $this -> relation_record_num, $ACTION, $RELATION, $OTHER_DTL, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }
	function SelectFamRelations() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_RELATION(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_relation_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            #$this -> masters_occupation_id = $row -> ID;
            $this -> masters_relation_relation = $row -> RELATION;
            $this -> masters_relation_other = $row -> OTHER_DTL;
        }
    }
	function InsertFamRelations() {
        #RELATION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_RELATION(@RELATION_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @RELATION_ = " . "'" . $this -> masters_relation_relation . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_relation_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateFamRelations() {
        #ID_ INT(11), NAME_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_ VARCHAR(200)
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_RELATION(@ID_,@RELATION_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_relation_id);
        $mysqli -> query("SET @RELATION_ = " . "'" . $this -> masters_relation_relation . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_relation_other  . "'");
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
	function DeleteFamRelations() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_RELATION(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_relation_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( relation Still In Use )");</script>';
        }
    }
	public function ReadPageParamsRel() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_RELATION_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> relation_num_rows = $result -> relation_num_rows;
        $this -> relation_last_page = ceil($this -> relation_num_rows / $this -> relation_page_rows);
    }
    
    //=>read tribe
	#ID_  INT(11), TRIBE_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), LOCATION_ VARCHAR(40),UPDATED_BY_  INT(11)
	function LoadFamTribe() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_TRIBE_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> tribe_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> tribe_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           #TRIBE_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), LOCATION_ VARCHAR(40), CREATED_BY_ INT(11)
            $ID = $row -> ID;
            $TRIBE = $row -> TRIBE;
            $OTHER_DTL = $row -> OTHER_DTL;
			$LOCATION = $row -> LOCATION;
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewTribe&TribeID=' . $ID . '#BioFamily';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteTribe&TribeID=' . $ID . '#BioFamily';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> tribe_record_num = $this -> tribe_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>", $this -> tribe_record_num, $ACTION, $TRIBE, $LOCATION, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }	
	function SelectFamTribe() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_TRIBE(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_tribe_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            $this -> masters_tribe_relation = $row -> TRIBE;
            $this -> masters_tribe_other = $row -> OTHER_DTL;
			$this -> masters_tribe_location = $row -> LOCATION;
        }
    }
	function InsertFamTribe() {
        #TRIBE_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), LOCATION_ VARCHAR(40), CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_TRIBE(@TRIBE_, @OTHER_DTL_, @LOCATION_,  @CREATED_BY_)";

        $mysqli -> query("SET @TRIBE_ = " . "'" . $this -> masters_tribe_tribe . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_tribe_other . "'");
		$mysqli -> query("SET @LOCATION_ = " . "'" . $this -> masters_tribe_location . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateFamTribe() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_TRIBE(@ID_,@TRIBE_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_tribe_id);
        $mysqli -> query("SET @TRIBE_ = " . "'" . $this -> masters_tribe_tribe . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_tribe_other  . "'");
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
	function DeleteFamTribe() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_TRIBE(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_tribe_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( occupation Still In Use )");</script>';
        }
    }
	public function ReadPageParamsTri() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_TRIBE_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> tribe_num_rows = $result -> tribe_num_rows;
        $this -> tribe_last_page = ceil($this -> tribe_num_rows / $this -> tribe_page_rows);
    }
    
    //=>read religion 
	#ID_  INT(11), RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
	function LoadFamReligion() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_RELIGION_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> religion_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> religion_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
            $ID = $row -> ID;
            $RELIGION = $row -> RELIGION;
            $OTHER_DTL = $row -> OTHER_DTL;
			
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewReligion&ReligionID=' . $ID . '#BioFamily';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteReligion&ReligionID=' . $ID . '#BioFamily';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> religion_record_num = $this -> religion_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> religion_record_num, $ACTION, $RELIGION, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }	
	function SelectFamReligion() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_RELIGION(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_religion_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            $this -> masters_religion_religion = $row -> RELIGION;
            $this -> masters_religion_other = $row -> OTHER_DTL;
        }
    }
	function InsertFamReligion() {
        #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_RELIGION(@RELIGION_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @RELIGION_ = " . "'" . $this -> masters_religion_religion . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_religion_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateFamReligion() {
        #ID_ INT(11), NAME_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_ VARCHAR(200)
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_RELIGION(@ID_,@RELIGION_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_religion_id);
        $mysqli -> query("SET @RELIGION_ = " . "'" . $this -> masters_religion_religion . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_religion_other  . "'");
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
	function DeleteFamReligion() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_RELIGION(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_religion_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful (religion Still In Use )");</script>';
        }
    }
	public function ReadPageParamsRel() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_RELIGION_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> religion_num_rows = $result -> religion_num_rows;
        $this -> religion_last_page = ceil($this -> religion_num_rows / $this -> religion_page_rows);
    }
	
	//#:: Valuation section ::# 
    //=>read crop 
	#ID_  INT(11), CROP_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
	function LoadValCrop() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_CROP_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> crop_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> crop_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
            $ID = $row -> ID;
            $CROP = $row -> CROP;
            $OTHER_DTL = $row -> OTHER_DTL;
			
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewCrop&CropID=' . $ID . '#ValuationInfo';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteCrop&CropID=' . $ID . '#ValuationInfo';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> crop_record_num = $this -> crop_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> crop_record_num, $ACTION, $CROP, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }	
	function SelectValCrop() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_CROP(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_crop_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            #$this -> masters_occupation_id = $row -> ID;
            $this -> masters_crop_crop = $row -> CROP;
            $this -> masters_crop_other = $row -> OTHER_DTL;
        }

    }
	function InsertValCrop() {
        #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_CROP(@CROP_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @CROP_ = " . "'" . $this -> masters_crop_crop . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_crop_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateValCrop() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_CROP(@ID_,@CROP_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_crop_id);
        $mysqli -> query("SET @CROP_ = " . "'" . $this -> masters_crop_crop . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_crop_other  . "'");
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
	function DeleteValCrop() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_CROP(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_crop_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful (crop Still In Use )");</script>';
        }
    }
	public function ReadPageParamsCrop() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_CROP_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> crop_num_rows = $result -> crop_num_rows;
        $this -> crop_last_page = ceil($this -> crop_num_rows / $this -> crop_page_rows);
    }
    //=>read land
	#ID_  INT(11), LND_TYPE_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
	function LoadValLand() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_LAND_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> land_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> land_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           
            $ID = $row -> ID;
            $LND_TYPE = $row -> LND_TYPE;
            $OTHER_DTL = $row -> OTHER_DTL;
			
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewLand&LandID=' . $ID . '#ValuationInfo';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteLand&LandID=' . $ID . '#ValuationInfo';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> land_record_num = $this -> land_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> land_record_num, $ACTION, $LND_TYPE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }
	function SelectValLand() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_LAND(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_land_id);
        $results = $mysqli -> query($sql);
        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            #$this -> masters_occupation_id = $row -> ID;
            $this -> masters_land_land = $row -> LND_TYPE;
            $this -> masters_land_other = $row -> OTHER_DTL;
        }
    }
	function InsertValLand() {
        #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_LAND(@LND_TYPE_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @LND_TYPE_ = " . "'" . $this -> masters_land_land . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_land_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateValLand() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_LAND(@ID_,@LND_TYPE_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_land_id);
        $mysqli -> query("SET @LND_TYPE_ = " . "'" . $this -> masters_land_land . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_land_other  . "'");
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
	function DeleteValLand() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_LAND(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_land_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful (land Still In Use )");</script>';
        }
    }
	public function ReadPageParamsLand() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_LAND_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> land_num_rows = $result -> land_num_rows;
        $this -> land_last_page = ceil($this -> land_num_rows / $this -> land_page_rows);
    }
	
	//#:: PROJECTS section ::# 
    //=>read dispute
	#ID_  INT(11), DISP_CATG_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
	function LoadProjDispute() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_DISPUTE_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> dispute_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> dispute_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           
            $ID = $row -> ID;
            $DISP_CATG = $row -> DISP_CATG;
            $OTHER_DTL = $row -> OTHER_DTL;
			
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewDispute&DisputeID=' . $ID . '#ProjectsInfo';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteDispute&DisputeD=' . $ID . '#ProjectsInfo';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> dispute_record_num = $this -> dispute_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> dispute_record_num, $ACTION, $DISP_CATG, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }
	function SelectProjDispute() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_DISPUTE(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_dispute_id);
        $results = $mysqli -> query($sql);
        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            #$this -> masters_occupation_id = $row -> ID;
            $this -> masters_dispute_dispute = $row -> DISP_CATG;
            $this -> masters_dispute_other = $row -> OTHER_DTL;
        }
    }
	function InsertProjDispute() {
        #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_DISPUTE(@DISP_CATG_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @DISP_CATG_ = " . "'" . $this -> masters_dispute_dispute . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_dispute_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateProjDispute() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_DISPUTE(@ID_,@DISP_CATG_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_dispute_id);
        $mysqli -> query("SET @DISP_CATG_ = " . "'" . $this -> masters_dispute_dispute . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_dispute_other  . "'");
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
	function DeleteProjDispute() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_DISPUTE(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_dispute_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful (dispute Still In Use )");</script>';
        }
    }
	public function ReadPageParamsDisp() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_DISPUTE_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> dispute_num_rows = $result -> num_rows;
        $this -> dispute_last_page = ceil($this -> dispute_num_rows / $this -> dispute_page_rows);
    }
	
	
	//#:: REPORTS section ::# 
    //=>read doc
	#ID_  INT(11), DOC_TYPE_ VARCHAR(40), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
	function LoadSysDoc() {
        //LIMIT_ INT, OFFSET_ INT
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_DOC_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> doc_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> doc_data_offset);
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           #RELIGION_ VARCHAR(40), OTHER_DTL_ VARCHAR(200),CREATED_BY_ INT(11)
            $ID = $row -> ID;
            $DOC_TYPE = $row -> DOC_TYPE;
            $OTHER_DTL = $row -> OTHER_DTL;
			
            
            $ACTION = '../ui/ui_masters.php?Mode=ViewDoc&DocID=' . $ID . '#ReportsInfo';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteDoc&DocID=' . $ID . '#ReportsInfo';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> doc_record_num = $this -> doc_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td></tr>", $this -> doc_record_num, $ACTION, $DOC_TYPE, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }	
	function SelectSysDoc() {
        include ('code_connect.php');
        //ID_ INT(11)
        $sql = "CALL USP_GET_MST_DOC(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_doc_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();
            // $this -> client_id = $row -> ID;
            #$this -> masters_occupation_id = $row -> ID;
            $this -> masters_doc_doc = $row -> DOC_TYPE;
            $this -> masters_doc_other = $row -> OTHER_DTL;
        }
    }
	function InsertSysDoc() {
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_LAND(@LND_TYPE_, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @LND_TYPE_ = " . "'" . $this -> masters_land_land . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_land_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }
	function UpdateSysDoc() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_DOC(@ID_,@DOC_TYPE_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_doc_id);
        $mysqli -> query("SET @DOC_TYPE_ = " . "'" . $this -> masters_doc_doc . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_doc_other  . "'");
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
	function DeleteSysDoc() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_DOC(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_doc_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful (document Still In Use )");</script>';
        }
    }
	public function ReadPageParamsDoc() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_DOC_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> doc_num_rows = $result -> num_rows;
        $this -> doc_last_page = ceil($this -> doc_num_rows / $this -> doc_page_rows);
    }
    
    //=>read report
	#ID_  INT(11), REPORT_ VARCHAR(40), RPT_CODE_ VARCHAR(10), OTHER_DTL_ VARCHAR(200), UPDATED_BY_  INT(11)
	function LoadSysReport() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_RPT_LIMIT (@LIMIT_ , @OFFSET_)";
        $mysqli->query("set @LIMIT_ = ". $this-> report_page_rows);
        $mysqli->query("set @OFFSET_ = ". $this-> report_data_offset);
        $result = $mysqli -> query($sql);
		
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $REPORT = $row -> REPORT;
            $RPT_CODE = $row -> RPT_CODE;
            $ACTION = '../ui/ui_masters.php?Mode=ViewReport&ReportID=' . $ID . '#ReportsInfo';
            $DEL_URL = '../ui/ui_masters.php?Mode=DeleteReport&ReportID=' . $ID . '#ReportsInfo';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> report_record_num = $this -> report_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>", $this -> report_record_num, $ACTION, $LND_TYPE,$RPT_CODE, $DEL_ACTION);
        }
        $result -> free();
    }
	function SelectSysReport() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_MST_RPT(@ID_)";
        $mysqli->query("set @ID_ = ". $this-> masters_report_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            $this -> masters_report_report = $row -> REPORT;
            $this -> masters_report_code = $row -> RPT_CODE;
			$this -> masters_report_other = $row -> OTHER_DTL;
        }
    }
	function InsertSysReport() {
        include ('code_connect.php');
		$sql = "CALL USP_INS_MST_RPT(@REPORT_, @RPT_CODE, @OTHER_DTL_, @CREATED_BY_)";

        $mysqli -> query("SET @REPORT_ = " . "'" . $this -> masters_report_report . "'");
		$mysqli -> query("SET @RPT_CODE_ = " . "'" . $this -> masters_report_code . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_report_other . "'");
        $mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
			}
    }
	function UpdateSysReport() {
        include ('code_connect.php');
         $sql = "CALL USP_UPD_MST_RPT(@ID_,@REPORT_,@RPT_CODE_,@OTHER_DTL_,@UPDATED_BY_)";

        $mysqli -> query("SET @ID_ = " . $this -> masters_reprot_id);
        $mysqli -> query("SET @REPORT_ = " . "'" . $this -> masters_reprot_reprot . "'");
        $mysqli -> query("SET @RPT_CODE_ = " . "'" . $this -> masters_reprot_code  . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> masters_reprot_other  . "'");
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
	function DeleteSysReport() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_MST_RPT(@ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> masters_report_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful (report Still In Use )");</script>';
        }
    }
	public function ReadPageParamsRep() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_MST_RPT_ALL ()";
        $result = $mysqli -> query($sql);
        $this -> report_num_rows = $result -> num_rows;
        $this -> report_last_page = ceil($this -> report_num_rows / $this -> report_page_rows);
    }    
}




?>