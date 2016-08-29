<?php

class PapDocPhoto {
	
	//read Expense Page Parameters
	public $pap_doc_page_rows = 5;
	public $pap_doc_last_page;
	public $pap_doc_data_offset;
	public $pap_doc_record_num;
	public $pap_doc_load_page;

	public $pap_doc_num_rows;
	
	
	/***** Doc Photo details ****** 
	 ID,PAP_ID,PROJ_ID,DOC_TYPE,DOC_TAG,FILE_NAME,FILE_PATH,UPLOAD_DEVICE,IS_DELETED,
	 UPDATED_BY,UPDATED_DATE,CREATED_BY,CREATED_DATE
	 ***********/
	
	public $pap_id;
	public $proj_id;
	public $doc_type;
	public $doc_tag;
	public $file_name;
	public $file_path;
	public $created_by;

	

	function InsertDocPhoto() {
		# PAP_ID_, PROJ_ID_, DOC_TYPE_, DOC_TAG_, FILE_NAME_, FILE_PATH_, USER_ID_
		include ('code_connect.php');
		$sql = "CALL USP_INS_DOC_PHOTO( @PAP_ID_, @PROJ_ID_, @DOC_TYPE_, @DOC_TAG_, @FILE_NAME_, @FILE_PATH_, @USER_ID_ )";

		$mysqli -> query("SET @PAP_ID_ = " . $this -> pap_id);
		$mysqli -> query("SET @PROJ_ID_ = " . $this -> proj_id);
		$mysqli -> query("SET @DOC_TYPE_ = '" . $this->doc_type . "'");
		$mysqli -> query("SET @DOC_TAG_ = '" . $this->doc_tag . "'");
		$mysqli -> query("SET @FILE_NAME_ = '" . $this -> file_name . "'");
		$mysqli -> query("SET @FILE_PATH_ = '" . $this -> file_path . "'");
		$mysqli -> query("SET @USER_ID_ = " . $this->created_by);

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

	}
	
	function GetIDPhoto(){
		
	}

	function DeleteDocPhoto() {

	}

	function DocPhotoPageParams() {

	}

}
?>
