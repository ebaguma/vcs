<?php

Class PapWelfare {

    //General parameters
	public $selected_project_id;
	public $selected_project_code;
	public $session_user_id;

	//read Expense Page Parameters
	public $pap_welf_rows = 5;
	public $pap_welf_last_page;
	public $pap_welf_data_offset;
	public $pap_welf_record_num;
	public $pap_welf_load_page;

	public $pap_welf_num_rows;

	# Expense Details
	# id, pap_id, asset_nsme, nbr_asset, asset_purpose,
	# other_dtl, is_deleted, updated_by, updated_date, created_by, created_date

	public $pap_welf_id;
	public $pap_welf_pap_id;
	public $pap_welf_asset_name;
	public $pap_welf_nbr_assets;
	public $pap_welf_asset_purpose;
        public $pap_welf_other_dtl;
	
	
                

	function LoadPapWelf() {
		include ('code_connect.php');
		// @PROJ_ID_, @LIMIT_, @OFFSET_
                
		$sql = "CALL USP_GET_PAP_WELF (@PAP_ID, @ASSET_NAME, @NBR_ASSETS, ASSET_PURPOSE)";
		$mysqli -> query("SET @PAP_ID = " . $this -> pap_welf_pap_id);
		$mysqli -> query("SET @ASSETS_NAME = " . $this -> pap_welf_asset_name);
		$mysqli -> query("SET @NBR_ASSETS = " . $this -> pap_welf_nbr_assets);
                $mysqli -> query("SET @ASSET_PURPOSE = " . $this -> pap_welf_asset_purpose);

		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			# ID, PAP_ID, ASSET_NAME,NBR_ASSETS,ASSET_PURPOSE,
			# OTHER_DTL, IS_DELETED

			$ID = $row -> ID;
			$PAP_ID = $row -> PAP_ID;
			$ASSET_NAME = $row -> ASSET_NAME;
			$NBR_ASSETS = $row -> NBR_ASSETS;
			$ASSET_PURPOSE = $row -> ASSET_PURPOSE;
			$OTHER_DTL = $row->OTHER_DTL;
                        
			$confirm = "Are You Sure?";
			$ACTION = '../ui/ui_pap_info.php?Mode=ViewMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_URL = '../ui/ui_pap_info.php?Mode=DeleteMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Member?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';
			
			$this -> pap_welf_record_num = $this -> pap_welf_record_num + 1;
			printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_welf_record_num, $ACTION, $ASSET_NAME, $NBR_ASSETS, $ASSET_PURPOSE,  $DEL_ACTION);
		}
		//recuperate resources
		$result -> free();
	}

	function SelectPapWelf() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_WELF (@ID_)";
		$mysqli -> query("SET @ID_ = " . $this -> pap_welf_id);
		$results = $mysqli -> query($sql);

		//iterate through the result set
		if ($results) {
			# ID, PAP_ID, ASSET_NAME,NBR_ASSETS,ASSET_PURPOSE,
			# OTHER_DTL, IS_DELETED, UPDATED_BY, UPDATED_DATE, CREATED_BY, CREATED_DATE

			$row = $results -> fetch_object();

			$this -> pap_welf_id = $row -> ID;
			$this -> pap_welf_asset_name = $row -> ASSET_NAME;
			$this -> pap_welf_nbr_assets = $row -> NBR_ASSETS;
			$this -> pap_welf_asset_purpose = $row -> ASSET_PURPOSE;
                        $this -> pap_welf_other_dtl = $row -> OTHER_DTL;
			

		}

	}

	function InsertPapWelf() {
		include ('code_connect.php');
		$sql = "CALL USP_INS_PAP_WELF(@PAP_ID_, @ASSET_NAME_, @NBR_ASSETS_, @ASSET_PURPOSE_, @OTHER_DTL_, @CREATED_BY_)";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_welf_pap_id);
		$mysqli -> query("SET @ASSET_NAME_ = " . "'" . $this -> pap_welf_asset_name . "'");
		$mysqli -> query("SET @NBR_ASSETS_ = " . $this -> pap_welf_nbr_assets );
		$mysqli -> query("SET @ASSET_PURPOSE_ = " . "'" . $this -> pap_welf_asset_purpose . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> pap_welf_other_dtl . "'");
		$mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Inserted Successfully");</script>';
		} else {
			echo '<script>alert("Insert Unsuccessful !");</script>';
		}
	}

	function UpdatePapWelf() {
		include ('code_connect.php');
		$sql = "CALL USP_UPD_PAP_WELF(@PAP_ID_, @ASSET_NAME_, @NBR_ASSETS_, @ASSET_PURPOSE_, @OTHER_DTL_, @UPDATED_BY_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_welf_id);
		$mysqli -> query("SET @ASSET_NAME_ = " . "'" . $this -> pap_welf_asset_name . "'");
		$mysqli -> query("SET @NBR_ASSETS_ = " . $this -> pap_welf_nbr_assets );
		$mysqli -> query("SET @ASSET_PURPOSE_ = " . "'" . $this -> pap_welf_asset_purpose . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> pap_welf_other_dtl . "'");
		$mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Unsuccessful !");</script>';
		}
	}

	function DeletePapWelf() {
		include ('code_connect.php');
		$sql = "CALL USP_DEL_PAP_WELF(@ID_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_welf_id);
		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
		}
	}

}

?>