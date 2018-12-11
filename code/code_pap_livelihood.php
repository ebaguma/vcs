<?php

Class PapHealth {

    //General parameters
	public $selected_project_id;
	public $selected_project_code;
	public $session_user_id;

	//read Expense Page Parameters
	public $pap_live_rows = 5;
	public $pap_live_last_page;
	public $pap_live_data_offset;
	public $pap_live_record_num;
	public $pap_live_load_page;

	public $pap_live_num_rows;

	# Expense Details
	# id, pap_id, activity_name, income_cycle, cycle,
	# other_dtl, is_deleted, updated_by, updated_date, created_by, created_date

	public $pap_live_id;
	public $pap_live_pap_id;
	public $pap_live_activity_name;
	public $pap_live_income_cycle;
	public $pap_live_cycle;
	
	
                

	function LoadPapLive() {
		include ('code_connect.php');
		// @PROJ_ID_, @LIMIT_, @OFFSET_
                
		$sql = "CALL USP_GET_PAP_LIVE_LIMIT (@PAP_ID_, @ACTIVITY_NAME_, @INCOME_CYCLE_, @CYCLE_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_live_pap_id);
		$mysqli -> query("SET @ACTIVITY_NAME_ = " . $this -> pap_live_activity_name);
		$mysqli -> query("SET @INCOME_CYCLE_ = " . $this -> pap_live_income_cycle);
                $mysqli -> query("SET @CYCLE_ = " . $this -> pap_live_cycle);

		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			# ID, PAP_ID, ACTIVITY_NAME,INCOME_CYCLE,CYCLE,
			# OTHER_DTL, IS_DELETED

			$ID = $row -> ID;
			$PAP_ID = $row -> PAP_ID;
			$ACTIVITY_NAME = $row -> ACTIVITY_NAME;
			$INCOME_CYCLE = $row -> INCOME_CYCLE;
			$CYCLE = $row -> CYCLE;
			$OTHER_DTL = $row->OTHER_DTL;
                        
			$confirm = "Are You Sure?";
			$ACTION = '../ui/ui_pap_info.php?Mode=ViewMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_URL = '../ui/ui_pap_info.php?Mode=DeleteMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Member?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';
			
			$this -> pap_live_record_num = $this -> pap_live_record_num + 1;
			printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_live_record_num, $ACTION, $ACTIVITY_NAME, $INCOME_CYCLE, $CYCLE,  $DEL_ACTION);
		}
		//recuperate resources
		$result -> free();
	}

	function SelectPapLive() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_LIVE (@ID_)";
		$mysqli -> query("SET @ID_ = " . $this -> pap_live_id);
		$results = $mysqli -> query($sql);

		//iterate through the result set
		if ($results) {
			# ID, PAP_ID, ACTIVITY_NAME,INCOME_CYCLE,CYCLE,
			# OTHER_DTL, IS_DELETED, UPDATED_BY, UPDATED_DATE, CREATED_BY, CREATED_DATE

			$row = $results -> fetch_object();

			$this -> pap_live_id = $row -> ID;
			$this -> pap_live_activity_name = $row -> ACTIVITY_NAME;
			$this -> pap_live_income_cycle = $row -> INCOME_CYCLE;
			$this -> pap_live_cycle = $row -> CYCLE;
			

		}

	}

	function InsertPapLive() {
		include ('code_connect.php');
		$sql = "CALL USP_INS_PAP_LIVE(@ACTIVITY_NAME_, @INCOME_CYCLE_, @CYCLE_, @OTHER_DTL_, @CREATED_BY_)";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_live_pap_id);
		$mysqli -> query("SET @ACTIVITY_NAME_ = " . "'" . $this -> pap_live_activity_name . "'");
		$mysqli -> query("SET @INCOME_CYCLE_ = " . "'" . $this -> pap_live_income_cycle . "'");
		$mysqli -> query("SET @CYCLE_ = " . "'" . $this -> pap_live_cycle . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> pap_live_other_dtl . "'");
		$mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Inserted Successfully");</script>';
		} else {
			echo '<script>alert("Insert Unsuccessful !");</script>';
		}
	}

	function UpdatePapLive() {
		include ('code_connect.php');
		$sql = "CALL USP_UPD_PAP_LIVE(@ID_, @ACTIVITY_NAME_, @INCOME_CYCLE_, @CYCLE_, @OTHER_DTL_, @UPDATED_BY_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_live_id);
		$mysqli -> query("SET @ACTIVITY_NAME_ = " . "'" . $this -> pap_live_activity_name . "'");
		$mysqli -> query("SET @INCOME_CYCLE_ = " . "'" . $this -> pap_live_income_cycle . "'");
		$mysqli -> query("SET @CYCLE_ = " . "'" . $this -> pap_live_cycle . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> pap_live_other_dtl . "'");
		$mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Unsuccessful !");</script>';
		}
	}

	function DeletePapLive() {
		include ('code_connect.php');
		$sql = "CALL USP_DEL_PAP_LIVE(@ID_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_live_id);
		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
		}
	}

	


	function PapLivePageParams() {
		include 'code_connect.php';
		$sql = "CALL USP_GET_PAP_LIVE_ALL (@PAP_ID_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_live_pap_id);

		$result = $mysqli -> query($sql);
		$this -> pap_live_num_rows = $result -> num_rows;
		$this -> pap_live_last_page = ceil($this -> pap_live_num_rows / $this -> pap_live_page_rows);
	}

}
?>