<! doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="Me" content="Masters">
		<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Masters</title>


<?php

ob_start(NULL, 0, PHP_OUTPUT_HANDLER_CLEANABLE);
        
include ('ui_header.php');

if (isset($_GET['Mode']) && $_GET['Mode'] == 'Read') {
	LoadBioOccupation();
    LoadFamRelations();
    LoadFamTribe();
    LoadFamReligion();
    LoadValCrop();
    LoadValLand();
    LoadProjDispute();
    LoadSysDoc();
    LoadSysReport();
}

if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewOccupation') {SelectBioOccupation();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewRelations')
{SelectFamRelations();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewTribe')      
{SelectFamTribe();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewReligion')
{SelectFamReligion();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewCrop') 
{SelectValCrop();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewLand') 
{SelectValLand();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewDispute') 
{SelectProjDispute();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewDoc') 
{SelectSysDoc();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'ViewReport') 
{SelectSysReport();}

if (isset($_GET['Mode']) && $_GET['Mode'] == 'EditOccupation') {UpdateBioOccupation();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditRelations')
{UpdateFamRelations();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditTribe')      
{UpdateFamTribe();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditReligion')
{UpdateFamReligion();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditCrop') 
{UpdateValCrop();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditLand') 
{UpdateValLand();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditDispute') 
{UpdateProjDispute();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditDoc') 
{UpdateSysDoc();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'EditReport') 
{UpdateSysReport();}

if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertOccupation') {InsertBioOccupation();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertRelations')
{InsertFamRelations();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertTribe')      
{InsertFamTribe();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertReligion')
{InsertFamReligion();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertCrop') 
{InsertValCrop();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertLand') 
{InsertValLand();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertDispute') 
{InsertProjDispute();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertDoc') 
{InsertSysDoc();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'InsertReport') 
{InsertSysReport();}

if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteOccupation') {DeleteBioOccupation();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteRelations')
{DeleteFamRelations();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteTribe')      
{DeleteFamTribe();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteReligion')
{DeleteFamReligion();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteCrop') 
{DeleteValCrop();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteLand') 
{DeleteValLand();} 
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteDispute') 
{DeleteProjDispute();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteDoc') 
{DeleteSysDoc();}
elseif(isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteReport') 
{DeleteSysReport();}

        
        function CheckReturnUser() {
            $time = $_SERVER['REQUEST_TIME'];

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }//this function haasn't changed
		}
        
        function LogOut() {
            include_once ('../code/code_index.php');

            $logout = new LogInOut();
            // session_start();
            if (isset($_SESSION['session_user_id'])) {
                $logout -> user_id = $_SESSION['session_user_id'];
            } else {
                $logout -> user_id = $_COOKIE["last_user"];
            }
            $logout -> LogOff();
        }
            
        
        
#::Bio::# 
      
        function LoadBioOccupation() {
            include_once ('../code/code_masters_BioID.php');
            $LoadOccupation = new MastersBio();
           #$project_clients -> select_project_id = $_GET["ProjectID"];

            if (isset($_GET['occupnPage'])) {
                $GLOBALS['occupn_load_page'] = $_GET['occupnPage'];
            } else {
                $GLOBALS['occupn_load_page'] = 1;
            }

            //set pagination parameters
             $LoadOccupation -> ReadPageParams();
            $GLOBALS['num_pages'] = $LoadOccupation  -> occupn_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['occupn_load_page'] == 1) {
                 $LoadOccupation -> occupn_record_num = 0;
                 $LoadOccupation -> occupn_data_offset = 0;
            } else if ($GLOBALS['occupn_load_page'] <=  $LoadOccupation -> occupn_last_page) {
                 $LoadOccupation -> occupn_data_offset = ($GLOBALS['occupn_load_page'] - 1) *  $LoadOccupation -> occupn_page_rows;
                 $LoadOccupation -> occupn_record_num = ($GLOBALS['occupn_load_page'] - 1) *  $LoadOccupation -> occupn_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['occupn_load_page'] = 1;
                 $LoadOccupation -> occupn_record_num = 0;
                 $LoadOccupation -> occupn_data_offset = 0;
            }

            if (($GLOBALS['occupn_load_page'] + 1) <=  $LoadOccupation -> occupn_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['occupn_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['occupn_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['occupn_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadOccupation -> LoadBioOccupation();
        } 
        function SelectBioOccupation() {
           
            include_once ('../code/code_masters_BioID.php');
            $SelectOccupation = new MastersBio();
            $SelectOccupation  -> masters_occupation_id = $_GET["OccupationID"];
            $SelectOccupation  -> SelectBioOccupation();
            //$client_name = $project_client -> client_name;
            $GLOBALS['masters_occupation_id'] = $SelectOccupation -> masters_occupation_id;
            $GLOBALS['masters_occupation_name'] = $SelectOccupation -> masters_occupation_name;
            $GLOBALS['masters_occupation_other'] = $SelectOccupation -> masters_occupation_other; 
        }
        function UpdateBioOccupation() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_masters_BioID.php');
            $UpdateOccupation = new MastersBio();
            //$client_name = $project_client -> client_name;
            $UpdateOccupation -> masters_occupation_id = $_POST['OccupationID'];
            $UpdateOccupation -> masters_occupation_name = $_POST['occupn_name'];
            $UpdateOccupation -> masters_occupation_other = $_POST['occupn_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateOccupation -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateOccupation -> session_user_id = $_SESSION['session_user_id'];
            }
            
            $UpdateOccupation -> UpdateBioOccupation();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewOccupation&OccupationID=' . $UpdateOccupation -> masters_occupation_id  . '#BioID');
            exit();
        }
        function InsertBioOccupation() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
              include_once ('../code/code_masters_BioID.php');
            $InsertOccupation = new MastersBio();
            
            $InsertOccupation -> masters_occupation_name = $_POST['occupn_name'];
            $InsertOccupation -> masters_occupation_other = $_POST['occupn_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertOccupation -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertOccupation -> session_user_id = $_SESSION['session_user_id'];
            }
            

            $InsertOccupation -> InsertBioOccupation();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#BioID');
            exit();
        }
        function DeleteBioOccupation() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteOccupation = new MastersBio();
            $DeleteOccupation  -> masters_occupation_id = $_GET['OccupationID'];
            $DeleteOccupation -> DeleteBioOccupation();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&OccupationID=' . $DeleteOccupation -> masters_occupation_id . '&ISDELETED #BioID');
            exit();
        }
        
#::FAMILY::# 
        //read relation
        function LoadFamRelations() {
            include_once ('../code/code_masters_BioID.php');
            $LoadRelations = new MastersBio();
           #$project_clients -> select_project_id = $_GET["ProjectID"];

            if (isset($_GET['relationPage'])) {
                $GLOBALS['relation_load_page'] = $_GET['relationPage'];
            } else {
                $GLOBALS['relation_load_page'] = 1;
            }

            //set pagination parameters
             $LoadRelations -> ReadPageParamsRel();
            $GLOBALS['num_pages'] = $LoadRelations -> occupn_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['relation_load_page'] == 1) {
                 $LoadRelations -> relation_record_num = 0;
                 $LoadRelations -> relation_data_offset = 0;
            } else if ($GLOBALS['relation_load_page'] <=  $LoadRelations -> relation_last_page) {
                 $LoadRelations -> relation_data_offset = ($GLOBALS['relation_load_page'] - 1) *  $LoadRelations -> relation_page_rows;
                 $LoadRelations -> relation_record_num = ($GLOBALS['relation_load_page'] - 1) *  $LoadRelations -> relation_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['relation_load_page'] = 1;
                 $LoadRelations -> relation_record_num = 0;
                 $LoadRelations -> relation_data_offset = 0;
            }

            if (($GLOBALS['relation_load_page'] + 1) <=  $LoadRelations -> occupn_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['relation_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['relation_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['relation_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadRelations -> LoadFamRelations();
        } 
        function SelectFamRelations() {
           
            include_once ('../code/code_masters_BioID.php');
            $SelectRelations = new MastersBio();
            $SelectRelations  -> masters_relation_id = $_GET["RelationID"];
            $SelectRelations  -> SelectFamRelations();
            //$client_name = $project_client -> client_name;
            $GLOBALS['masters_relation_id'] = $SelectRelations -> masters_relation_id;
            $GLOBALS['masters_relation_relation'] = $SelectRelations -> masters_relation_relation;
            $GLOBALS['masters_relation_other'] = $SelectRelations -> masters_relation_ther; 
        }
        function UpdateFamRelations() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_masters_BioID.php');
            $UpdateRelation = new MastersBio();
            //$client_name = $project_client -> client_name;
            $UpdateRelation -> masters_relation_id = $_POST['RelationID'];
            $UpdateRelation -> masters_relation_relation = $_POST['relation_relation'];
            $UpdateRelation -> masters_relation_other = $_POST['relation_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateRelation -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateRelation -> session_user_id = $_SESSION['session_user_id'];
            }
            
            $UpdateRelation -> UpdateFamRelations();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewRelation&RelationID=' . $UpdateRelation -> masters_relation_id  . '#BioFamily');
            exit();
        }
        function InsertFamRelations() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
              include_once ('../code/code_masters_BioID.php');
            $InsertRelations = new MastersBio();
            
            $InsertRelations -> masters_relation_relation = $_POST['relation_name'];
            $InsertRelations -> masters_relation_other = $_POST['relation_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertRelations -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertRelations -> session_user_id = $_SESSION['session_user_id'];
            }
            

            $InsertRelations -> InsertFamRelations();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#BioFamily');
            exit();
        }
        function DeleteFamRelations() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteRelations = new MastersBio();
            $DeleteRelations -> masters_relation_id = $_GET['RelationID'];
            $DeleteRelations -> DeleteFamRelations();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&RelationID=' . $DeleteRelations -> masters_relation_id . '&ISDELETED #BioFamily');
            exit();
        }
        
        //read tribe
        function LoadFamTribe() {
            include_once ('../code/code_masters_BioID.php');
            $LoadTribe = new MastersBio();
           #$project_clients -> select_project_id = $_GET["ProjectID"];

            if (isset($_GET['tribePage'])) {
                $GLOBALS['tribe_load_page'] = $_GET['tribePage'];
            } else {
                $GLOBALS['tribe_load_page'] = 1;
            }

            //set pagination parameters
             $LoadTribe -> ReadPageParamsTribe();
            $GLOBALS['num_pages'] = $LoadTribe  -> tribe_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['tribe_load_page'] == 1) {
                 $LoadTribe -> tribe_record_num = 0;
                 $LoadTribe -> tribe_data_offset = 0;
            } else if ($GLOBALS['tribe_load_page'] <=  $LoadTribe -> occupn_last_page) {
                 $LoadTribe -> tribe_data_offset = ($GLOBALS['tribe_load_page'] - 1) *  $LoadTribe -> tribe_page_rows;
                 $LoadTribe -> tribe_record_num = ($GLOBALS['tribe_load_page'] - 1) *  $LoadTribe -> tribe_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['tribe_load_page'] = 1;
                 $LoadTribe -> tribe_record_num = 0;
                 $LoadTribe -> tribe_data_offset = 0;
            }

            if (($GLOBALS['tribe_load_page'] + 1) <=  $LoadTribe -> tribe_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['tribe_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['tribe_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['tribe_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadOccupation -> LoadFamTribe();
        } 
        function SelectFamTribe() {
           
            include_once ('../code/code_masters_BioID.php');
            $SelectTribe = new MastersBio();
            $SelectTribe  -> masters_tribe_id = $_GET["TribeID"];
            $SelectTribe  -> SelectFamTribe();
            //$client_name = $project_client -> client_name;
            $GLOBALS['masters_tribe_id'] = $SelectTribe -> masters_tribe_id;
            $GLOBALS['masters_tribe_tribe'] = $SelectTribe -> masters_tribe_tribe;
            $GLOBALS['masters_tribe_other'] = $SelectTribe -> masters_tribe_other;
            $GLOBALS['masters_tribe_location'] = $SelectTribe -> masters_tribe_location;
        }
        function UpdateFamTribe() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_masters_BioID.php');
            $UpdateOccupation = new MastersBio();
            //$client_name = $project_client -> client_name;
            $UpdateTribe -> masters_tribe_id = $_POST['TribeID'];
            $UpdateTribe -> masters_tribe_tribe = $_POST['tribe_name'];
            $UpdateTribe -> masters_tribe_other = $_POST['tribe_other'];
            $UpdateTribe -> masters_tribe_location = $_POST['tribe_location'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateTribe -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateTribe -> session_user_id = $_SESSION['session_user_id'];
            }
            
            $UpdateTribe -> UpdateFamTribe();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewTribe&TribeID=' . $UpdateTribe -> masters_tribe_id  . '#BioFamily');
            exit();
        }
        function InsertFamTribe() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
              include_once ('../code/code_masters_BioID.php');
            $InsertTribe = new MastersBio();
            
            $InsertTribe -> masters_tribe_tribe = $_POST['tribe_name'];
            $InsertTribe -> masters_tribe_other = $_POST['tribe_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertTribe -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertTribe -> session_tribe_id = $_SESSION['session_user_id'];
            }
            

            $InsertTribe -> InsertFamTribe();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#BioFamily');
            exit();
        }
        function DeleteFamTribe() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteTribe = new MastersBio();
            $DeleteTribe  -> masters_tribe_id = $_GET['TribeID'];
            $DeleteTribe -> DeleteFamTribe();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&TribeID=' . $DeleteTribe -> masters_tribe_id . '&ISDELETED #BioFamily');
            exit();
        }

        //read religion
        function LoadFamReligion() {
            include_once ('../code/code_masters_BioID.php');
            $LoadReligion = new MastersBio();
           #$project_clients -> select_project_id = $_GET["ProjectID"];

            if (isset($_GET['relationPage'])) {
                $GLOBALS['relation_load_page'] = $_GET['relationPage'];
            } else {
                $GLOBALS['relation_load_page'] = 1;
            }

            //set pagination parameters
             $LoadReligion -> ReadPageParamsRel();
            $GLOBALS['num_pages'] = $LoadReligionn  -> relation_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['relation_load_page'] == 1) {
                 $LoadReligion -> relation_record_num = 0;
                 $LoadReligion -> relation_data_offset = 0;
            } else if ($GLOBALS['relation_load_page'] <=  $LoadReligion -> relation_last_page) {
                 $LoadReligion -> relation_data_offset = ($GLOBALS['relation_load_page'] - 1) *  $LoadReligion -> relation_page_rows;
                 $LoadReligion -> relation_record_num = ($GLOBALS['relation_load_page'] - 1) *  $LoadReligion -> relation_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['relation_load_page'] = 1;
                 $LoadReligion -> relation_record_num = 0;
                 $LoadReligion -> relation_data_offset = 0;
            }

            if (($GLOBALS['relation_load_page'] + 1) <=  $LoadReligion -> relation_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['relation_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['relation_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['relation_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadReligion -> LoadFamReligion();
        } 
        function SelectFamReligion() {
            include_once ('../code/code_masters_BioID.php');
            $SelectReligion= new MastersBio();
            $SelectReligion -> masters_religion_id = $_GET["ReligionID"];
            $SelectReligion -> SelectFamReligion();
            $GLOBALS['masters_religion_id'] = $SelectReligion -> masters_religion_id;
            $GLOBALS['masters_religion_name'] = $SelectReligion -> masters_religion_religion;
            $GLOBALS['masters_religion_other'] = $SelectReligion -> masters_religion_other; 
        }
        function UpdateFamReligion() {
            include_once ('../code/code_masters_BioID.php');
            $UpdateReligion = new MastersBio();
            $UpdateReligion -> masters_religion_id = $_POST['ReligionID'];
            $UpdateReligion -> masters_religion_religion = $_POST['religion_religion'];
            $UpdateReligion -> masters_religion_other = $_POST['religion_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateReligion -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateReligion -> session_user_id = $_SESSION['session_user_id'];
            }
            
            $UpdateReligion -> UpdateFamReligion();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewReligion&ReligionID=' . $UpdateReligion-> masters_religion_id  . '#BioFamily');
            exit();
        }
        function InsertFamReligion() {
              include_once ('../code/code_masters_BioID.php');
            $InsertReligion = new MastersBio();
            $InsertReligion -> masters_religion_religion = $_POST['religion_name'];
            $InsertReligion -> masters_religion_other = $_POST['religion_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertReligion -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertReligion -> session_user_id = $_SESSION['session_user_id'];
            }
            $InsertReligion -> InsertFamReligion();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#BioFamily');
            exit();
        }
        function DeleteFamReligion() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteReligion = new MastersBio();
            $DeleteReligion  -> masters_religion_id = $_GET['ReligionID'];
            $DeleteReligion -> DeleteFamReligion();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&ReligionID=' . $DeleteReligion -> masters_religion_id . '&ISDELETED #BioFamily');
            exit();
        }
        

#::VALUATION::
        //MASTERS crop
        function LoadValCrop() {
            include_once ('../code/code_masters_BioID.php');
            $LoadCrop = new MastersBio();
            if (isset($_GET['cropPage'])) {
                $GLOBALS['crop_load_page'] = $_GET['cropPage'];
            } else {
                $GLOBALS['crop_load_page'] = 1;
            }

            //set pagination parameters
             $LoadCrop -> ReadPageParamsCrop();
            $GLOBALS['num_pages'] = $LoadCrop -> crop_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['crop_load_page'] == 1) {
                 $LoadCrop -> crop_record_num = 0;
                 $LoadCrop -> crop_data_offset = 0;
            } else if ($GLOBALS['crop_load_page'] <=  $LoadRelations -> crop_last_page) {
                 $LoadCrop -> crop_data_offset = ($GLOBALS['crop_load_page'] - 1) *  $LoadCrop -> crop_page_rows;
                 $LoadCrop -> crop_record_num = ($GLOBALS['crop_load_page'] - 1) *  $LoadCrop -> crop_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['crop_load_page'] = 1;
                 $LoadCrop -> crop_record_num = 0;
                 $LoadCrop -> crop_data_offset = 0;
            }

            if (($GLOBALS['crop_load_page'] + 1) <=  $LoadCrop -> crop_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['crop_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['crop_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['crop_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadCrop -> LoadFalCrop();
        } 
        function SelectValCrop() {
            include_once ('../code/code_masters_BioID.php');
            $SelectCrop = new MastersBio();
            $SelectCrop  -> masters_crop_id = $_GET["CropID"];
            $SelectCrop  -> SelectValCrop();
            $GLOBALS['masters_crop_id'] = $SelectRelations -> masters_crop_id;
            $GLOBALS['masters_crop_relation'] = $SelectCrop -> masters_crop_crop;
            $GLOBALS['masters_crop_other'] = $SelectCrop -> masters_crop_other; 
        }
        function UpdateValCrop() {
            include_once ('../code/code_masters_BioID.php');
            $UpdateCrop = new MastersBio();
            $UpdateCrop -> masters_crop_id = $_POST['CropID'];
            $UpdateCrop -> masters_crop_crop = $_POST['crop_crop'];
            $UpdateCrop -> masters_crop_other = $_POST['crop_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateCrop -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateCrop -> session_user_id = $_SESSION['session_user_id'];
            }
            $UpdateCrop -> UpdateValCrop();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewCrop&CropID=' . $UpdateCrop -> masters_crop_id  . '#ValuationInfo');
            exit();
        }
        function InsertValCrop() {
              include_once ('../code/code_masters_BioID.php');
            $InsertCrop = new MastersBio();
            $InsertCrop -> masters_crop_crop = $_POST['crop_name'];
            $InsertCrop -> masters_crop_other = $_POST['crop_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertCrop -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertCrop -> session_user_id = $_SESSION['session_user_id'];
            }
            $InsertCrop -> InsertValCrop();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#ValuationInfo');
            exit();
        }
        function DeleteValCrop() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteCrop = new MastersBio();
            $DeleteCrop -> masters_crop_id = $_GET['CropID'];
            $DeleteCrop -> DeleteValCrop();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&CropID=' . $DeleteCrop -> masters_crop_id . '&ISDELETED #ValuationInfo');
            exit();
        }
        //MASTERS land
        function LoadValLand() {
            include_once ('../code/code_masters_BioID.php');
            $LoadLand = new MastersBio();
            if (isset($_GET['landPage'])) {
                $GLOBALS['land_load_page'] = $_GET['landPage'];
            } else {
                $GLOBALS['land_load_page'] = 1;
            }

            //set pagination parameters
             $LoadLand -> ReadPageParamsRel();
            $GLOBALS['num_pages'] = $LoadLand -> land_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['land_load_page'] == 1) {
                 $LoadLand -> land_record_num = 0;
                 $LoadLand -> land_data_offset = 0;
            } else if ($GLOBALS['land_load_page'] <=  $LoadLand -> land_last_page) {
                 $LoadLand -> land_data_offset = ($GLOBALS['land_load_page'] - 1) *  $LoadLand -> land_page_rows;
                 $LoadLand -> land_record_num = ($GLOBALS['land_load_page'] - 1) *  $LoadLand -> land_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['land_load_page'] = 1;
                 $LoadLand -> land_record_num = 0;
                 $LoadLand -> land_data_offset = 0;
            }

            if (($GLOBALS['land_load_page'] + 1) <=  $LoadReligion -> relation_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['land_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['land_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['land_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadLand -> LoadValLand();
        } 
        function SelectValLand() {
            include_once ('../code/code_masters_BioID.php');
            $SelectLand= new MastersBio();
            $SelectLand -> masters_land_id = $_GET["LandID"];
            $SelectLand -> SelectValLand();
            $GLOBALS['masters_land_id'] = $SelectLand -> masters_land_id;
            $GLOBALS['masters_land_land'] = $SelectLand -> masters_land_land;
            $GLOBALS['masters_land_other'] = $SelectLand -> masters_land_other; 
        }
        function UpdateValLand() {
            include_once ('../code/code_masters_BioID.php');
            $UpdateLand = new MastersBio();
            $UpdateLand -> masters_Land_id = $_POST['LandID'];
            $UpdateLand -> masters_Land_Land = $_POST['Land_Land'];
            $UpdateLand -> masters_Land_other = $_POST['land_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateLand -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateLand -> session_user_id = $_SESSION['session_user_id'];
            }
            $UpdateLand -> UpdateValLand();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewLand&LandID=' . $UpdateLand -> masters_land_id  . '#ValuationInfo');
            exit();
        }
        function InsertValLand() {
              include_once ('../code/code_masters_BioID.php');
            $InsertLand = new MastersBio();
            $InsertLand -> masters_land_land = $_POST['land_name'];
            $InsertLand -> masters_land_other = $_POST['land_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertLand -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertLand -> session_user_id = $_SESSION['session_user_id'];
            }
            $InsertLand -> InsertValLand();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#ValuationInfo');
            exit();
        }
        function DeleteValLand() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteLand = new MastersBio();
            $DeleteLand -> masters_land_id = $_GET['LandID'];
            $DeleteLand -> DeleteFamReligion();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&LandID=' . $DeleteLand -> masters_land_id . '&ISDELETED #ValuationInfo');
            exit();
        }


#::PROJECT::#
        //read dispute
        function LoadProjDispute() {
            include_once ('../code/code_masters_BioID.php');
            $LoadDispute = new MastersBio();
            if (isset($_GET['disputePage'])) {
                $GLOBALS['dispute_load_page'] = $_GET['disputePage'];
            } else {
                $GLOBALS['dispute_load_page'] = 1;
            }

            //set pagination parameters
             $LoadDispute -> ReadPageParamsDisp();
            $GLOBALS['num_pages'] = $LoadDispute -> land_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['dispute_load_page'] == 1) {
                 $LoadDispute -> dispute_record_num = 0;
                 $LoadDispute -> dispute_data_offset = 0;
            } else if ($GLOBALS['dispute_load_page'] <=  $LoadLand -> dispute_last_page) {
                 $LoadDispute -> dispute_data_offset = ($GLOBALS['dispute_load_page'] - 1) *  $LoadDispute-> dispute_page_rows;
                 $LoadDispute -> dispute_record_num = ($GLOBALS['dispute_load_page'] - 1) *  $LoadDispute -> dispute_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['dispute_load_page'] = 1;
                 $LoadDispute -> dispute_record_num = 0;
                 $LoadDispute -> dispute_data_offset = 0;
            }

            if (($GLOBALS['dispute_load_page'] + 1) <=  $LoadReligion -> dispute_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['dispute_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['dispute_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['dispute_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadDispute -> LoadProjDispute();
        } 
        function SelectProjDispute() {
            include_once ('../code/code_masters_BioID.php');
            $SelectLand= new MastersBio();
            $SelectLand -> masters_dispute_id = $_GET["DisputeID"];
            $SelectLand -> SelectProjDispute();
            $GLOBALS['masters_dispute_id'] = $SelectDispute -> masters_dispute_id;
            $GLOBALS['masters_dispute_dispute'] = $SelectDispute -> masters_dispute_dispute;
            $GLOBALS['masters_dispute_other'] = $SelectDispute -> masters_dispute_other; 
        }
        function UpdateProjDispute() {
            include_once ('../code/code_masters_BioID.php');
            $UpdateDispute = new MastersBio();
            $UpdateDispute -> masters_dispute_id = $_POST['DisputeID'];
            $UpdateDispute -> masters_dispute_dispute = $_POST['dispute_dispute'];
            $UpdateDispute -> masters_dispute_other = $_POST['dispute_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateDispute -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateDispute -> session_user_id = $_SESSION['session_user_id'];
            }
            $UpdateDispute -> UpdateProjDispute();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewDispute&DisputeID=' . $UpdateDispute -> masters_dispute_id  . '#ProjectsInfo');
            exit();
        }
        function InsertProjDispute() {
              include_once ('../code/code_masters_BioID.php');
            $InsertDispute = new MastersBio();
            $InsertDispute -> masters_dispute_land = $_POST['dispute_dispute'];
            $InsertDispute -> masters_land_other = $_POST['dispute_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertDispute -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertDispute -> session_user_id = $_SESSION['session_user_id'];
            }
            $InsertDispute -> InsertProjDispute();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#ProjectsInfo');
            exit();
        }
        function DeleteProjDispute() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteDispute = new MastersBio();
            $DeleteDispute -> masters_dispute_id = $_GET['DisputeID'];
            $DeleteDispute -> DeleteProjDispute();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&DisputeID=' . $DeleteDispute -> masters_dispute_id . '&ISDELETED #ProjectsInfo');
            exit();
        }


#::REPORTS::#
        //read doc
        function LoadSysDoc() {
            include_once ('../code/code_masters_BioID.php');
            $LoadDoc = new MastersBio();
            if (isset($_GET['docPage'])) {
                $GLOBALS['doc_load_page'] = $_GET['docPage'];
            } else {
                $GLOBALS['doc_load_page'] = 1;
            }

            //set pagination parameters
             $LoadDoc -> ReadPageParamsDoc();
            $GLOBALS['num_pages'] = $LoadDoc -> doc_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['doc_load_page'] == 1) {
                 $LoadDoc -> doc_record_num = 0;
                 $LoadDoc -> doc_data_offset = 0;
            } else if ($GLOBALS['doc_load_page'] <=  $LoadDoc -> doc_last_page) {
                 $LoadDoc -> doc_data_offset = ($GLOBALS['doc_load_page'] - 1) *  $LoadDoc-> doc_page_rows;
                 $LoadDoc -> doc_record_num = ($GLOBALS['doc_load_page'] - 1) *  $LoadDoc -> doc_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['doc_load_page'] = 1;
                 $LoadDoc -> doc_record_num = 0;
                 $LoadDoc -> doc_data_offset = 0;
            }

            if (($GLOBALS['doc_load_page'] + 1) <=  $LoadDoc -> doc_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['doc_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['doc_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['doc_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadDoc -> LoadSysDoc();
        } 
        function SelectSysDoc() {
            include_once ('../code/code_masters_BioID.php');
            $SelectDoc= new MastersBio();
            $SelectDoc -> masters_doc_id = $_GET["DocID"];
            $SelectDoc -> SelectSysDoc();
            $GLOBALS['masters_doc_id'] = $SelectDoc -> masters_doc_id;
            $GLOBALS['masters_doc_doc'] = $SelectDoc -> masters_doc_doc;
            $GLOBALS['masters_doc_other'] = $SelectDoc -> masters_doc_other; 
        }
        function UpdateSysDoc() {
            include_once ('../code/code_masters_BioID.php');
            $UpdateDoc = new MastersBio();
            $UpdateDoc -> masters_doc_id = $_POST['DocID'];
            $UpdateDoc -> masters_doc_doc = $_POST['doc_doc'];
            $UpdateDoc -> masters_doc_other = $_POST['doc_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateDoc -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateDoc -> session_user_id = $_SESSION['session_user_id'];
            }
            $UpdateDoc -> UpdateSysDoc();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewDoc&DocID=' . $UpdateDoc -> masters_doc_id  . '#ReportsInfo');
            exit();
        }
        function InsertSysDoc() {
              include_once ('../code/code_masters_BioID.php');
            $InsertDoc = new MastersBio();
            $InsertDoc -> masters_doc_doc = $_POST['doc_doc'];
            $InsertDoc -> masters_doc_other = $_POST['doc_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertDoc -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertDoc -> session_user_id = $_SESSION['session_user_id'];
            }
            $InsertDoc -> InsertSysDoc();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#ReportsInfo');
            exit();
        }
        function DeleteSysDoc() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteDoc = new MastersBio();
            $DeleteDoc -> masters_doc_id = $_GET['DocID'];
            $DeleteDoc -> DeleteSysDoc();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&DocID=' . $DeleteDoc -> masters_doc_id . '&ISDELETED #ReportsInfo');
            exit();
        }

        //read report
        function LoadSysReport() {
            include_once ('../code/code_masters_BioID.php');
            $LoadReport = new MastersBio();
            if (isset($_GET['reportPage'])) {
                $GLOBALS['report_load_page'] = $_GET['reportPage'];
            } else {
                $GLOBALS['report_load_page'] = 1;
            }

            //set pagination parameters
             $LoadReport -> ReadPageParamsRep();
            $GLOBALS['num_pages'] = $LoadReport -> report_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['report_load_page'] == 1) {
                 $LoadReport -> report_record_num = 0;
                 $LoadReport -> report_data_offset = 0;
            } else if ($GLOBALS['report_load_page'] <=  $LoadReport -> report_last_page) {
                 $LoadReport -> report_data_offset = ($GLOBALS['report_load_page'] - 1) *  $LoadReport -> report_page_rows;
                 $LoadReport -> report_record_num = ($GLOBALS['report_load_page'] - 1) *  $LoadReport -> report_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['report_load_page'] = 1;
                 $LoadReport -> report_record_num = 0;
                 $LoadReport -> report_data_offset = 0;
            }

            if (($GLOBALS['report_load_page'] + 1) <=  $LoadReport -> report_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['report_load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['report_load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['report_load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $LoadReport -> LoadSysReport();
        } 
        function SelectSysReport() {
            include_once ('../code/code_masters_BioID.php');
            $SelectReport= new MastersBio();
            $SelectReport -> masters_report_id = $_GET["ReportID"];
            $SelectReport -> SelectSysReport();
            $GLOBALS['masters_report_id'] = $SelectReport -> masters_report_id;
            $GLOBALS['masters_report_report'] = $SelectReport -> masters_report_report;
            $GLOBALS['masters_report_code'] = $SelectReport -> masters_report_code; 
            $GLOBALS['masters_report_other'] = $SelectReport -> masters_report_other; 
        }
        function UpdateSysReport() {
            include_once ('../code/code_masters_BioID.php');
            $UpdateReport = new MastersBio();
            $UpdateReport -> masters_report_id = $_POST['ReportID'];
            $UpdateReport -> masters_report_report = $_POST['report_report'];
            $UpdateReport -> masters_report_code = $_POST['report_code'];
            $UpdateReport -> masters_report_other = $_POST['report_other'];
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $UpdateReport -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $UpdateReport -> session_user_id = $_SESSION['session_user_id'];
            }
            $UpdateReport -> UpdateSysReport();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=ViewReport&ReportID=' . $UpdateReport -> masters_report_id  . '#ReportsInfo');
            exit();
        }
        function InsertSysReport() { 
              include_once ('../code/code_masters_BioID.php');
            $InsertReport = new MastersBio();
            $InsertReport -> masters_report_land = $_POST['report_report'];
            $InsertReport -> masters_report_code = $_POST['report_code'];
             $InsertReport -> masters_report_other = $_POST['report_other'];
             if (session_status() == PHP_SESSION_NONE) {
                session_start();
            $InsertReport -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $InsertReport -> session_user_id = $_SESSION['session_user_id'];
            }
            $InsertReport -> InsertSysReport();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read#ReportsInfo');
            exit();
        }
        function DeleteSysReport() {
            include_once ('../code/code_masters_BioID.php');
            $DeleteReport = new MastersBio();
            $DeleteReport -> masters_report_id = $_GET['ReportID'];
            $DeleteReport -> DeleteSysReport();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&ReportID=' . $DeleteReport -> masters_report_id . '&ISDELETED #ReportsInfo');
            exit();
        }



		?>

        
   <!-- Adds tab persistence to bootstrap tabs -->
		<script type="text/javascript">
            $(document).ready(function() {
                // show active tab on reload
                if (location.hash !== '') 
                $('a[href="' + location.hash + '"]').tab('show');

                // remember the hash in the URL without jumping
                 $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                 if (history.pushState) { history.pushState(null, null, '#' + $(e.target).attr('href').substr(1)); } 
                 else { location.hash = '#' + $(e.target).attr('href').substr(1); }
                 });
            });
        </script>     
        
        
        
    
		<div id="ContentP" class="ContentParent" style="height:1300px;">
			<div class="Content">
				<div class="ContentTitle2">
					Masters, Other Information
				</div>
				<br>
				<div class="container">
					<ul class="nav nav-tabs">
						<li class="inactive">
							<a  href="">&nbsp;</a>
						</li>
						<li class="active">
							<a data-toggle="tab" href="#BioID">Bio ID</a>
						</li>
						<li>
							<a data-toggle="tab" href="#BioFamily">Bio Family</a>
						</li>
						<li>
							<a data-toggle="tab" href="#ValuationInfo">Valuation Info</a>
						</li>
						<li>
							<a data-toggle="tab" href="#ProjectsInfo">Projects Info</a>
						</li>
						<li>
							<a data-toggle="tab" href="#ReportsInfo">Documentation Info</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
					</ul>
					<div class="tab-content">

						<!-- Master Bio ID Starts here -->
						<div id="BioID" class="tab-pane active">

							<!-- First Form for Occupation  and it works apart from the navigation-->
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
				 <form action="<?php if ($_GET['Mode'] == 'ViewOccupation') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=EditOccupation#BioID';} else if ($_GET['Mode'] == 'Read') {echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertOccupation#BioID';}?>" method="POST" autocomplete="off">    
									<fieldset class="fieldset" style="padding:15px; width:600px;">
										<legend class="legend" style="width:150px;">
											<span class="legendText" > Occupation</span>
										</legend>
										<table>
											<tr>
												<td class="formLabel">Occupation Name:</td>
												<td class="formLabel">Other Details:</td>
											</tr>
											<tr>   
                                <!-- OCCUPATION INPUT -->                
				                <td><span class="formSingleLineBox" style="width:250px;">
                                    <input type="text" value="<?php if (isset($GLOBALS['masters_occupation_name'])) { echo $GLOBALS['masters_occupation_name']; } ?>" name = "occupn_name" placeholder="Enter Job Name" style="width: 180px;" /> <input type="hidden" value="<?php echo $_GET['OccupationID']?> " name = "OccupationID"/> </span>
                                </td>           
                                <!-- OCCUPATION other details input -->                 
				                <td rowspan="4"><span class="formMultiLineBox" style="width:300px; height:125px;">
                                    <textarea type="text" style="width:280px; height:110px;"	placeholder="Occupation Description, Summary" name="occupn_other"><?php if (isset($GLOBALS['masters_occupation_other'])) { echo $GLOBALS['masters_occupation_other'];}?></textarea></span>
                                </td>
											</tr>
											<tr>
												<td class="formLabel"> Job Sector: </td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter Sector Name:</span></td>
											</tr>
                                           <tr>
											<td colspan="2"> <span class="saveButtonArea">
												<input type="submit" value="<?php if ($_GET['Mode'] == 'ViewOccupation') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;" />
												<?php $new_Occupn = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read #BioID';
                                                if ($_GET['Mode'] == 'ViewOccupation') {echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_Occupn . '>New Client</a></span>'; } ?>
                                                </span></td>
											<!--<td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
											class="formLinks"><a href="#">Photos</a></span></td>-->
										</tr> 
										</table>
                                        
                                        <!-- start of table grid -->
										<table class="detailGrid" style="width:560px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Occupation:</td>
												<td class = "detailGridHead">Job Sector:</td>
												<td class = "detailGridHead" colspan="2">Modify:</td>
											</tr>
											<?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadBioOccupation(); } ?>
										</table>
                                        
                                        <!-- start of Navigation -->
										<table class="detailNavigation">
										<tr>
											<td>
                                                <span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['OccupationID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewOccupation&OccupationID=' . $_GET['OccupationID'] . '&OccupationPage=' . $GLOBALS['prev_page'] . '#BioID'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&OccupationPage=' . $GLOBALS['prev_page'] . '#BioID'; } ?>" >Previous</a>
                                                    
                                                    
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['OccupationPage'])) { echo $occupn_load_page . ' / ' . $num_pages ; } else {echo '1 / ' . $num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['OccupationID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewOccupation&OccupationID=' . $_GET['OccupationID'] . '&OccupationPage=' . $GLOBALS['next_page'] . '#BioID';}
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&OccupationPage=' . $GLOBALS['next_page'] . '#BioID'; } ?>" >Next</a>
                                    </span>                                            
                                            </td>
										</tr>
									</table>
									</fieldset>
								</form>
							</div>

							<!-- Side Form for Identification Types -->
							<div  style="width:350px; float:left; margin-top:10px;">
								<form>
									<fieldset class="fieldset" style="padding:20px; width:350px;">
										<legend class="legend" style="width:200px;">
											<span class="legendText" >Identification Types:</span>
										</legend>
										<table>
											<tr>
												<td class="formLabel">ID Type:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter ID Type <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Country Of Issue</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Country Issued </span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:300px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">ID Type:</td>
												<td class = "detailGridHead">Country:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                            <!--<php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadBioOccupation(); } ?>-->
											<tr>
												<td>1</td>
												<td>Karuma Project</td>
												<td>KIP</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Standard Gauge Railway</td>
												<td>SGR</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Uganda Kenya Oil Pipeline</td>
												<td>UKP</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Kasese Kinshasa Super Highway</td>
												<td>KKSH</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Entebbe Express Project</td>
												<td>EEP</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</fieldset>
								</form>
							</div>
						</div>
                        
						<!-- Master Bio Family Information starts here -->
						<div id="BioFamily" class="tab-pane">
							<p>
								Add, Edit Family Relations, Tribe and Religion Masters
							</p>

							<!-- First Form Family Relations -->
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form>
									<fieldset class="fieldset" style="padding:10px; width:600px;">
										<legend class="legend" style="width:200px;">
											<span class="legendText" >Relation Types:</span>
										</legend>
										<table style="width:250px; float:left; margin:0px 10px;">
											<tr>
												<td class="formLabel">Relation Name:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter Relation Name <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="width:250px; height:125px;">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:280px; margin:25px 0px; float:left;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Relation Name:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                            <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadFamRelations(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</fieldset>
								</form>

								<!-- Second Form Religion Information -->
								<form>
									<fieldset class="fieldset" style="padding:10px; margin:20px 0px; width:600px;">
										<legend class="legend" style="width:200px;">
											<span class="legendText" >Religion Types:</span>
										</legend>
										<table style="width:250px; float:left; margin:0px 10px;">
											<tr>
												<td class="formLabel">Religion Name:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter Religion Name <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="width:250px; height:125px;">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:280px; margin:25px 0px; float:left;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Religion Name:</td>
												<td class = "detailGridHead" >Modify:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadFamReligion(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</fieldset>
								</form>
							</div>

							<!-- Side Form Tribe Information -->
							<div  style="width:350px; float:left; margin-top:10px;">
								<form>
									<fieldset class="fieldset" style="padding:20px; width:350px;">
										<legend class="legend" style="width:200px;">
											<span class="legendText" >Tribe Names:</span>
										</legend>
										<table>
											<tr>
												<td class="formLabel">Tribe Name:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Tribe Name <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">District (Location)</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Tribe Location</span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="height:125px;">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:310px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Tribe Name:</td>
												<td class = "detailGridHead">District:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") {  LoadFamTribe(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</fieldset>
								</form>
							</div>
						</div>

						<!-- Master Valuation Starts Here -->
						<div id="ValuationInfo" class="tab-pane">
							<p>
								This is the Valuation Masters Information Screen
							</p>

							<!-- First Form Crop Masters -->
							<form>
								<fieldset class="fieldset" style="float:left; margin-top:10px; margin-right:10px;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Crop Master:</span>
									</legend>
									<div  style="width:280px;">
										<table>
											<tr>
												<td class="formLabel">Crop Name:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:280px;">Enter Crop Name <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="height:125px; width:280px;">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:280px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Crop Name:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadValCrop(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>

							<!-- Second Form Land Master -->
							<form>
								<fieldset class="fieldset" style="float:left; margin-top:10px; margin-right:10px;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Land Tenure / Type:</span>
									</legend>
									<div  style="width:280px;">
										<table>
											<tr>
												<td class="formLabel">Land Type:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:280px;">Enter Type Name <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="height:125px; width:280px;">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:280px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Land Tenure:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadValLand(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>

							<!-- Third Form Structures Master -->
							<form>
								<fieldset class="fieldset" style="float:left; margin-top:10px;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Improvement Types:</span>
									</legend>
									<div  style="width:280px;">
										<table>
											<tr>
												<td class="formLabel">Improvement Type:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:280px;">Enter Improvement Name <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details About:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="height:125px; width:280px;">Enter Other Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:280px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Improvement:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                            <!-- <php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadBioOccupation(); } ?>-->
											<tr>
												<td>1</td>
												<td>Karuma Project</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Standard Gauge Railway</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Uganda Kenya Oil Pipeline</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Kasese Kinshasa Super Highway</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Entebbe Express Project</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>
						</div>

						<!-- Master Projects Information Starts Here -->
						<div id="ProjectsInfo" class="tab-pane">
							<p>
								This is the Projects Information Masters Screen
							</p>

							<!-- Form one Location Details -->
							<form>
								<fieldset class="fieldset" style="width:900px; float:left; margin-top:10px;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Master Location Details:</span>
									</legend>
									<div  style="">
										<table>
											<tr>
												<td class="formLabel">District</td>
												<td class="formLabel">Sub County</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter a District<a href="#" class="LinkInBox">New</a></span></td>
												<td><span class="formSingleLineBox" style="width:250px;">Enter a Sub County<a href="#" class="LinkInBox">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">County</td>
												<td class="formLabel">Village</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter a County <a class="LinkInBox" href="#">New</a></span></td>
												<td><span class="formSingleLineBox" style="width:250px;">Enter A Village <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td><a href="#">Save/Finish</a></td>
												<td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:850px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td class = "detailGridHead">District</td>
												<td class = "detailGridHead">County</td>
												<td class = "detailGridHead">Sub County</td>
												<td class = "detailGridHead">Village</td>
												<td class = "detailGridHead">Action:</td>
											</tr>
                                             <!--<php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadBioOccupation(); } ?>-->
											<tr>
												<td>1</td>
												<td>Kabale</td>
												<td>Muhanga Town Council</td>
												<td>Bukinda</td>
												<td>Nyakitabire</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Kabale</td>
												<td>Muhanga Town Council</td>
												<td>Bukinda</td>
												<td>Nyakitabire</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Kabale</td>
												<td>Muhanga Town Council</td>
												<td>Bukinda</td>
												<td>Nyakitabire</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Kabale</td>
												<td>Muhanga Town Council</td>
												<td>Bukinda</td>
												<td>Nyakitabire</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Kabale</td>
												<td>Muhanga Town Council</td>
												<td>Bukinda</td>
												<td>Nyakitabire</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump">1 / 5</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>

							<!-- Second Form Financials Master -->
							<form>
								<fieldset class="fieldset" style=" float:left; margin-top:20px">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Financial Masters:</span>
									</legend>
									<div  style="width:400px;">
										<table>
											<tr>
												<td class="formLabel">Financial Category:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Select Financial Category <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Financial Sub Category:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Financial Sub Category</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:380px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Financial Sub Category:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                             <!--<php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadBioOccupation(); } ?>-->
											<tr>
												<td>1</td>
												<td>Karuma Project</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Standard Gauge Railway</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Uganda Kenya Oil Pipeline</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Kasese Kinshasa Super Highway</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Entebbe Express Project</td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>

							<!-- Third Form Dispute Categories -->
							<form>
								<fieldset class="fieldset" style=" float:left; margin:20px 10px;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Dispute Categories:</span>
									</legend>
									<div  style="width:400px;">
										<table>
											<tr>
												<td class="formLabel">Dispute Category:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Select Dispute Category <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Detail About:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox" style="height:100px;">Enter Details or Remarks about</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:380px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Dispute Category:</td>
												<td class = "detailGridHead">Modify:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadProjDispute(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 100</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>
						</div>

						<!-- Master Reports, Documents, and Uploads Starts here -->
						<div id="ReportsInfo" class="tab-pane">
							<p>
								This is the Report Information Masters Screen
							</p>

							<!-- First Form Document types -->
							<form>
								<fieldset class="fieldset" style=" float:left; margin-top:20px">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Document Types:</span>
									</legend>
									<div  style="width:400px;">
										<table>
											<tr>
												<td class="formLabel">Document Type:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Document Type <a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Document Code:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Document Code</span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Details:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox">Enter Remarks or Other Details</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:380px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Document Type:</td>
												<td  class = "detailGridHead">Code:</td>
												<td  class = "detailGridHead">Delete:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadSysDoc(); } ?>
                                        </table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 10</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>

							<!-- Second Form Report Types -->
							<form>
								<fieldset class="fieldset" style=" float:left; margin:20px 10px;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Report Types:</span>
									</legend>
									<div  style="width:400px;">
										<table>
											<tr>
												<td class="formLabel">Report Types:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Report Type<a class="LinkInBox" href="#">New</a></span></td>
											</tr>
											<tr>
												<td class="formLabel">Report Code:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">Enter Report Code</span></td>
											</tr>
											<tr>
												<td class="formLabel">Other Detail:</td>
											</tr>
											<tr>
												<td><span class="formMultiLineBox">Enter Remakrs or Other Detail</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:380px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Report Type:</td>
												<td  class = "detailGridHead">Code:</td>
												<td  class = "detailGridHead">Delete:</td>
											</tr>
                                             <?php if ($_SERVER["REQUEST_METHOD"] == "GET") { LoadSysReport(); } ?>
											
										</table>
										<table class="detailNavigation">
											<tr>
												<td><a href="#">Previous</a></td>
												<td class="PageJump" style="width:80px;">1 / 10</td>
												<td><a href="#">Next</a></td>
											</tr>
										</table>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		
		<div id="FooterP" class="FooterParent" style="top: 1400px;">
			<div class="FooterContent">
				&copy;&nbsp;&nbsp;Copyright&nbsp;&nbsp;2015:&nbsp;&nbsp;Dataplus Systems Uganda
			</div>
		</div>


		</body>
</html>