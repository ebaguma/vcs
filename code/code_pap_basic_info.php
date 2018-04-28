<?php

class PapBasicInfo {
	//General parameters
	public $selected_project_id;
	public $selected_project_code;
	public $session_user_id;

	// PAP Details

	// HHID,PAP_NAME,DOB,SEX,PLOT_REF,REF_NO,IS_RESIDENT,BIRTH_PLACE,IS_MARRIED,ADDR_ID,TRIBE_ID,RELGN_ID,OCCUPN_ID,
	// PAP_STATUS_ID,DESIGN,PHOTO,PAP_TYPE,PROJ_ID,IS_DELETED,UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE

	public $pap_hhid;
        public $pap_id_no;
        Public $pap_status;
        Public $pap_type;
        Public $residence_status;
        public $pap_name;
	public $pap_dob;
	public $pap_sex;
	public $pap_plot_ref;
	public $pap_birth_place;
	public $pap_is_married;
	public $pap_tribe_id;
	public $pap_phone_no;
	public $pap_other_phone_no;
	public $pap_email;
	public $pap_religion_id;
	public $pap_occupation_id;
        public $pap_lit_lev;
	public $pap_status_id;
        public $pap_interviewer;
        public $pap_interview_date;

        // Photo Details

	public $photo_name;
	public $photo_path;
	public $photo_doc_type;
	public $photo_ext;
	public $photo_user = 1;

	function LoadBasicInfo() {

		$_SESSION['project_code'] = "";

		include ('code_connect.php');

		$sql = "CALL USP_GET_PAP_INFO_BASIC(@PROJ_ID_, @HHID_)";
		$mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
		$mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);

		$results = $mysqli -> query($sql);
		if ($results) {
			// HHID,PAP_NAME,DOB,SEX,PLOT_REF,REF_NO,IS_RESIDENT,BIRTH_PLACE,IS_MARRIED,ADDR_ID,TRIBE_ID,RELGN_ID,OCCUPN_ID,PAP_STATUS_ID,
			// DESIGN,PHOTO,PAP_TYPE,PROJ_ID,IS_DELETED,UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE

			$row = $results -> fetch_object();

			$this -> pap_hhid = $row -> HHID;
                        $this -> pap_id_no = $row -> ID_NO;
                        $this -> pap_status = $row -> PAP_STATUS;
                        $this -> pap_type = $row -> PAP_TYPE;
                        $this -> residence_status = $row -> RESIDENCE_STATUS;
			$this -> pap_name = $row -> PAP_NAME;
			$this -> pap_dob = $row -> DOB;
			$this -> pap_sex = $row -> SEX;
			$this -> pap_plot_ref = $row -> PLOT_REF;
			$this -> pap_birth_place = $row -> BIRTH_PLACE;
			$this -> pap_is_married = $row -> IS_MARRIED;
			$this -> pap_tribe_id = $row -> TRIBE_ID;
			$this -> pap_religion_id = $row -> RELGN_ID;
			$this -> pap_occupation_id = $row -> OCCUPN_ID;
                        $this -> pap_lit_lev = $row -> LIT_LEV;
			$this -> pap_status_id = $row -> PAP_STATUS_ID;
			$this -> pap_phone_no = $row -> PHONE_NO;
			$this -> pap_photo = $row -> PHOTO;
			$this -> pap_other_phone_no = $row -> OTHR_PHONE_NO;
			$this -> pap_email = $row -> EMAIL;
                        $this -> pap_interviewer = $row -> INTERVIEWER;
                        $this -> pap_interview_date = $row -> INTERVIEW_DATE;

		} else {
			echo '<script>alert("Not Done");</script>';
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
				$Religion = $_SESSION['pap_religion_id'];
			} else { $Religion = $_SESSION['pap_religion_id'];
			}

			if (isset($Religion) && $Religion == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $RELIGION);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $RELIGION);
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
				$Tribe = $_SESSION['pap_tribe_id'];
			} else { $Tribe = $_SESSION['pap_tribe_id'];
			}

			if (isset($Tribe) && $Tribe == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $TRIBE);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $TRIBE);
			}

		}
	}

	function BindOccupation() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_OCCUPN_DROP()";
		$result = $mysqli -> query($sql);

		//iterate through the result set
		while ($row = $result -> fetch_object()) {
			// ID,OCCUPN_NAME,OTHER_DTL,IS_DELETED
			$ID = $row -> ID;
			$OCCUPN_NAME = $row -> OCCUPN_NAME;
			$OTHER_DTL = $row -> OTHER_DTL;

			if (session_status() == PHP_SESSION_NONE) { session_start();
				$Occupation = $_SESSION['pap_occupation_id'];
			} else { $Occupation = $_SESSION['pap_occupation_id'];
			}

			if (isset($Occupation) && $Occupation == $ID) {
				printf("<option value='%s' selected >%s</option>", $ID, $OCCUPN_NAME);
			} else {
				printf("<option value='%s' >%s</option>", $ID, $OCCUPN_NAME);
			}

		}
	}

	function UpdatePapBasicInfo() {
		include ('code_connect.php');
		$sql = "CALL USP_UPD_PAP_INFO_BASIC(@HHID_,@ID_NO_,@PAP_STATUS_,@PAP_TYPE_,@RESIDENCE_STATUS_,@PAP_NAME_,@DOB_,@SEX_,@PLOT_REF_,@BIRTH_PLACE_,@IS_MARRIED_,@TRIBE_ID_,@RELGN_ID_,@OCCUPN_ID_,@LIT_LEV_, @PAP_STATUS_ID_, @PHONE_NO_, @OTHR_PHONE_NO_, @EMAIL_, @INTERVIEWER_, @INTERVIEW_DATE_, @UPDATED_BY_)";

		$mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);
                $mysqli -> query("SET @ID_NO_ = " . $this -> pap_id_no);
                $mysqli -> query("SET @PAP_STATUS_ = " . "'" . $this -> pap_status . "'");
                $mysqli -> query("SET @PAP_TYPE_ = " . "'" . $this -> pap_type . "'");
                $mysqli -> query("SET @RESIDENCE_STATUS_ = " . "'" . $this -> residence_status . "'");
		$mysqli -> query("SET @PAP_NAME_ = " . "'" . $this -> pap_name . "'");
		$mysqli -> query("SET @DOB_ = '" . DateTime::createFromFormat('d/m/Y', $this -> pap_dob) -> format('Y-m-d') . "'");
		$mysqli -> query("SET @SEX_ = " . "'" . $this -> pap_sex . "'");
		$mysqli -> query("SET @PLOT_REF_ = " . "'" . $this -> pap_plot_ref . "'");
		$mysqli -> query("SET @BIRTH_PLACE_ = " . "'" . $this -> pap_birth_place . "'");
		$mysqli -> query("SET @IS_MARRIED_ = " . "'" . $this -> pap_is_married . "'");
		$mysqli -> query("SET @TRIBE_ID_ = " . $this -> pap_tribe_id);
		$mysqli -> query("SET @RELGN_ID_ = " . $this -> pap_religion_id);
		$mysqli -> query("SET @OCCUPN_ID_ = " . $this -> pap_occupation_id);
                $mysqli -> query("SET @LIT_LEV_ = " . "'" . $this -> pap_lit_lev . "'");
		$mysqli -> query("SET @PAP_STATUS_ID_ = " . $this -> pap_status_id);
		$mysqli -> query("SET @PHONE_NO_ = " . $this -> pap_phone_no);
		$mysqli -> query("SET @OTHR_PHONE_NO_ = " . $this -> pap_other_phone_no);
		$mysqli -> query("SET @EMAIL_ = " . "'" . $this -> pap_email . "'");
                $mysqli -> query("SET @INTERVIEWER_ = " . "'" . $this -> pap_interviewer . "'");
//                $mysqli -> query("SET @INTERVIEWER_DATE_ = '" . DateTime::createFromFormat('d/m/Y', $this -> pap_interview_date) -> format('Y-m-d') . "'");
                $mysqli -> query("SET @INTERVIEW_DATE_ = '" . DateTime::createFromFormat('d/m/Y', $this -> pap_interview_date) -> format('Y-m-d') . "'");
		$mysqli -> query("SET @UPDATED_BY_ = " . $this -> session_user_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Not Successful");</script>';
		}
	}

	function UpdatePhoto() {
		# PAP_ID_, DOC_TYPE_, FILE_NAME_, FILE_PATH_, CONTENT_TYPE_, USER_ID_
		include ('code_connect.php');
		$sql = "CALL USP_INS_DOC_UPLOAD( @PAP_ID_, @DOC_TYPE_, @FILE_NAME_, @FILE_PATH_, @CONTENT_TYPE_, @USER_ID_ )";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_hhid);
		$mysqli -> query("SET @DOC_TYPE_ = " . 1);
		$mysqli -> query("SET @FILE_NAME_ = '" . $this -> photo_name . "'");
		$mysqli -> query("SET @FILE_PATH_ = '" . $this -> photo_path . "'");
		$mysqli -> query("SET @CONTENT_TYPE_ = '" . $this -> photo_ext . "'");
		$mysqli -> query("SET @USER_ID_ = " . 1);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Not Successful");</script>';
		}
	}

	function DeletePhoto() {

	}

	function GetPapPhoto() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_PAP_INFO_PHOTO( @HHID_ )";

		$mysqli -> query("SET @HHID_ = " . $this -> pap_hhid);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			$this -> pap_photo = $row -> PHOTO;
		}
	}

}
?>