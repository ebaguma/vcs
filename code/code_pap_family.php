<?php

Class FamilyMember {

    //General parameters
	public $selected_project_id;
	public $selected_project_code;
	public $session_user_id;

	//read Expense Page Parameters
	public $fam_mbr_page_rows = 5;
	public $fam_mbr_last_page;
	public $fam_mbr_data_offset;
	public $fam_mbr_record_num;
	public $fam_mbr_load_page;

	public $fam_mbr_num_rows;

	# Expense Details
	# id, mbr_name, pap_id, rltn_id, dob, age, birth_place, sex, tribe_id, relgn_id,
	# other_dtl, is_deleted, updated_by, updated_date, created_by, created_date

	public $fam_mbr_id;
	public $fam_mbr_name;
	public $fam_mbr_pap_id;
	public $fam_mbr_rltn_id;
	public $fam_mbr_dob;
	public $fam_mbr_age;
	public $fam_mbr_birth_place;
	public $fam_mbr_sex;
	public $fam_mbr_tribe_id;
	public $fam_mbr_relgn_id;
        public $fam_mbr_edu_lev;
        public $fam_mbr_css;
        public $fam_mbr_non_att;
        public $fam_mbr_rdo;
	public $fam_mbr_other_dtl;
	
                

	function LoadFamilyMember() {
		include ('code_connect.php');
		// @PROJ_ID_, @LIMIT_, @OFFSET_
                
		$sql = "CALL USP_GET_PAP_FAMILY_LIMIT (@PAP_ID_, @OFFSET_, @LIMIT_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> fam_mbr_pap_id);
		$mysqli -> query("SET @OFFSET_ = " . $this -> fam_mbr_data_offset);
		$mysqli -> query("SET @LIMIT_ = " . $this -> fam_mbr_page_rows);

		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			# ID, MBR_NAME, PAP_ID, RLTN_ID, DOB, AGE, BIRTH_PLACE, SEX, TRIBE_ID, RELGN_ID, EDU_LEV, CSS, NON_ATT, RDO
			# OTHER_DTL, IS_DELETED

			$ID = $row -> ID;
			$MBR_NAME = $row -> MBR_NAME;
			$PAP_ID = $row -> PAP_ID;
			$RLTN_ID = $row -> RLTN_ID;
			$RELATION = $row->RELATION;
			$DOB = $row -> DOB;
			$AGE = $row -> AGE;
			$BIRTH_PLACE = $row -> BIRTH_PLACE;
			$SEX = $row -> SEX;
			$TRIBE_ID = $row -> TRIBE_ID;
			$RELGN_ID = $row -> RELGN_ID;
                        $EDU_LEV = $row -> EDU_LEV;
                        $CSS = $row -> CSS;
                        $NON_ATT = $row -> NON_ATT;
                        $RDO = $row -> RDO;
			$OTHER_DTL = $row->OTHER_DTL;
                        
			$confirm = "Are You Sure?";
			$ACTION = '../ui/ui_pap_info.php?Mode=ViewMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_URL = '../ui/ui_pap_info.php?Mode=DeleteMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $ID . '#PapFamily';
			$DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Member?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';
			
			$this -> fam_mbr_record_num = $this -> fam_mbr_record_num + 1;
			printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> fam_mbr_record_num, $ACTION, $MBR_NAME, $SEX, $AGE, $RELATION, $DEL_ACTION);
		}
		//recuperate resources
		$result -> free();
	}

	function SelectFamilyMember() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_FAMILY_MBR (@ID_)";
		$mysqli -> query("SET @ID_ = " . $this -> fam_mbr_id);
		$results = $mysqli -> query($sql);

		//iterate through the result set
		if ($results) {
			# ID, MBR_NAME, PAP_ID, RLTN_ID, DOB, AGE, BIRTH_PLACE, SEX, TRIBE_ID, RELGN_ID, EDU_LEV, CSS, NON_ATT, RDO,
			# OTHER_DTL, IS_DELETED, UPDATED_BY, UPDATED_DATE, CREATED_BY, CREATED_DATE

			$row = $results -> fetch_object();

			$this -> fam_mbr_id = $row -> ID;
			$this -> fam_mbr_name = $row -> MBR_NAME;
			$this -> fam_mbr_sex = $row -> SEX;
			$this -> fam_mbr_rltn_id = $row -> RLTN_ID;
			$this -> fam_mbr_dob = $row -> DOB;
			$this -> fam_mbr_birth_place = $row -> BIRTH_PLACE;
			$this -> fam_mbr_tribe_id = $row -> TRIBE_ID;
			$this -> fam_mbr_relgn_id = $row -> RELGN_ID;
                        $this -> fam_mbr_edu_lev = $row -> EDU_LEV;
                        $this -> fam_mbr_css = $row -> CSS;
                        $this -> fam_mbr_non_att = $row -> NON_ATT;
                        $this -> fam_mbr_rdo = $row -> RDO;

		}

	}

	function InsertFamilyMember() {
		include ('code_connect.php');
		$sql = "CALL USP_INS_PAP_FAMILY_MBR(@MBR_NAME_, @PAP_ID_, @RLTN_ID_, @DOB_, @BIRTH_PLACE_, @SEX_, @TRIBE_ID_, @RELGN_ID_, @EDU_LEV_, @CSS_, @NON_ATT_, @RDO_, @OTHER_DTL_, @CREATED_BY_)";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> fam_mbr_pap_id);
		$mysqli -> query("SET @MBR_NAME_ = " . "'" . $this -> fam_mbr_name . "'");
		$mysqli -> query("SET @RLTN_ID_ = " . $this -> fam_mbr_rltn_id );
		$mysqli -> query("SET @DOB_ = '" . DateTime::createFromFormat( 'd/m/Y', $this -> fam_mbr_dob ) -> format('Y-m-d') . "'" );
		$mysqli -> query("SET @BIRTH_PLACE_ = " . "'" . $this ->fam_mbr_birth_place . "'");
		$mysqli -> query("SET @SEX_ = " . "'" . $this -> fam_mbr_sex . "'");
		$mysqli -> query("SET @TRIBE_ID_ = " . $this -> fam_mbr_tribe_id );
		$mysqli -> query("SET @RELGN_ID_ = " . $this -> fam_mbr_relgn_id );
                $mysqli -> query("SET @EDU_LEV_ = " . "'" . $this -> fam_mbr_edu_lev . "'");
                $mysqli -> query("SET @CSS_ = " . "'" . $this -> fam_mbr_css . "'" );
                $mysqli -> query("SET @NON_ATT_ = " . "'" . $this -> fam_mbr_non_att ."'" );
                $mysqli -> query("SET @RDO_ = " . "'" . $this -> fam_mbr_rdo . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> fam_mbr_other_dtl . "'");
		$mysqli -> query("SET @CREATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Inserted Successfully");</script>';
		} else {
			echo '<script>alert("Insert Unsuccessful !");</script>';
		}
	}

	function UpdateFamilyMember() {
		include ('code_connect.php');
		$sql = "CALL USP_UPD_PAP_FAMILY_MBR(@ID_, @MBR_NAME_, @RLTN_ID_, @DOB_, @BIRTH_PLACE_, @SEX_, @TRIBE_ID_, @RELGN_ID_, @EDU_LEV_, @CSS_, @NON_ATT, @RDO_, @OTHER_DTL_, @UPDATED_BY_)";

		$mysqli -> query("SET @ID_ = " . $this -> fam_mbr_id);
		$mysqli -> query("SET @MBR_NAME_ = " . "'" . $this -> fam_mbr_name . "'");
		$mysqli -> query("SET @RLTN_ID_ = " . $this -> fam_mbr_rltn_id );
		$mysqli -> query("SET @DOB_ = '" . DateTime::createFromFormat('d/m/Y', $this -> fam_mbr_dob) -> format('Y-m-d') . "'");
		$mysqli -> query("SET @BIRTH_PLACE_ = " . "'" . $this ->fam_mbr_birth_place . "'");
		$mysqli -> query("SET @SEX_ = " . "'" . $this -> fam_mbr_sex . "'");
		$mysqli -> query("SET @TRIBE_ID_ = " . $this -> fam_mbr_tribe_id );
		$mysqli -> query("SET @RELGN_ID_ = " . $this -> fam_mbr_relgn_id );
                $mysqli -> query("SET @EDU_LEV_ = " . "'" . $this -> fam_mbr_edu_lev . "'");
                $mysqli -> query("SET @CSS_ = " . "'" . $this -> fam_mbr_css . "'");
                $mysqli -> query("SET @NON_ATT_ = " . "'" . $this -> fam_mbr_non_att . "'" );
                $mysqli -> query("SET @RDO_ = " . "'" . $this -> fam_mbr_rdo . "'");
		$mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> fam_mbr_other_dtl . "'");
		$mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Unsuccessful !");</script>';
		}
	}

	function DeleteFamilyMember() {
		include ('code_connect.php');
		$sql = "CALL USP_DEL_PAP_FAMILY_MBR(@ID_)";

		$mysqli -> query("SET @ID_ = " . $this -> fam_mbr_id);
		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
		}
	}

	function BindReligion() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_RELGN_DROP()";
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// RELIGION,OTHER_DTL,ID

			$ID = $row -> ID;
			$RELIGION = $row -> RELIGION;
			$OTHER_DTL = $row -> OTHER_DTL;

			if (session_status() == PHP_SESSION_NONE) { session_start();
				$Religion = $_SESSION['fam_mbr_relgn_id'];
			} else { $Religion = $_SESSION['fam_mbr_relgn_id'];
			}

			if (isset($Religion) && $Religion == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $RELIGION);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $RELIGION);
			}

		}
	}
	
	function BindRelation() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_RELATION_DROP()";
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// ID,TRIBE

			$ID = $row -> ID;
			$RELATION = $row -> RELATION;

			if (session_status() == PHP_SESSION_NONE) { session_start();
				$Relation = $_SESSION['fam_mbr_rltn_id'];
			} else { $Relation = $_SESSION['fam_mbr_rltn_id'];
			}

			if (isset($Relation) && $Relation == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $RELATION);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $RELATION);
			}

		}
	}
	
	function BindTribe() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_TRIBE_DROP()";
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// ID,TRIBE

			$ID = $row -> ID;
			$TRIBE = $row -> TRIBE;

			if (session_status() == PHP_SESSION_NONE) { session_start();
				$Tribe = $_SESSION['fam_mbr_tribe_id'];
			} else { $Tribe = $_SESSION['fam_mbr_tribe_id'];
			}

			if (isset($Tribe) && $Tribe == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $TRIBE);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $TRIBE);
			}

		}
	}

	function FamilyPageParams() {
		include 'code_connect.php';
		$sql = "CALL USP_GET_PAP_FAMILY_ALL (@PAP_ID_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> fam_mbr_pap_id);

		$result = $mysqli -> query($sql);
		$this -> fam_mbr_num_rows = $result -> num_rows;
		$this -> fam_mbr_last_page = ceil($this -> fam_mbr_num_rows / $this -> fam_mbr_page_rows);
	}

}
?>