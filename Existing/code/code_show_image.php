<?php

$pap_id = $_GET['HHID'];
# (isset($_GET['HHID']) && is_numeric($_GET['HHID'])) ? intval($_GET['HHID']) : 0;
include_once ('code_pap_basic_info.php');

$get_pap_photo = new PapBasicInfo();
$get_pap_photo -> pap_hhid = $pap_id;

$get_pap_photo -> GetPapPhoto();
header('Content-Type: image/jpeg');
echo $get_pap_photo -> pap_photo;

?>