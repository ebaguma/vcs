<?php

Class PapHealth {

    //General parameters
	public $selected_project_id;
	public $selected_project_code;
	public $session_user_id;

	//read Expense Page Parameters
	public $pap_health_rows = 5;
	public $pap_health_last_page;
	public $pap_health_data_offset;
	public $pap_health_record_num;
	public $pap_health_load_page;

	public $pap_health_num_rows;

	# Expense Details
	# id, pap_id, affected_no, health_first_aid, health_ctr,
	# other_dtl, is_deleted, updated_by, updated_date, created_by, created_date

	public $pap_health_id;
	public $pap_health_pap_id;
	public $pap_health_affected_no;
	public $pap_health_first_aid;
	public $pap_health_health_ctr;
	
	
                

	function LoadPapHealth() {
		include ('code_connect.php');
		// @PROJ_ID_, @LIMIT_, @OFFSET_
                
		$sql = "CALL USP_GET_PAP_HEALTH_LIMIT (@PAP_ID_, @OFFSET_, @LIMIT_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_health_pap_id);
		$mysqli -> query("SET @AFFECTED_NO_ = " . $this -> pap_health_affected_no);
		$mysqli -> query("SET @FIRST_AID_ = " . $this -> pap_health_first_aid);
                $mysqli -> query("SET @HEALTH_CTR_ = " . $this -> pap_health_health_ctr);

		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			# ID, PAP_ID, AFFECTED_NO,FIRST_AID,HEALTH_CTR,
			# OTHER_DTL, IS_DELETED

			$ID = $row -> ID;
			$PAP_ID = $row -> PAP_ID;
			$AFFECTED_NO = $row -> AFFECTED_NO;
			$FIRST_AID = $row -> FIRST_AID;
			$HEALTH_CTR = $row -> HEALTH_CTR;
			$OTHER_DTL = $row->OTHER_DTL;
                        
			$confirm = "Are You Sure?";
			$ACTION = '../ui/ui_pap_info.php?Mode=ViewMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_URL = '../ui/ui_pap_info.php?Mode=DeleteMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Member?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';
			
			$this -> pap_health_record_num = $this -> pap_health_record_num + 1;
			printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_health_record_num, $ACTION, $AFFECTED_NO, $FIRST_AID, $HEALTH_CTR,  $DEL_ACTION);
		}
		//recuperate resources
		$result -> free();
	}

	function SelectPapHealth() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_HEALTH (@ID_)";
		$mysqli -> query("SET @ID_ = " . $this -> pap_health_id);
		$results = $mysqli -> query($sql);

		//iterate through the result set
		if ($results) {
			# ID, PAP_ID, AFFECTED_NO,FIRST_AID,HEALTH_CTR,
			# OTHER_DTL, IS_DELETED, UPDATED_BY, UPDATED_DATE, CREATED_BY, CREATED_DATE

			$row = $results -> fetch_object();

			$this -> pap_health_id = $row -> ID;
			$this -> pap_health_affected_no = $row -> AFFECTED_NO;
			$this -> pap_health_first_aid = $row -> FIRST_AID;
			$this -> pap_health_health_ctr = $row -> HEALTH_CTR;
			

		}

	}

	function InsertPapHealth() {
		include ('code_connect.php');
		$sql = "CALL USP_INS_PAP_FAMILY_MBR(@AFFECTED_NO_, @FIRST_AID_, @HEALTH_CTR_, @OTHER_DTL_, @CREATED_BY_)";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_health_pap_id);
		$mysqli -> query("SET @AFFECTED_NO_ = " . "'" . $this -> pap_health_affected_no . "'");
		$mysqli -> query("SET @FIRST_AID_ = " . "'" . $this -> pap_health_first_aid . "'");
		$mysqli -> query("SET @HEALTH_CTR_ = " . "'" . $this -> pap_health_health_ctr . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> pap_health_other_dtl . "'");
		$mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Inserted Successfully");</script>';
		} else {
			echo '<script>alert("Insert Unsuccessful !");</script>';
		}
	}

	function UpdatePapHealth() {
		include ('code_connect.php');
		$sql = "CALL USP_UPD_PAP_HEALTH(@ID_, @AFFECTED_NO_, @FIRST_AID_, @HEALTH_CTR_, @OTHER_DTL_, @UPDATED_BY_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_health_id);
		$mysqli -> query("SET @AFFECTED_NO_ = " . "'" . $this -> pap_health_affected_no . "'");
		$mysqli -> query("SET @FIRST_AID_ = " . "'" . $this -> pap_health_first_aid . "'");
		$mysqli -> query("SET @HEALTH_CTR_ = " . "'" . $this -> pap_health_health_ctr . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> pap_health_other_dtl . "'");
		$mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Unsuccessful !");</script>';
		}
	}

	function DeletePapHealth() {
		include ('code_connect.php');
		$sql = "CALL USP_DEL_PAP_HEALTH(@ID_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_health_id);
		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
		}
	}

	


	function PapHealthPageParams() {
		include 'code_connect.php';
		$sql = "CALL USP_GET_PAP_HEALTH_ALL (@PAP_ID_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_health_pap_id);

		$result = $mysqli -> query($sql);
		$this -> pap_health_num_rows = $result -> num_rows;
		$this -> pap_health_last_page = ceil($this -> pap_health_num_rows / $this -> pap_health_page_rows);
	}

}
?>