<?php

	# Do not source format this code!

	@$element_id = $_GET['Element'];
	
	$dbhost_name = "192.168.7.3";
	$database = "VCS_DB";
	$username = "application";
	$password = "application";
	
	try {
		$dbo = new PDO('mysql:host=' . $dbhost_name . ';dbname=' . $database, $username, $password);
	} catch (PDOException $e) {
		print "Error!: " . $e -> getMessage() . "<br/>";
		die();
	}

	$sql = "CALL USP_GET_COUNTY(" . $element_id . ")";
	$row = $dbo -> prepare($sql);
	$row -> execute();
	$result = $row -> fetchAll(PDO::FETCH_ASSOC);
	
	$main = array('data' => $result);
	echo json_encode($main);
	
?>