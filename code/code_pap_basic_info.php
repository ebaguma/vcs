<?php

class PapBasicInfo {
    //General parameters
    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    // Project Details

    // HHID,PAP_NAME,DOB,SEX,PLOT_REF,REF_NO,IS_RESIDENT,BIRTH_PLACE,IS_MARRIED,ADDR_ID,TRIBE_ID,RELGN_ID,OCCUPN_ID,
    // PAP_STATUS_ID,DESIGN,PHOTO,PAP_TYPE,PROJ_ID,IS_DELETED,UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE

    public $pap_hhid;
    public $pap_name;
    public $pap_dob;
    public $pap_sex;
    public $pap_plot_ref;
    public $pap_birth_place;
    public $pap_is_married;
    public $pap_address_id;
    public $pap_tribe_id;
    public $pap_religion_id;
    public $pap_occupation_id;
    public $pap_status_id;

    // Project Details

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
            $this -> pap_name = $row -> PAP_NAME;
            $this -> pap_dob = $row -> DOB;
            $this -> pap_sex = $row -> SEX;
            $this -> pap_plot_ref = $row -> PLOT_REF;
            $this -> pap_birth_place = $row -> BIRTH_PLACE;
            $this -> pap_is_married = $row -> IS_MARRIED;
            $this -> pap_tribe_id = $row -> TRIBE_ID;
            $this -> pap_religion_id = $row -> RELGN_ID;
            $this -> pap_occupation_id = $row -> OCCUPN_ID;
            $this -> pap_status_id = $row -> PAP_STATUS_ID;

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

            if (isset($_GET['ReligionID']) && $_GET['ReligionID'] == $ID) {
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

            if (isset($_GET['TribeID']) && $_GET['TribeID'] == $ID) {
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

            if (isset($_GET['OccupnID']) && $_GET['OccupnID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $OCCUPN_NAME);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $OCCUPN_NAME);
            }

        }
    }

    function UpdateProjectDetails() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PROJECT_DTL(@project_id,@project_name,@project_code,@project_obj,@start_date,@end_date,@project_desc)";

        $mysqli -> query("SET @project_id = " . $this -> project_id);
        $mysqli -> query("SET @project_name = " . "'" . $this -> project_name . "'");
        $mysqli -> query("SET @project_code = " . "'" . $this -> project_code . "'");
        $mysqli -> query("SET @project_obj = " . "'" . $this -> project_obj . "'");

        $mysqli -> query("SET @start_date = '" . DateTime::createFromFormat('d/m/Y', $this -> start_date) -> format('Y-m-d') . "'");
        $mysqli -> query("SET @end_date = '" . DateTime::createFromFormat('d/m/Y', $this -> end_date) -> format('Y-m-d') . "'");

        $mysqli -> query("SET @project_desc = " . "'" . $this -> project_desc . "'");

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }

}
?>