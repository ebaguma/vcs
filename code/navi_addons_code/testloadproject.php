<?php

class ProjectList {
    //geeneral parameters
    public $read_num_rows;

    //read pagination parameters
    public $page_rows = 10;
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

        include ('conn_class2.php');
        $sql = "CALL USP_GET_PROJECTS_ALL()";
        $result = $mysqli -> query($sql);


	while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJECTNAME = $row -> PROJ_NAME;
            $PROJECTCODE = $row -> PROJ_CODE;
            $DISPLAYNAME = $row -> DISP_NAME;
            #$PROJECTSTART = $row -> START_DATE;
            #$PROJECTEND = $row -> END_DATE;

            $ACTION = '../ui/ui_project_detail.php?Mode=Read&ProjectID=' . $ID . '&ProjectCode=' . $PROJECTCODE . '#Details';
			//this page counts how many rows are been displayed
            $this -> record_num = $this -> record_num + 1;
			
			//this part is printing out the table 
            printf("<br /><tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td> <td>%s</td></tr><br />",
			$this -> record_num, $ACTION, $PROJECTNAME, $PROJECTCODE, $DISPLAYNAME);
        }

        //iterate through the result set
       /* while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJECTNAME = $row -> PROJ_NAME;
            $PROJECTCODE = $row -> PROJ_CODE;
            $DISPLAYNAME = $row -> DISP_NAME;
            $PROJECTSTART = $row -> START_DATE;
            $PROJECTEND = $row -> END_DATE;

            $ACTION = '../ui/ui_project_detail.php?Mode=Read&ProjectID=' . $ID . '&ProjectCode=' . $PROJECTCODE . '#Details';
            $this -> record_num = $this -> record_num + 1;
			
			//this part is printing out the table 
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> record_num, $ACTION, $PROJECTNAME, $PROJECTCODE, $DISPLAYNAME, $PROJECTSTART, $PROJECTEND);
        }*/

        //recuperate resources
        $result -> free();
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*public function ReadPageParams() {
        include ('conn_class2.php');
        $sql = "CALL USP_GET_PROJECTS_ALL()";
        $result = $mysqli -> query($sql);
        $this -> read_num_rows = $result -> num_rows;
        $this -> read_last_page = ceil($this -> read_num_rows / $this -> page_rows);
    }*/
	
}

#function ReadProject(){
	
$projects = new ProjectList();


$projects->LoadProjects();
 
#}

#$dispaly = new ReadProject();
#$dispaly->ReadProject();








?>