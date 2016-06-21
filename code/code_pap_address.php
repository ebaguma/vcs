<?php

Class PapAddress {

	//General parameters
	public $selected_project_id;
	public $selected_project_code;
	public $session_user_id;

	//read Expense Page Parameters
	public $pap_addr_page_rows = 5;
	public $pap_addr_last_page;
	public $pap_addr_data_offset;
	public $pap_addr_record_num;
	public $pap_addr_load_page;

	public $pap_addr_num_rows;

	# Expense Details
	# id,pap_id,road,is_resident,vill_id,subcty_id,cty_id,dist_id

	public $pap_addr_id;
	public $pap_addr_pap_id;
	public $pap_addr_road;
	public $pap_addr_is_resident;
	public $pap_addr_vill_id;
	public $pap_addr_subcty_id;
	public $pap_addr_cty_id;
	public $pap_addr_dist_id;

	function LoadPapAddr() {
		include ('code_connect.php');
		// @PROJ_ID_, @LIMIT_, @OFFSET_
		$sql = "CALL USP_GET_PAP_ADDR_LIMIT (@PAP_ID_, @OFFSET_, @LIMIT_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_addr_pap_id);
		$mysqli -> query("SET @OFFSET_ = " . $this -> pap_addr_data_offset);
		$mysqli -> query("SET @LIMIT_ = " . $this -> pap_addr_page_rows);

		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			# ID,PAP_ID,ROAD,IS_RESIDENT,VILL_ID,VILLAGE,SUBCTY_ID,CTY_ID,DIST_ID,DISTRICT

			$ID = $row -> ID;
			$PAP_ID = $row -> PAP_ID;
			$ROAD = $row -> ROAD;
			$IS_RESIDENT = $row -> IS_RESIDENT;
			$VILL_ID = $row -> VILL_ID;
			$VILLAGE = $row -> VILLAGE;
			$SUBCTY_ID = $row -> SUBCTY_ID;
			$CTY_ID = $row -> CTY_ID;
			$DIST_ID = $row -> DIST_ID;
			$DISTRICT = $row -> DISTRICT;

			$proj_sect_page = 1;

			$confirm = "Are You Sure?";
			$ACTION = '../ui/ui_pap_info.php?Mode=ViewAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&AddrID=' . $ID . '#PapAddress';
			$DEL_URL = '../ui/ui_pap_info.php?Mode=DeleteAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&AddrID=' . $ID . '#PapAddress';
			$DEL_ACTION = '<a href="' . $DEL_URL . '" ><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';
			$ADDR_STR = $ROAD . ', ' . $DISTRICT . ', ' . $VILLAGE;

			$this -> pap_addr_record_num = $this -> pap_addr_record_num + 1;
			printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_addr_record_num, $ACTION, $ROAD, $VILLAGE, $DISTRICT, $DEL_ACTION);
		}
		//recuperate resources
		$result -> free();
	}

	function SelectPapAddr() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_ADDR (@ID_)";
		$mysqli -> query("SET @ID_ = " . $this -> pap_addr_id);
		$results = $mysqli -> query($sql);

		//iterate through the result set
		if ($results) {
			# ID,PAP_ID,ROAD,IS_RESIDENT,VILL_ID,VILLAGE,SUBCTY_ID,CTY_ID,DIST_ID,DISTRICT

			$row = $results -> fetch_object();

			$this -> pap_addr_id = $row -> ID;
			$this -> pap_addr_road = $row -> ROAD;
			$this -> pap_addr_is_resident = $row -> IS_RESIDENT;
			$this -> pap_addr_dist_id = $row -> DIST_ID;
			$this -> pap_addr_cty_id = $row -> CTY_ID;
			$this -> pap_addr_subcty_id = $row -> SUBCTY_ID;
			$this -> pap_addr_vill_id = $row -> VILL_ID;

		}

	}

	function InsertPapAddr() {
		include ('code_connect.php');
		$sql = "CALL USP_INS_PAP_ADDR(@PAP_ID_, @ROAD_, @IS_RESIDENT_, @VILL_ID_, @CREATED_BY_)";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_addr_pap_id);
		$mysqli -> query("SET @ROAD_ = " . "'" . $this -> pap_addr_road . "'");
		# $mysqli -> query("SET @IS_RESIDENT_ = " . $this -> selected_project_id);
		$mysqli -> query("SET @VILL_ID_ = " . $this -> pap_addr_vill_id);
		$mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Inserted Successfully");</script>';
		} else {
			echo '<script>alert("Insert Unsuccessful !");</script>';
		}
	}

	function UpdatePapAddr() {
		include ('code_connect.php');
		$sql = "CALL USP_UPD_PAP_ADDR(@ID_, @ROAD_, @IS_RESIDENT_, @VILL_ID_, @UPDATED_BY_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_addr_id);
		$mysqli -> query("SET @ROAD_ = " . "'" . $this -> pap_addr_road . "'");
		# $mysqli -> query("SET @IS_RESIDENT_ = " . $this -> proj_sect_length);
		$mysqli -> query("SET @VILL_ID_ = " . $this -> pap_addr_vill_id);
		$mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Unsuccessful !");</script>';
		}
	}

	function DeletePapAddr() {
		include ('code_connect.php');
		$sql = "CALL USP_DEL_PAP_ADDR(@ID_)";

		$mysqli -> query("SET @ID_ = " . $this -> pap_addr_id);
		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
		}
	}

	function BindDistrict() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_DISTRICT()";
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			# ID,DISTRICT,

			$ID = $row -> ID;
			$DISTRICT = $row -> DISTRICT;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$District = $_SESSION['pap_addr_dist_id'];
			} else {
				$District = $_SESSION['pap_addr_dist_id'];

			}
			

			if (isset($District) && $District == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $DISTRICT);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $DISTRICT);
			}

		}
	}

	function BindCounties() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_COUNTY(@DIST_ID_ )";
		$mysqli -> query("SET @DIST_ID_ = " . $this -> pap_addr_dist_id);
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// ID,COUNTY

			$ID = $row -> ID;
			$COUNTY = $row -> COUNTY;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$County = $_SESSION['pap_addr_cty_id'];
			} else {
				$County = $_SESSION['pap_addr_cty_id'];

			}

			if (isset($County) && $County == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $COUNTY);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $COUNTY);
			}

		}
	}

	function BindSubCounties() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_SUBCOUNTY(@CTY_ID_)";
		$mysqli -> query("SET @CTY_ID_ = " . $this -> pap_addr_cty_id);
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// ID,SUBCOUNTY

			$ID = $row -> ID;
			$SUBCOUNTY = $row -> SUBCOUNTY;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$SubCounty = $_SESSION['pap_addr_subcty_id'];
			} else {
				$SubCounty = $_SESSION['pap_addr_subcty_id'];

			}

			if (isset($SubCounty) && $SubCounty == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $SUBCOUNTY);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $SUBCOUNTY);
			}

		}
	}

	function BindVillage() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_VILLAGE(@SUBCTY_ID_)";
		$mysqli -> query("SET @SUBCTY_ID_ = " . $this -> pap_addr_subcty_id);
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// ID,VILLAGE

			$ID = $row -> ID;
			$VILLAGE = $row -> VILLAGE;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$Village = $_SESSION['pap_addr_vill_id'];
			} else {
				$Village = $_SESSION['pap_addr_vill_id'];

			}

			if (isset($Village) && $Village == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $VILLAGE);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $VILLAGE);
			}

		}
	}

	function PapAddrPageParams() {
		include 'code_connect.php';
		$sql = "CALL USP_GET_PAP_ADDR_ALL (@PAP_ID_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_addr_pap_id);

		$result = $mysqli -> query($sql);
		$this -> pap_addr_num_rows = $result -> num_rows;
		$this -> pap_addr_last_page = ceil($this -> pap_addr_num_rows / $this -> pap_addr_page_rows);
	}

}
?>