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
        $mysqli -> query("SET @HHID_ = " . $this -> pap_hhid );
        
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            $this -> project_name = $row -> PROJ_NAME;
            $this -> project_code = $row -> PROJ_CODE;
            $this -> start_date = $row -> START_DATE;
            $this -> end_date = $row -> END_DATE;
            $this -> project_obj = $row -> PROJ_OBJ;
            $this -> project_desc = $row -> PROJ_DESC;
        } else {
            echo '<script>alert("Not Done");</script>';
        }

    }
    
    function BindReligion(){
        include ('code_connect.php');
        $sql = "CALL USP_GET_USER_ROLE_ALL()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $ROLE = $row -> ROLE;
            $OTHER_DTL = $row -> OTHER_DTL;
            
            if (isset($_GET['RoleID']) && $_GET['RoleID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $ROLE);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $ROLE);
            }

        }
    }
    
    function BindTribe(){
        include ('code_connect.php');
        $sql = "CALL USP_GET_PAP_TRIBE_DROP()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            // ID,TRIBE
            
            $ID = $row -> ID;
            $TRIBE = $row -> TRIBE;
            
            if (isset($_GET['RoleID']) && $_GET['RoleID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $ROLE);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $ROLE);
            }

        }
    }
    
    function BindOccupation(){
        include ('code_connect.php');
        $sql = "CALL USP_GET_USER_ROLE_ALL()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $ROLE = $row -> ROLE;
            $OTHER_DTL = $row -> OTHER_DTL;
            
            if (isset($_GET['RoleID']) && $_GET['RoleID'] == $ID) {
                printf("<option value='%s' selected >%s</option>", $ID, $ROLE);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $ROLE);
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