<?php

class PapDocPhoto {

	//read Expense Page Parameters
	public $pap_doc_page_rows = 10;
	public $pap_doc_last_page;
	public $pap_doc_data_offset;
	public $pap_doc_record_num;
	public $pap_doc_load_page;

	public $pap_doc_num_rows;

	/***** Doc Photo details ******
	 ID,PAP_ID,PROJ_ID,DOC_TYPE,DOC_TAG,FILE_NAME,FILE_PATH,UPLOAD_DEVICE,IS_DELETED,
	 UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE
	 ***********/
        public $id;    
	public $pap_id;
	public $proj_id;
	public $doc_type;
	public $doc_tag;
	public $file_name;
	public $file_path;
	public $created_by;
	
	public $pap_id_photo;

	function InsertDocPhoto() {
		# PAP_ID_, PROJ_ID_, DOC_TYPE_, DOC_TAG_, FILE_NAME_, FILE_PATH_, USER_ID_
		include ('code_connect.php');
		$sql = "CALL USP_INS_DOC_PHOTO( @PAP_ID_, @PROJ_ID_, @DOC_TYPE_, @DOC_TAG_, @FILE_NAME_, @FILE_PATH_, @USER_ID_ )";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_id);
		$mysqli -> query("SET @PROJ_ID_ = " . $this -> proj_id);
		$mysqli -> query("SET @DOC_TYPE_ = '" . $this -> doc_type . "'");
		$mysqli -> query("SET @DOC_TAG_ = '" . $this -> doc_tag . "'");
		$mysqli -> query("SET @FILE_NAME_ = '" . $this -> file_name . "'");
		$mysqli -> query("SET @FILE_PATH_ = '" . $this -> file_path . "'");
		$mysqli -> query("SET @USER_ID_ = " . $this -> created_by);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			echo '<script>alert("Data Updated Successfully");</script>';
		} else {
			echo '<script>alert("Update Not Successful");</script>';
		}
	}

	function findexts($filename) {
		$filename = strtolower($filename);
		$exts = split("[/\\.]", $filename);
		$n = count($exts) - 1;
		$exts = $exts[$n];
		return $exts;
	}

	function GetDocPhoto() {
		include ('code_connect.php');
                # PAP_ID_ INT, PROJ_ID_ INT, DOC_TYPE_ VARCHAR(100), DOC_TAG_ VARCHAR(100), OFFSET_ INT, LIMIT_ INT
		$sql = "CALL USP_GET_DOC_PHOTO_LIMIT (@PAP_ID_, @PROJ_ID_, @DOC_TYPE_, @DOC_TAG_, @OFFSET_, @LIMIT_)";
                $mysqli -> query("SET @PAP_ID_ = " . $this -> pap_id);
		$mysqli -> query("SET @PROJ_ID_ = " . $this -> proj_id);
		$mysqli -> query("SET @DOC_TYPE_ = '" . $this -> doc_type . "'");
		$mysqli -> query("SET @DOC_TAG_ = '" . $this -> doc_tag . "'");
                $mysqli -> query("SET @OFFSET_ = " . $this -> pap_doc_data_offset);
                $mysqli -> query("SET @LIMIT_ = " . $this -> pap_doc_page_rows);
                
		$result = $mysqli -> query($sql);   
                //iterate through the result set
                while ($row = $result -> fetch_object()) {
                        # ID,PAP_ID,PROJ_ID,DOC_TYPE,FILE_NAME,FILE_PATH
                        $ID = $row -> ID;
                        $PAP_ID = $row -> PAP_ID;
                        $PROJ_ID = $row -> PROJ_ID;
                        $DOC_TYPE = $row -> DOC_TYPE;
                        $FILE_NAME = $row -> FILE_NAME;
                        $FILE_PATH = $row -> FILE_PATH;

                        $doc_page = 1;
                        $ACTION = '../uploads/' . $FILE_PATH . '/' . $FILE_NAME;
                        $DEL_URL = '../ui/ui_doc.php?Mode=DeleteDoc&InitialMode=' . $_GET['Mode'] . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] ;
                        $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

                        $this -> pap_doc_record_num = $this -> pap_doc_record_num + 1;
                        printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> pap_doc_record_num, $ACTION, $FILE_NAME, $DOC_TYPE, $FILE_PATH, $DEL_ACTION);
                }
                //recuperate resources
                $result -> free();
                
		
	}

	function GetIDPhoto() {
		include ('code_connect.php');
		$sql = "CALL USP_GET_DOC_PHOTO (@PAP_ID_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_id);
		$results = $mysqli -> query($sql);

		//iterate through the result set
		if ($results) {
			# ID,PAP_ID,PROJ_ID,DOC_TYPE,FILE_NAME,FILE_PATH

			$row = $results -> fetch_object();
			
			if($results -> num_rows > 0){
			# $this -> pap_id = $row -> PAP_ID;
			$this -> proj_id = $row -> PROJ_ID;
			$this -> doc_type = $row -> DOC_TYPE;
			$this -> doc_tag = $row -> DOC_TAG;
			$this -> file_name = $row -> FILE_NAME;
			$this -> file_path = $row -> FILE_PATH;
			
			include_once('code_config.php');
			$document_dir = $main_doc_dir;
			$this->pap_id_photo = $document_dir . $this -> file_path . '/' . $this -> file_name;
			
			}else{
				$this->pap_id_photo = 1;
			}
		}
		
		
		
	}

	function DeleteIDPhoto() {
		# PAP_ID_, PROJ_ID_, DOC_TYPE_, DOC_TAG_, FILE_NAME_, FILE_PATH_, USER_ID_
		include ('code_connect.php');
		$sql = "CALL USP_DEL_ID_PHOTO( @PAP_ID_)";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			#echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Not Successful");</script>';
		}
	}
        
        function DeleteDocPhoto() {
		# PAP_ID_, PROJ_ID_, DOC_TYPE_, DOC_TAG_, FILE_NAME_, FILE_PATH_, USER_ID_
		include ('code_connect.php');
		$sql = "CALL USP_DEL_DOC_PHOTO( @ID_)";

		$mysqli -> query("SET @ID_ = " . $this -> id);

		$results = $mysqli -> query($sql);
		if ($results) {
			$row = $results -> fetch_object();
			#echo '<script>alert("Data Deleted Successfully");</script>';
		} else {
			echo '<script>alert("Delete Not Successful");</script>';
		}
	}

	function DocPhotoPageParams() {
		include 'code_connect.php';
                # PAP_ID_, PROJ_ID_, DOC_TYPE_, DOC_TAG_, OFFSET_, LIMIT_ 
		$sql = "CALL USP_GET_DOC_PHOTO_ALL (@PAP_ID_, @PROJ_ID_, @DOC_TYPE_, @DOC_TAG_)";
		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_id);
		$mysqli -> query("SET @PROJ_ID_ = " . $this -> proj_id);
		$mysqli -> query("SET @DOC_TYPE_ = '" . $this -> doc_type . "'");
		$mysqli -> query("SET @DOC_TAG_ = '" . $this -> doc_tag . "'");

		$result = $mysqli -> query($sql);
		$this -> pap_doc_record_num = $result -> num_rows;
		$this -> pap_doc_last_page = ceil($this -> pap_doc_num_rows / $this -> pap_doc_page_rows);
	}

}

?>
