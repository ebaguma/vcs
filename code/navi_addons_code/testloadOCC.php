<?php

class MastersBio {
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
    #public $select_project_id;
         
		 
	public function LoadBioOccupation() {

        include ('conn_class2.php');
        $sql = "CALL USP_GET_MST_OCCUPATION_ALL()";
        $result = $mysqli -> query($sql);


	while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $OCCUP_NAME = $row -> NAME;
            $OCCUP_OTHER = $row -> OTHER_DTL;

            $ACTION = '../ui/ui_masters_detail.php?Mode=Read&OccupationID=' . $ID . '&OccupationName=' . $OCCUP_NAME . '#Details';
		
            $this -> record_num = $this -> record_num + 1;
            printf("<br /><tr> <td>%s</td> <td><a href='%s'>%s</a></td>  <td>%s</td> </tr><br />",
			$this -> record_num, $ACTION, $OCCUP_NAME, $OCCUP_OTHER);
        }

      $result -> free();
    }
	
}


$projects = new MastersBio ();
$projects->LoadBioOccupation();
 




?>