<?php
class ProjectDetail {
    // Parameters to generate details
    public $project_id;

    // Project Details
    public $project_name;
    public $project_code;
    public $start_date;
    public $end_date;
    public $project_obj;
    public $project_desc;

    // Project Details

    function LoadProjectDetails() {

        $_SESSION['project_code'] = "";

        include ('code_connect.php');

        $sql = "CALL USP_GET_PROJECT_DTL(" . $this -> project_id . ")";
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

    function LoadProjectStaff() {

    }

    function ProjStaffPageParams() {

    }

}
?>