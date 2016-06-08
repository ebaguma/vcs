<?Php

@$cat_id = $_GET['cat_id'];

/////// Update your database login details here /////
$dbhost_name = "192.168.7.3";
// Your host name
$database = "VCS_DB";
// Your database name
$username = "application";
// Your login userid
$password = "application";
// Your password
//////// End of database details of your server //////

//////// Do not Edit below /////////
try {
    $dbo = new PDO('mysql:host=' . $dbhost_name . ';dbname=' . $database, $username, $password);
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