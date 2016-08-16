<?Php

	# Do not source format this code!

	@$cat_id = $_GET['cat_id'];
	
	include ('code_config.php');
	
	try {
		$dbo = new PDO('mysql:host=' . $database_server . ';dbname=' . $database, $database_user, $database_pwd);
	} catch (PDOException $e) {
		print "Error!: " . $e -> getMessage() . "<br/>";
		die();
	}
	
	$sql = "CALL USP_GET_PROJ_FIN_SUBCATG (" . $cat_id . ")";
	$row = $dbo -> prepare($sql);
	$row -> execute();
	$result = $row -> fetchAll(PDO::FETCH_ASSOC);
	
	$main = array('data' => $result);
	echo json_encode($main);

?>