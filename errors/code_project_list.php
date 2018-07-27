<?php

class ProjectList {
    //geeneral parameters
    public $read_num_rows;

    //read pagination parameters
    public $page_rows = 5;
    public $read_last_page;
    public $data_offset;
    public $record_num;
    public $load_page;

    //search pagination parameters
    public $search_last_page;
    public $search_record_num;
    public $search_num_rows;
    public $search_load_page;
    public $search_data_offset;
    public $search_proj_name;
    public $search_proj_code;

    //session variables to keep
    public $select_project_id;

    public function LoadProjects() {

        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJECTS_LIMIT('" . $this -> page_rows . "','" . $this -> data_offset . "')";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJECTNAME = $row -> PROJ_NAME;
            $PROJECTCODE = $row -> PROJ_CODE;
            $DISPLAYNAME = $row -> DISP_NAME;
            $PROJECTSTART = $row -> START_DATE;
            $PROJECTEND = $row -> END_DATE;
                        $ACTION = '../ui/ui_project_detail.php?Mode=Read&Select=true&ProjectID=' . $ID . '&ProjectCode=' . $PROJECTCODE ;
            $this -> record_num = $this -> record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> record_num, $ACTION, $PROJECTNAME, $PROJECTCODE, $DISPLAYNAME, $PROJECTSTART, $PROJECTEND);
        
            
        }
           
        //recuperate resources
        
        $result -> free();
        
    }
    

    public function SearchProjects() {

        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJECTS_LIMIT_SEARCH('" . $this -> page_rows . "','" . $this -> search_data_offset . "','" . $this -> search_proj_name . "','" . $this -> search_proj_code . "')";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJECTNAME = $row -> PROJ_NAME;
            $PROJECTCODE = $row -> PROJ_CODE;
            $DISPLAYNAME = $row -> DISP_NAME;
            $PROJECTSTART = $row -> START_DATE;
            $PROJECTEND = $row -> END_DATE;
           $ACTION = '../ui/ui_home.php?Mode=Read&ProjectID=' . $ID . '&ProjectCode=' . $PROJECTCODE ;
            $this -> search_record_num = $this -> search_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> search_record_num, $ACTION, $PROJECTNAME, $PROJECTCODE, $DISPLAYNAME, $PROJECTSTART, $PROJECTEND);
        }

        //recuperate resources
        $result -> free();
    }

    public function SelectProject() {

    }

    public function ReadPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJECTS_ALL()";
        $result = $mysqli -> query($sql);
        $this -> read_num_rows = $result -> num_rows;
        $this -> read_last_page = ceil($this -> read_num_rows / $this -> page_rows);
    }

    public function SearchPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJECTS_ALL_SEARCH('" . $this -> search_proj_name . "','" . $this -> search_proj_code . "')";
        $result = $mysqli -> query($sql);
        $this -> search_num_rows = $result -> num_rows;
        $this -> search_last_page = ceil($this -> search_num_rows / $this -> page_rows);
    }

}
?>