<! doctype html>

<?php
    # session_start();
    # $_SESSION['pap_count'] = "";
    
    ob_start();
    
    if(isset($_GET['LogOut'])){ LogOut(); }
        
    if (isset($_GET['HHID'])) { SelectPap(); }
    
     if (isset($_GET['Mode']) && ($_GET['Mode'] == 'ViewLand' || $_GET['Mode'] == 'Read' )) { CheckLandRecord(); }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewLand') {  SelectLandVal(); }
    
    if (isset($_GET['Mode']) && ($_GET['Mode'] == 'InsertLand' || $_GET['Mode'] == 'UpdateLand')) {  UpdateLandVal(); }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteLand' ) {  DeleteLandVal(); }
    
?>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="Me" content="Valuation">
		<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Valuation</title>

		<?php
                include ('ui_header.php');

                function CheckReturnUser() {
                    # include ('../code/code_index.php');
                    $time = $_SERVER['REQUEST_TIME'];

                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (session_status() == PHP_SESSION_ACTIVE && $time < $_COOKIE["session_expire"]) {
                        if (($time - $_SESSION['Last_Activity']) < 1800) {
                            // isset($_SESSION['session_user_id'])
                            include_once ('../code/code_index.php');
                            $CheckReturnUser = new LogInOut();
                            $CheckReturnUser->user_id = $_SESSION['session_user_id'];
                            $CheckReturnUser->CheckLoginStatus();
                            CheckPapSelection();
                            if ($CheckReturnUser->return_session_id == session_id() && $CheckReturnUser->login_status == "TRUE") {
                                // header('Location: ui/ui_project_list.php?PageNumber=1');
                                echo 'SetActivePage()';
                                $_SESSION['Last_Activity'] = $time;
                            } else {
                                session_unset();
                                session_destroy();
                                header('Location: ../index.php?Message=DB_Session_Expired');
                            }
                        } else {
                            include_once ('../code/code_index.php');
                            $InactiveReturnUser = new LogInOut();
                            $InactiveReturnUser->user_id = $_SESSION['session_user_id'];
                            $InactiveReturnUser->LogOff();
                            session_unset();
                            session_destroy();
                            header('Location: ../index.php?Message=Inactive_Session_Expired');
                        }
                    } else if ($time > $_COOKIE["session_expire"]) {
                        include_once ('../code/code_index.php');
                        $InactiveReturnUser = new LogInOut();
                        # $InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
                        $InactiveReturnUser->user_id = $_COOKIE["last_user"];
                        $InactiveReturnUser->LogOff();
                        session_unset();
                        session_destroy();
                        header('Location: ../index.php?Message=Session_Expired');
                    } else {
                        session_unset();
                        session_destroy();
                        header('Location: ../index.php?Message=Session_Expired');
                    }
                }

                function CheckPapSelection() {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])) {
                            header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1');
                        }
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])) {
                            header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1');
                        }
                    }
                }

                function SelectPap() {

                    include_once ('../code/code_pap_list.php');
                    $select_project_pap = new ProjectPapList();
                    $select_project_pap->pap_hhid = $_GET["HHID"];
                    $select_project_pap->SelectPap();
                    $GLOBALS['pap_name'] = $select_project_pap->pap_name;

                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        $_SESSION['pap_count'] = "";
                        $_SESSION['session_pap_hhid'] = $_GET['HHID'];
                        $_SESSION['session_pap_name'] = $GLOBALS['pap_name'];
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        $_SESSION['pap_count'] = "";
                        $_SESSION['session_pap_hhid'] = $_GET['HHID'];
                        $_SESSION['session_pap_name'] = $GLOBALS['pap_name'];
                    }
                }

                function LogOut() {
                    include_once ('../code/code_index.php');

                    $logout = new LogInOut();
                    //session_start();
                    if (isset($_SESSION['session_user_id'])) {
                        $logout->user_id = $_SESSION['session_user_id'];
                    } else {
                        $logout->user_id = $_COOKIE["last_user"];
                    }
                    $logout->LogOff();
                }
                ?>
                
                <?php
                
                function BindUnits() {
			include_once ('../code/code_val_land.php');
			$bind_units_msr = new PapLandVal();
			$bind_units_msr -> selected_project_id = $_GET["ProjectID"];
			$bind_units_msr -> BindUnits();
		}
                
                function BindTenure() {
			include_once ('../code/code_val_land.php');
			$bind_lnd_type = new PapLandVal();
			$bind_lnd_type -> selected_project_id = $_GET["ProjectID"];
			$bind_lnd_type -> BindTenure();
		}
                
                function FormatValue($Figure){
                    return number_format($Figure,0,'.',',');
                }
                
                function CleanValue($Figure){
                    return intval(preg_replace('/[%,]/', '', $Figure));
                }
                
                ?>
                
                <?php

                function LoadLandVal() {
                    include_once ('../code/code_val_land.php');
                    $load_land_val = new PapLandVal();

                    $load_land_val->selected_project_id = $_GET['ProjectID'];
                    $load_land_val->selected_project_code = $_GET['ProjectCode'];

                    if (isset($_GET['HHID'])) {
                        $load_land_val->pap_id = $_GET['HHID'];
                    } else if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        $load_land_val->pap_id = $_SESSION['session_pap_hhid'];
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        $load_land_val->pap_id = $_SESSION['session_pap_hhid'];
                    }



                    # Loading Pap Addresses
                    $load_land_val->LoadLandVal();



                    /* if (session_status() == PHP_SESSION_NONE) {
                      session_start();
                      #$GLOBALS['pap_count'] = "";
                      $GLOBALS['pap_count'] = $load_land_val -> pap_count ;
                      } else if (session_status() == PHP_SESSION_ACTIVE) {
                      #$GLOBALS['pap_count'] = "";
                      $GLOBALS['pap_count'] = $load_land_val -> pap_count ;
                      } */
                }

                function CheckLandRecord() {
                    include_once ('../code/code_val_land.php');
                    $check_land_val = new PapLandVal();

                    if (isset($_GET['HHID'])) {
                        $check_land_val->pap_id = $_GET['HHID'];
                    } else if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        $check_land_val->pap_id = $_SESSION['session_pap_hhid'];
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        $check_land_val->pap_id = $_SESSION['session_pap_hhid'];
                    }

                    # Loading Pap Addresses
                    $check_land_val->CheckLandExists();

                    # $GLOBALS['pap_count'] = "";
                    $GLOBALS['pap_count'] = $check_land_val->pap_count;
                }

                function SelectLandVal() {
                    include_once ('../code/code_val_land.php');
                    $select_land_val = new PapLandVal();

                    $select_land_val->selected_project_id = $_GET['ProjectID'];
                    $select_land_val->selected_project_code = $_GET['ProjectCode'];

                    if (isset($_GET['HHID'])) {
                        $select_land_val->pap_id = $_GET['HHID'];
                    } else if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        $select_land_val->pap_id = $_SESSION['session_pap_hhid'];
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        $select_land_val->pap_id = $_SESSION['session_pap_hhid'];
                    }

                    # Loading Pap Addresses
                    $select_land_val->SelectLandVal();

                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        $_SESSION['lnd_type'] = $select_land_val->lnd_type;
                        $_SESSION['unit_msr'] = $select_land_val->unit_msr;
                        $_SESSION['is_titled'] = $select_land_val->is_titled;
                        $_SESSION['share_of_lnd'] = $select_land_val->share_of_lnd;
                        $_SESSION['diminution'] = $select_land_val->diminution;
                        $_SESSION['is_deleted'] = $select_land_val->is_deleted;
                        $_SESSION['plot'] = $select_land_val->plot;
                        $_SESSION['block'] = $select_land_val->block;
                        # $_SESSION['share_of_lnd'] = $select_land_val ->created_by;
                        # $_SESSION['share_of_lnd'] = $select_land_val ->created_date;
                        $_SESSION['row_units'] = $select_land_val->row_units;
                        $_SESSION['row_other_units'] = $select_land_val->row_other_units;
                        $_SESSION['row_value'] = $select_land_val->row_value;
                        $_SESSION['wl_units'] = $select_land_val->wl_units;
                        $_SESSION['wl_other_units'] = $select_land_val->wl_other_units;
                        $_SESSION['wl_value'] = $select_land_val->wl_value;
                        $_SESSION['rate'] = $select_land_val->rate;
                    } else {
                        $_SESSION['lnd_type'] = $select_land_val->lnd_type;
                        $_SESSION['unit_msr'] = $select_land_val->unit_msr;
                        $_SESSION['is_titled'] = $select_land_val->is_titled;
                        $_SESSION['share_of_lnd'] = $select_land_val->share_of_lnd;
                        $_SESSION['diminution'] = $select_land_val->diminution;
                        $_SESSION['is_deleted'] = $select_land_val->is_deleted;
                        $_SESSION['plot'] = $select_land_val->plot;
                        $_SESSION['block'] = $select_land_val->block;
                        # $_SESSION['share_of_lnd'] = $select_land_val ->created_by;
                        # $_SESSION['share_of_lnd'] = $select_land_val ->created_date;
                        $_SESSION['row_units'] = $select_land_val->row_units;
                        $_SESSION['row_other_units'] = $select_land_val->row_other_units;
                        $_SESSION['row_value'] = $select_land_val->row_value;
                        $_SESSION['wl_units'] = $select_land_val->wl_units;
                        $_SESSION['wl_other_units'] = $select_land_val->wl_other_units;
                        $_SESSION['wl_value'] = $select_land_val->wl_value;
                        $_SESSION['rate'] = $select_land_val->rate;
                    }
                }

                function UpdateLandVal() {
                    include_once ('../code/code_val_land.php');
                    $update_land_val = new PapLandVal();
                    // set update parameters

                    if (isset($_GET['HHID'])) { $update_land_val->pap_id = $_GET['HHID']; } 
                    else if (session_status() == PHP_SESSION_NONE) { session_start(); $update_land_val->pap_id = $_SESSION['session_pap_hhid']; } 
                    else if (session_status() == PHP_SESSION_ACTIVE) { $update_land_val->pap_id = $_SESSION['session_pap_hhid']; }

                    $update_land_val->row_units = $_POST['ROW'];
                    $update_land_val->wl_units = $_POST['WL'];
                    $update_land_val->unit_msr = $_POST['UnitMsr'];
                    $update_land_val->block = $_POST['Block'];
                    $update_land_val->plot = $_POST['Plot'];
                    $update_land_val->share_of_lnd = CleanValue($_POST['ROWShare']);
                    $update_land_val->diminution = CleanValue($_POST['WLDim']);
                    $update_land_val->rate = CleanValue($_POST['ROWRate']);
                    $update_land_val->lnd_type = $_POST['LandTenure'];
                    if (isset($_POST['IsTitled'])) { $update_land_val -> is_titled = $_POST['IsTitled']; } else { $update_land_val -> is_titled = "false"; }
                    $update_land_val->selected_project_id = $_POST['ProjectID'];
                    $update_land_val->selected_project_code = $_POST['ProjectCode'];

                    if (session_status() == PHP_SESSION_NONE) { session_start(); $update_land_val->session_user_id = $_SESSION['session_user_id']; } 
                    else if (session_status() == PHP_SESSION_ACTIVE) { $update_land_val->session_user_id = $_SESSION['session_user_id']; }

                    $update_land_val->UpdateLandVal();

                    unset($_POST);
                    header('Refresh:0; url=ui_valuation.php?Mode=Read&ProjectID=' . $update_land_val -> selected_project_id . '&ProjectCode=' . $update_land_val -> selected_project_code . '&HHID=' . $update_land_val -> pap_id . '#ValLand');
                    exit();
                }
                
                function DeleteLandVal(){
                    include_once ('../code/code_val_land.php');
			$delete_land_val = new PapLandVal();

			$delete_land_val -> selected_project_id = $_GET['ProjectID'];
			$delete_land_val -> selected_project_code = $_GET['ProjectCode'];

			if (isset($_GET['HHID'])) { $delete_land_val->pap_id = $_GET['HHID']; } 
                         else if (session_status() == PHP_SESSION_NONE) { session_start(); $delete_land_val->pap_id = $_SESSION['session_pap_hhid']; } 
                        else if (session_status() == PHP_SESSION_ACTIVE) { $delete_land_val->pap_id = $_SESSION['session_pap_hhid']; }

			$delete_land_val ->DeleteLandVal();
                        
			unset($_POST);
			header('Refresh:0; url=ui_valuation.php?Mode=Read&ProjectID=' . $delete_land_val -> selected_project_id . '&ProjectCode=' . $delete_land_val -> selected_project_code . '&HHID=' . $delete_land_val -> pap_id . '#ValLand');
			exit();
                }
                
                ?>
                
                <script type="text/javascript">
                    function Focus(b){
                        document.getElementById(b).style.color = "#ff6600";
                    }

                    function Blur(b){
                        document.getElementById(b).style.color = "#797979";
                    }
                </script>

		<div class="ContentParent">
			<div class="Content">
				<div class="ContentTitle2">
					Valuation Information:
				</div>
				<br>
				<div class="container">
					<ul class="nav nav-tabs">
						<li class="inactive"><a>&nbsp;&nbsp;</a></li>
						<li class="active">
                                                    <a data-toggle="tab" href="#ValLand">Land Valuation</a>
						</li>
						<li>
                                                    <a data-toggle="tab" href="#ValCrops">Crop Valuation</a>
						</li>
                                                <li>
                                                    <a data-toggle="tab" href="#ValFixtures">Fixture Valuation</a>
						</li>
						<li>
                                                    <a data-toggle="tab" href="#ValStructures">Structure Valuation</a>
						</li>
                                                <!-- li>
                                                    <a data-toggle="tab" href="#ValSummary">Valuation Summary</a>
						</li -->
						<li>
                                                    <a data-toggle="tab" href="#ValFinancials">Consolidated Financials</a>
						</li>
						<li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
                                                <li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
                                                <li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
                                                <li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
					</ul>
					<div class="tab-content">

						<!-- This is the Land Valuation Screen -->
						<div id="ValLand" class="tab-pane fade in active">
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateLand&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#ValLand'; ?>" method="POST" autocomplete="off">	
                                                        <table class="formTable" style="margin-bottom: 50px;">
                                                            <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                        <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                        <input type="hidden" name="HHID" value="<?php echo $_GET['HHID']; ?>" />
                                                                <tr>
                                                                    <td class="formLabel" >Units of Measure:</td>
                                                                    <td class="formLabel" >Land Tenure:</td>
                                                                        <td class="formLabel" colspan="2"><input type="checkbox" name="IsTitled" value="true" <?php if (isset($_SESSION['is_titled']) && $_SESSION['is_titled'] == 'true' && $_GET['Mode'] == 'ViewLand') { echo ' checked="checked" '; } ?> >&nbsp;&nbsp;&nbsp;Is Registered?</td>
								</tr>
								<tr>
                                                                            <td ><span class="formSingleLineBox" style="width:145px;">
                                                                                        <select name="UnitMsr" id="SelectUnitsMsr" style="width:125px;">
                                                                                        <option value="">-- Select --</option>
                                                                                         <?php if (isset($_GET['ProjectID'])) { BindUnits(); } ?>
                                                                                     <!-- select><a class="LinkInBoxOther" href="#">New</a -->
                                                                            </span></td>
                                                                                <td ><span class="formSingleLineBox" style="width:145px;">
                                                                                    <select name="LandTenure" id="SelectLandTenure" style="width:125px;">
                                                                                        <option value="">-- Select --</option>
                                                                                         <?php if (isset($_GET['ProjectID'])) { BindTenure(); } ?>
                                                                                     <!-- select><a class="LinkInBoxOther" href="#">New</a -->
                                                                            </span></td>
                                                                    <td><span class="formSingleLineBox" style="width:145px;">
                                                                            <input type="text" placeholder="Block" name="Block" style="width: 75px;" value="<?php if (isset($_SESSION['block']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['block'];} ?>"/>
                                                                            <a class="LinkInBox" href="">Block</a>
                                                                        </span></td>
                                                                    <td><span class="formSingleLineBox" style="width:145px;">
                                                                            <input type="text" placeholder="Plot" name="Plot" style="width: 75px;" value="<?php if (isset($_SESSION['plot']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['plot'];} ?>"/>
                                                                            <a class="LinkInBox" href="">Plot</a>
                                                                        </span></td>
								</tr>
                                                                <tr>
                                                                    <td class="formLabel"></td>
                                                                </tr>
                                                                <tr bgcolor="#337ab7" style="border: 1px solid #337ab7;">
									<td class="formLabelWhite" style="width:146px;">Right Of Way:</td>
                                                                        <td class="formLabelWhite" style="width:200px;">Other Units:</td>
                                                                        <td class="formLabelWhite" style="width:50px;">Share of Land:</td>
									<td class="formLabelWhite" style="width:50px;"></td>
                                                                        <td class="formLabelWhite" style="width:145px;">Land Rate:</td>
                                                                        <td class="formLabelWhite" style="width:145px;">Right of Way Value:</td>
								</tr>
								<tr style="border: 1px solid #337ab7;">
									<td ><span class="formSingleLineBox" style="width:145px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Enter ROW" name="ROW" value="<?php if (isset($_SESSION['row_units']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['row_units'];} ?>" style="width: 145px;height:20px;"/></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:200px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Other ROW Units" name="ROWOtherUnits" value="<?php if (isset($_SESSION['row_other_units']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['row_other_units'];} ?>"  style="width: 200px;height:20px;" readonly /></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:50px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Share of Land"  name="ROWShare" value="<?php if (isset($_SESSION['share_of_lnd']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['share_of_lnd'] . '%' ;} ?>" style="width: 50px;height:20px;"  /></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:50px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Diminution"  name="ROWDim" value="<?php if (isset($_SESSION['diminution']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['diminution'] . '%';} ?>" style="width: 50px;height:20px;" readonly /></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:145px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Enter Land Rate" name="ROWRate" value="<?php if (isset($_SESSION['rate']) && $_GET['Mode'] == 'ViewLand') { echo FormatValue($_SESSION['rate']);} ?>" style="width: 145px;height:20px;"/></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:145px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Right of Way Value" name="ROWValue" value="<?php if (isset($_SESSION['row_value']) && $_GET['Mode'] == 'ViewLand') { echo FormatValue($_SESSION['row_value']);} ?>" style="width: 145px;height:20px;" readonly /></span></td>
								</tr>
                                                                <tr>
                                                                    <td class="formLabel"></td>
                                                                </tr>
								<tr bgcolor="#337ab7" style="border: 1px solid #337ab7;">
									<td class="formLabelWhite" style="width:146px;">Wayleave Area:</td>
                                                                        <td class="formLabelWhite" style="width:200px;">Other Units:</td>
                                                                        <td class="formLabelWhite" style="width:50px;">Share of Land :</td>
									<td class="formLabelWhite" style="width:50px;">Diminution:</td>
                                                                        <td class="formLabelWhite" style="width:145px;">Land Rate:</td>
                                                                        <td class="formLabelWhite" style="width:145px;">Wayleave Value:</td>
								</tr>
								<tr style="border: 1px solid #337ab7;">
                                                                        <td ><span class="formSingleLineBox" style="width:145px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Enter Wayleave" name="WL" value="<?php if (isset($_SESSION['wl_units']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['wl_units'];} ?>" style="width: 145px;height:20px;"/></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:200px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Other WOL Units" name="WLOtherUnits" value="<?php if (isset($_SESSION['wl_other_units']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['wl_other_units'];} ?>" style="width: 200px;height:20px;" readonly /></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:50px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Share of Land" name="WLShare" value="<?php if (isset($_SESSION['share_of_lnd']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['share_of_lnd'] . '%';} ?>" style="width: 50px;height:20px;" readonly /></span></td>
									<td ><span class="formSingleLineBox" style="width:50px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Diminution" name="WLDim" value="<?php if (isset($_SESSION['diminution']) && $_GET['Mode'] == 'ViewLand') { echo $_SESSION['diminution'] . '%';} ?>" style="width: 50px;height:20px;"/></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:145px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Enter Land Rate" name="WLRate" value="<?php if (isset($_SESSION['rate']) && $_GET['Mode'] == 'ViewLand') { echo FormatValue($_SESSION['rate']);} ?>" style="width: 145px;height:20px;" readonly /></span></td>
                                                                        <td ><span class="formSingleLineBox" style="width:145px;border:none;height:25px;line-height:20px;padding:2.5px 5px;"><input type="text" placeholder="Wayleave Value" name="WLValue" value="<?php if (isset($_SESSION['wl_value']) && $_GET['Mode'] == 'ViewLand') { echo FormatValue($_SESSION['wl_value']);} ?>" style="width: 145px;height:20px;" readonly /></span></td>
								</tr>
                                                                <tr>
                                                                    <td class="BlankSpacer"></td>
                                                                </tr>
								<tr>
                                                                    <td colspan="2"><span class="saveButtonArea" ><?php if ($GLOBALS['pap_count'] == 0 || $_GET['Mode'] == 'ViewLand') { echo '<input type="submit" value="Save" name="UpdateLand"/>'; } ?>
                                                                            <?php $new_address = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#ValLand';
                                                                                    if ($_GET['Mode'] == 'ViewLand') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_address . '>Cancel</a></span>'; } ?></span></td>
                                                                        
                                                                        <td></td>
                                                                        <td colspan="2"><span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=PapDoc&Tag=PapBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
											<span class="formLinks"><a href="<?php echo 'ui_doc.php?Mode=PapPhoto&Tag=PapBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Photos</a></span></td>
								</tr>
							</table>
                                                    </form>
                                                    <br/>
                                                    <form style="margin-top: 20px;">
								<fieldset class="fieldset" style="width:900px; margin:15px 0px;;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Land Assessment:</span>
									</legend>
									<table class="detailGrid" style="width:850px; margin-top:10px;">
										<tr>
											<td class = "detailGridHead">Land Tenure</td>
											<td class = "detailGridHead">Pap Status</td>
											<td  class = "detailGridHead">Area (Acres)</td>
											<td  class = "detailGridHead">Land Rate</td>
											<td  class = "detailGridHead">Total Value</td>
											<td  class = "detailGridHead">Modify</td>
											
										</tr>
										<?php if (isset($_GET['ProjectID']) ) {  $_SESSION['pap_count'] = ""; LoadLandVal(); } ?>
									</table>
								</fieldset>
							</form>
                                                    
                                                        <div class="container" style="width:1000px; height:100px; margin-left: 0px; margin-top: 40px; padding:0px;">
                                                            <ul class="nav nav-tabs">
                                                                    <li class="inactive"><a>&nbsp;</a></li>
                                                                    <li class="active"><a data-toggle="tab" href="#LandGIS">GIS Info</a></li>
                                                                    <li><a data-toggle="tab" href="#LandMap">Map</a></li>
                                                                    <li><a data-toggle="tab" href="#LandNeighbour">Neighbours</a></li>
                                                                    <li><a data-toggle="tab" href="#LandDisputes">Disputes</a></li>
                                                                    <li ><a data-toggle="tab" href="#LandStakeholder">Stakeholders</a></li>
                                                                    <li ><a data-toggle="tab" href="#OtherPlots">Other Plots</a></li>
                                                                    <li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
                                                                    <li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
                                                                   
                                                                    
                                                            </ul>
                                                            
                                                            <div class="tab-content" style="margin:10px; padding:10px;">
                                                                <!-- GIS Information -->
                                                                <div id="LandGIS" class="tab-pane fade active in">
                                                                    <p>
                                                                        <span class="TabHeader"></span>
                                                                    </p>
                                                                    <div style="width:600px; float:left; margin-bottom:20px;">
                                                                        <form name="Address" action="" method="POST" autocomplete="off">
                                                                            <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                            <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                            <input type="hidden" name="MemberID" value="<?php if (isset($_GET['MemberID'])) {
                                                                                    echo $_GET['MemberID'];
                                                                                } ?>" />
                                                                            <table class="formTable" style="">
                                                                                <tr>
                                                                                    <td class="formLabel" >X Coordinate:</td>
                                                                                    <td class="formLabel" >Y Coordinate:</td>
                                                                                    <td class="formLabel" >Z Coordinate:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                        <!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
                                                                                    <td ><span class="formSingleLineBox" style="width:200px;">
                                                                                          <input type="text" placeholder="Latitude" name="GISX" value="" style="width:180px;"/>
                                                                                        </span></td>
                                                                                    <td ><span class="formSingleLineBox" style="width:200px;">
                                                                                           <input type="text" placeholder="Longitude" name="GISY" value="" style="width:180px;"/>
                                                                                        </span></td>
                                                                                        <td ><span class="formSingleLineBox" style="width:200px;">
                                                                                            <input type="text" placeholder="Z Decimal" name="GISZ" value="" style="width:180px;"/>
                                                                                        </span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="BlankSpacer" ></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2"><span class="saveButtonArea" ><input type="submit" value="Save" name="UpdateLand"/>
                                                                            <span class="formLinks" style="margin-top:0px;"><a href="">Cancel</a></span></span></td>
                                                                                    <!-- td colspan="2"><span class="formLinks SideBar"><a href="">Documents</a></span>
											<span class="formLinks"><a href="">Photos</a></span></td -->
                                                                                    
                                                                                </tr>
                                                                            </table>
                                                                        </form>
                                                                    </div>

                                                                    <div class="GridArea" style="width: 750px;">	
                                                                        <table class="detailGrid" style="width:700px;">
                                                                            <tr>
                                                                                <td class = "detailGridHead">#</td>
                                                                                <td class = "detailGridHead">X Coordinate:</td>
                                                                                <td  class = "detailGridHead">Y Coordinate:</td>
                                                                                <td  class = "detailGridHead">Z Coordinate:</td>
                                                                                <td  class = "detailGridHead" colspan="2">Modify:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Banana Plants</td>
                                                                                <td>Seasonal</td>
                                                                                <td>Young, Good Condition</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Orange Trees</td>
                                                                                <td>Seasonal</td>
                                                                                <td>Young, Good Condition</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Eucalyptus Trees</td>
                                                                                <td>Perenial</td>
                                                                                <td>Mature, Good Condition</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Banana Plants</td>
                                                                                <td>Seasonal</td>
                                                                                <td>Young, Good Condition</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Nsambya Tree</td>
                                                                                <td>Perenial</td>
                                                                                <td>Mature, Good Condition</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="detailNavigation">
                                                                            <tr>
                                                                                <td><a href="#">Previous</a></td>
                                                                                <td class="PageJump">1 / 2</td>
                                                                                <td><a href="#">Next</a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- This is the neighborhood map Screen -->
                                                                <div id="LandMap" class="tab-pane">
                                                                     <p>
                                                                        <span class="TabHeader">Map of Neighborhood:</span>
                                                                    </p>
                                                                    <div style="width:700px; height:300px; border:1px solid #bcddfe; float:left; ">
                                                                        
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- this is the neighbours screen -->
                                                                <div id="LandNeighbour" class="tab-pane">
                                                                    <p>
                                                                        <span class="TabHeader"></span>
                                                                    </p>
                                                                    <div style="width:600px; float:left; margin-bottom:20px;">
                                                                        <form name="Address" action="" method="POST" autocomplete="off">
                                                                            <table class="formTable" style="float:left; margin-right:10px;width:550px;">
                                                                                <tr>
                                                                                    <td class="formLabel">Search for PAP:</td>
                                                                                    <td class="formLabel">Select PAP:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                            <td><span class="formSingleLineBox" style="width:250px;">
                                                                                        <input type="text" placeholder="Search for PAP" name="SearchPAP" value="" style="width:230px;"/>
                                                                                        </span></td>
                                                                                    <td><span class="formSingleLineBox" style="">
                                                                                        <select name="UnitMsr" id="SelectNeighbour" style="">
                                                                                        <option value="">-- Select --</option>
                                                                                        </span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="formLabel">Select Direction:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><span class="formSingleLineBox" style="width:250px;">
                                                                                        <select name="UnitMsr" id="SelectDirection" style="width:230px;">
                                                                                        <option value="">-- Select --</option>
                                                                                        </span></td>
                                                                                    <td class="formLabel">
                                                                                        <input type="checkbox">
                                                                                        &nbsp;&nbsp;Boundary OK?
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="BlankSpacer" ></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td ><span class="saveButtonArea" style="width:250px;"><input type="submit" value="Save" name="UpdateLand"/>
                                                                                        <span class="formLinks" style="margin-top:0px;"><a href="">Cancel</a></span></span></td>
                                                                                    <!-- td colspan="2"><span class="formLinks SideBar"><a href="">Documents</a></span>
											<span class="formLinks"><a href="">Photos</a></span></td -->
                                                                                    
                                                                                </tr>
                                                                            </table>
                                                                        </form>
                                                                    </div>

                                                                    <div class="GridArea" style="width: 750px;">
                                                                        <table class="detailGrid" style="width:700px;">
                                                                            <tr>
                                                                                <td class = "detailGridHead">#</td>
                                                                                <td class = "detailGridHead">HHID:</td>
                                                                                <td class = "detailGridHead">PAP Name:</td>
                                                                                <td  class = "detailGridHead">Direction:</td>
                                                                                <td  class = "detailGridHead" colspan="2">Modify:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>6734</td>
                                                                                <td>Mudde Musa</td>
                                                                                <td>East</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>6734</td>
                                                                                <td>Guy Mulika</td>
                                                                                <td>North</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>6734</td>
                                                                                <td>Ssenfuka Ben</td>
                                                                                <td>South</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>6734</td>
                                                                                <td>Balikowa James</td>
                                                                                <td>North East</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>6734</td>
                                                                                <td>Balikowa James</td>
                                                                                <td>North East</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="detailNavigation">
                                                                            <tr>
                                                                                <td><a href="#">Previous</a></td>
                                                                                <td class="PageJump">1 / 2</td>
                                                                                <td><a href="#">Next</a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- this is the Disputes Screen -->
                                                                <div id="LandDisputes" class="tab-pane">
                                                                    <p>
                                                                        <span class="TabHeader"></span>
                                                                    </p>
                                                                    <div style="width:600px; float:left; margin-bottom:20px;">
                                                                        <form name="Address" action="" method="POST" autocomplete="off">
                                                                            <table class="formTable" style="float:left; margin-right:10px;">
                                                                                <tr>
                                                                                    <td class="formLabel">Dispute Category:</td>
                                                                                    <td class="formLabel">Other Party:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><span class="formSingleLineBox" style="">
                                                                                        <select name="LandTenure" id="SelectLandTenure" style="">
                                                                                        <option value="">-- Select --</option>  
                                                                                        </span></td>
                                                                                    <td><span class="formSingleLineBox" style="">
                                                                                        <input type="text" placeholder="Enter Third Party" name="OtherParty" value="" style=""/>
                                                                                            </span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="formLabel">Dispute Details:</td>
                                                                                    <td class="formLabel">Action Taken:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td ><span class="formMultiLineBox" style="height: 100px; width: 300px;">
                                                                                            <textarea style="width:280px;height:90px;" id="DisputeDesc"  type="text" placeholder="Enter dispute description" name="DisputeDetails"><?php # if(isset($project_obj)){echo $project_obj;}?></textarea>
                                                                                        </span></td>
                                                                                        
                                                                                    <td ><span class="formMultiLineBox" style="height: 100px; width: 300px;"> 
                                                                                        <textarea style="width:280px;height:90px;" id="ActionTaken"  type="text" placeholder="Enter action taken here" name="ActionTaken"><?php # if(isset($project_obj)){echo $project_obj;}?></textarea>
                                                                                        </span></td>
                                                                                        
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="BlankSpacer" ></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td ><span class="saveButtonArea" style="width:250px;"><input type="submit" value="Save" name="UpdateLand"/>
                                                                                        <span class="formLinks" style="margin-top:0px;"><a href="">Cancel</a></span></span></td>
                                                                                    <!-- td colspan="2"><span class="formLinks SideBar"><a href="">Documents</a></span>
											<span class="formLinks"><a href="">Photos</a></span></td -->
                                                                                    
                                                                                </tr>                                                                                
                                                                            </table>
                                                                        </form>
                                                                    </div>

                                                                    <div class="GridArea" style="width: 750px;">
                                                                        <table class="detailGrid" style="width:700px;">
                                                                            <tr>
                                                                                <td class = "detailGridHead">#</td>
                                                                                <td class = "detailGridHead">Category:</td>
                                                                                <td  class = "detailGridHead">Description:</td>
                                                                                <td  class = "detailGridHead">Lead Time:</td>
                                                                                <td  class = "detailGridHead">Status:</td>
                                                                                <td  class = "detailGridHead" colspan="2">Modify:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>Mudde Musa</td>
                                                                                <td>East</td>
                                                                                <td>20 Days</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>Guy Mulika</td>
                                                                                <td>North</td>
                                                                                <td>10 Days</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>Mudde Musa</td>
                                                                                <td>East</td>
                                                                                <td>20 Days</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>Guy Mulika</td>
                                                                                <td>North</td>
                                                                                <td>10 Days</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>Guy Mulika</td>
                                                                                <td>North</td>
                                                                                <td>10 Days</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="detailNavigation">
                                                                            <tr>
                                                                                <td><a href="#">Previous</a></td>
                                                                                <td class="PageJump">1 / 2</td>
                                                                                <td><a href="#">Next</a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- this is the Plot Stakeholders Screen -->
                                                                <div id="LandStakeholder" class="tab-pane">
                                                                    <p>
                                                                        <span class="TabHeader">Other Stakeholders on Plot:</span>
                                                                    </p>
                                                                     <div class="GridArea" style="width: 750px;">
                                                                    <table class="detailGrid" style="width:700px; ">
                                                                            <tr>
                                                                                <td class = "detailGridHead">#</td>
                                                                                <td class = "detailGridHead">HHID</td>
                                                                                <td class = "detailGridHead">Pap Name:</td>
                                                                                <td  class = "detailGridHead">Pap Status:</td>
                                                                                <td  class = "detailGridHead">Plot Referemce:</td>
                                                                                <td  class = "detailGridHead">Payment Status:</td>
                                                                                <td  class = "detailGridHead" colspan="2">Select Pap:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>1111</td>
                                                                                <td>Mudde Musa</td>
                                                                                <td>Tenant</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>2222</td>
                                                                                <td>Guy Mulika</td>
                                                                                <td>Licensee</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>3333</td>
                                                                                <td>Ssenfuka Ben</td>
                                                                                <td>Tenant</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Resolved</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>4342</td>
                                                                                <td>Balikowa James</td>
                                                                                <td>Licensee</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>5123</td>
                                                                                <td>Balikowa James</td>
                                                                                <td>Tenant</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                        </table>
                                                                         
                                                                         <table class="detailNavigation">
                                                                            <tr>
                                                                                <td><a href="#">Previous</a></td>
                                                                                <td class="PageJump">1 / 2</td>
                                                                                <td><a href="#">Next</a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                                <!-- this is the Other Plots Screen -->
                                                                <div id="OtherPlots" class="tab-pane">
                                                                    <p>
                                                                        <span class="TabHeader">Other Plots Owned on Project Site</span>
                                                                    </p>
                                                                    <div class="GridArea" style="width: 750px; padding:0px;">
                                                                    <table class="detailGrid" style="width:700px; ">
                                                                            <tr>
                                                                                <td class = "detailGridHead">#</td>
                                                                                <td class = "detailGridHead">HHID</td>
                                                                                <td class = "detailGridHead">Pap Name:</td>
                                                                                <td  class = "detailGridHead">Pap Status:</td>
                                                                                <td  class = "detailGridHead">Plot Referemce:</td>
                                                                                <td  class = "detailGridHead">Payment Status:</td>
                                                                                <td  class = "detailGridHead" colspan="2">Select Pap:</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1</td>
                                                                                <td>1111</td>
                                                                                <td>Mudde Musa</td>
                                                                                <td>Tenant</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2</td>
                                                                                <td>2222</td>
                                                                                <td>Guy Mulika</td>
                                                                                <td>Licensee</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>3</td>
                                                                                <td>3333</td>
                                                                                <td>Ssenfuka Ben</td>
                                                                                <td>Tenant</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Resolved</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>4</td>
                                                                                <td>4342</td>
                                                                                <td>Balikowa James</td>
                                                                                <td>Licensee</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>5</td>
                                                                                <td>5123</td>
                                                                                <td>Balikowa James</td>
                                                                                <td>Tenant</td>
                                                                                <td>ABCD/AP000/000</td>
                                                                                <td>Pending</td>
                                                                                <td><a href="#"><img src="images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                            </tr>
                                                                        </table>
                                                                        
                                                                        <table class="detailNavigation">
                                                                            <tr>
                                                                                <td><a href="#">Previous</a></td>
                                                                                <td class="PageJump">1 / 2</td>
                                                                                <td><a href="#">Next</a></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    
							<!-- form>
								<fieldset class="fieldset" style="padding:0px 15px; width:900px;">
									<legend class="legend" style="width:150px;">
										<span class="legendText" >Neighbours</span>
									</legend>
									<table style="float:left; margin-right:10px;">
										<tr>
											<td class="formLabel">Neighbour Name:</td>
											<td class="formLabel">Other Details</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox" style="width:225px;">Enter Neighbour</span></td>
											<td rowspan="3"><span class="formMultiLineBox" style="height:100px; width:225px;">Capture Any other details ...</span></td>
										</tr>
										<tr>
											<td class="formLabel">Direction:</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox" style="width:225px;">Select Direction</span></td>
										</tr>
										<tr>
											<td class="formLabel">
											<input type="checkbox">
											&nbsp;&nbsp;Boundary OK?</td>
											<td class="formLabel">
											<input type="checkbox">
											&nbsp;&nbsp;Is Disputed?</td>
										</tr>
										<tr>
											<td><a class="saveButtonArea" href="#">Finish</a></td>
											<!--td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
										</tr>
									</table>
									<table class="detailGrid" style="width:360px; ">
										<tr>
											<td class = "detailGridHead">#</td>
											<td class = "detailGridHead">Neighbour:</td>
											<td  class = "detailGridHead">Direction:</td>
											<td  class = "detailGridHead" colspan="2">Modify:</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Mudde Musa</td>
											<td>East</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Guy Mulika</td>
											<td>North</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Ssenfuka Ben</td>
											<td>South</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Balikowa James</td>
											<td>North East</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
									<table class="detailNavigation">
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 2</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
								</fieldset>
							</form -->
						</div>

						<!-- This is the Crop Valuation Screen -->
						<div id="ValCrops" class="tab-pane fade">
							<table>
								<tr>
									<td class="formLabel">Crop Name:</td>
								</tr>
								<tr>
									<td colspan="3"><span class="formSingleLineBox" style="width:610px;">Select Crop Name <a class="LinkInBox" href="#">New</a> </span></td>
								</tr>
								<tr>
									<td class="formLabel">Crop Description:</td>
									<td class="formLabel" colspan="2">Units:</td>
								</tr>
								<tr>
									<td ><span class="formSingleLineBox">Select Description <a class="LinkInBox" href="#">New</a> </span></td>
									<td colspan="2"><span class="formSingleLineBox">Enter Units <a class="LinkInBox" href="#">Area</a> </span></td>
								</tr>
								<tr>
									<td class="formLabel">Crop Type:</td>
									<td class="formLabel">Crop Rate:</td>
									<td class="formLabel">Crop Total:</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Crop Type <a class="LinkInBox" href="#">New</a> </span></td>
									<td><span class="formSingleLineBox" style="width:145px;">Rate</span></td>
									<td><span class="formSingleLineBox" style="width:145px;">Total</span></td>
								</tr>
								<tr>
									<td colspan="3" class="formLabel">Other Details</td>
								</tr>
								<tr>
									<td colspan="3"><span class="formMultiLineBox"></span></td>
								</tr>
								<tr>
									<td><a class="saveButtonArea" href="#">Save Button</a></td>
									<td colspan="2"><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
								</tr>
								<tr></tr>
								<tr></tr>
							</table>
							<form>
								<fieldset class="fieldset" style="width:900px; margin:15px 0px;;">
									<legend class="legend" style="width:225px;">
										<span class="legendText" >Summary Crop Assessment:</span>
									</legend>
									<table class="detailGrid" style="width:850px; ">
										<tr>
											<td class = "detailGridHead">#</td>
											<td class = "detailGridHead">Crop Name:</td>
											<td  class = "detailGridHead">Crop Type:</td>
											<td  class = "detailGridHead">Crop Description:</td>
											<td  class = "detailGridHead">Units:</td>
											<td  class = "detailGridHead">Crop Rate:</td>
											<td  class = "detailGridHead">Crop Total:</td>
											<td  class = "detailGridHead" colspan="2">Modify:</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Orange Trees</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Eucalyptus Trees</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Nsambya Tree</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
									<table class="detailNavigation">
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 2</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
								</fieldset>
							</form>
						</div>

                                                <!-- This is the fixtures Screen -->
                                                <div id="ValFixtures" class="tab-pane fade">
                                                    
                                                </div>
                                                
						<!-- This is the improvements Screen -->
						<div id="ValStructures" class="tab-pane fade">
							<table>
								<tr>
									<td class="formLabel">Structure Make:</td>
								</tr>
								<tr>
									<td colspan="2"><span class="formSingleLineBox" style="width:610px;">Select Structure Make <a class="LinkInBox" href="#">New</a> </span></td>
								</tr>
								<tr>
									<td class="formLabel">Structure Type:</td>
									<td class="formLabel">Units Of Measure:</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Structure Type <a class="LinkInBox" href="#">New</a> </span></td>
									<td><span class="formSingleLineBox">Enter Area <a class="LinkInBox" href="#">SQM</a></span></td>
								</tr>
								<tr>
									<td class="formLabel">Roof Type:</td>
									<td class="formLabel">Structure Rate:</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Roof Type</span></td>
									<td><span class="formSingleLineBox">Enter Rate</span></td>
								</tr>
								<tr>
									<td class="formLabel">Wall Type:</td>
									<td class="formLabel">Structure Total:</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Wall Type</span></td>
									<td><span class="formSingleLineBox">Enter Structure Total</span></td>
								</tr>
								<tr>
									<td class="formLabel">Window Type:</td>
									<td class="formLabel">Other Structure Details</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Window Type</span></td>
									<td rowspan="5"><span class="formMultiLineBox" style="height:165px;">Enter Any other structure details</span></td>
								</tr>
								<tr>
									<td class="formLabel">Door Type:</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Door Type</span></td>
								</tr>
								<tr>
									<td class="formLabel">Floor Type:</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox">Select Floor Type</span></td>
								</tr>
								<tr>
									<td><a class="saveButtonArea" href="#">Save Button</a></td>
									<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
								</tr>
							</table>
							<form>
								<fieldset class="fieldset" style="width:900px; margin:15px 0px;;">
									<legend class="legend" style="width:275px;">
										<span class="legendText" >Summary Structure Assessment:</span>
									</legend>
									<table class="detailGrid" style="width:850px; margin:0px;">
										<tr>
											<td class = "detailGridHead">#</td>
											<td class = "detailGridHead">Structure Make:</td>
											<td  class = "detailGridHead">Structure Type:</td>
											<td  class = "detailGridHead">Structure Desc:</td>
											<td  class = "detailGridHead">Units:</td>
											<td  class = "detailGridHead">Structure Rate:</td>
											<td  class = "detailGridHead">Sub Total:</td>
											<td  class = "detailGridHead" colspan="2">Modify:</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Orange Trees</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Eucalyptus Trees</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Nsambya Tree</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
									<table class="detailNavigation" >
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 2</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
								</fieldset>
							</form>
						</div>

                                                <!-- This is the valuation summary -->
                                                <div id="ValSummary" class="tab-pane fade">
                                                    
                                                </div>
                                                
						<!-- Consolidated Financials -->
						<div id="ValFinancials" class="tab-pane fade">

							<!-- Summary Land Assessment -->
							<form>
								<fieldset class="fieldset" style="width:960px; float:left; padding:0px 20px;">
									<legend class="legend" style="width:275px;">
										<span class="legendText" >Summary Land Assessment:</span>
									</legend>
									<table style="margin:0px 0px 20px 0px;">
										<tr>
											<td class="formLabel">Valuation:</td>
											<td class="formLabel">Disturbance:</td>
											<td class="formLabel">Total Compensation:</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox" >Land Assessment</span></td>
											<td><span class="formSingleLineBox" >Disturbance (%)age</span></td>
											<td><span class="formSingleLineBox" >Total Compensation <a class="LinkInBox" href="#"> <img src="UI/images/save.png" alt="" class="EditDeleteButtons" style="margin:0px;"/>&nbsp;Save</a> </span></td>
										</tr>
									</table>
								</fieldset>
							</form>

							<!-- Summary Crop Assessment: -->
							<form>
								<fieldset class="fieldset" style="width:960px; float:left; margin:10px 0px; padding:0px 20px;">
									<legend class="legend" style="width:275px;">
										<span class="legendText" >Summary Crop Assessment:</span>
									</legend>
									<table style="margin:0px 0px 20px 0px;">
										<tr>
											<td class="formLabel">Valuation:</td>
											<td class="formLabel">Disturbance:</td>
											<td class="formLabel">Total Compensation:</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox" >Crop Assessment</span></td>
											<td><span class="formSingleLineBox" >Disturbance (%)age</span></td>
											<td><span class="formSingleLineBox" >Total Compensation <a class="LinkInBox" href="#"> <img src="UI/images/save.png" alt="" class="EditDeleteButtons" style="margin:0px;"/>&nbsp;Save</a> </span></td>
										</tr>
									</table>
								</fieldset>
							</form>

							<!-- Summary Improvements Assessment: -->
							<form>
								<fieldset class="fieldset" style="width:960px; float:left; padding:0px 20px;">
									<legend class="legend" style="width:325px;">
										<span class="legendText" >Summary Improvements Assessment:</span>
									</legend>
									<table style="margin:0px 0px 20px 0px;">
										<tr>
											<td class="formLabel">Valuation:</td>
											<td class="formLabel">Disturbance:</td>
											<td class="formLabel">Total Compensation:</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox" >Improvement Assessment</span></td>
											<td><span class="formSingleLineBox" >Disturbance (%)age</span></td>
											<td><span class="formSingleLineBox" >Total Compensation <a class="LinkInBox" href="#"> <img src="UI/images/save.png" alt="" class="EditDeleteButtons" style="margin:0px;"/>&nbsp;Save</a> </span></td>
										</tr>
									</table>
								</fieldset>
							</form>

							<!-- Any Other Allowances -->
							<form>
								<fieldset class="fieldset" style="width:960px; float:left; margin:10px 0px; padding:10px 20px;">
									<legend class="legend" style="width:325px;">
										<span class="legendText" >Consolidated Summary Compensation:</span>
									</legend>
									<table style="float:left;">
										<tr>
											<td class="formLabel">Allowance Name:</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox">Enter Allowance Name</span></td>
										</tr>
										<tr>
											<td class="formLabel">Total Amount:</td>
										</tr>
										<tr>
											<td><span class="formSingleLineBox">Enter Total Amount</span></td>
										</tr>
										<tr>
											<td><a class="saveButtonArea" href="#">Finish</a><span class="formLinks SideBar"><a href="#">Documents</a> </span><span class="formLinks"><a href="#">Photos</a></span></td>
										</tr>
										<!-- These two rows are spacers, do not remove -->
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
										</tr>
									</table>
									<table class="detailGrid" style="float:left; width:500px; margin:20px;">
										<tr>
											<td  class = "detailGridHead">#</td>
											<td class = "detailGridHead">Allowance</td>
											<td class = "detailGridHead">Total</td>
											<td  class = "detailGridHead">Modify</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Facilitation Allowance</td>
											<td>2,000,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Extra Land Take</td>
											<td>2,000,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Cultural Compensation</td>
											<td>2,000,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Shifting Allowance</td>
											<td>2,000,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
									<table class="detailNavigation" style="float:left; margin:10px;" >
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 2</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
								</fieldset>
							</form>

							<!-- Consolidate Summary Compensation: -->
							<form>
								<fieldset class="fieldset" style="width:960px; float:left; margin:0px; padding:0px 20px;">
									<legend class="legend" style="width:325px;">
										<span class="legendText" >Consolidated Summary Compensation:</span>
									</legend>
									<table class="detailGrid" style="width:850px; margin-top:10px;">
										<tr>
											<td class = "detailGridHead">Crop Assessment</td>
											<td class = "detailGridHead">Land Assessment</td>
											<td  class = "detailGridHead">Improvements</td>
											<td class = "detailGridHead">Other Allowance</td>
											<td  class = "detailGridHead">Total Compensation</td>
											<td class = "detailGridHead">Modify:</td>
										</tr>
										<tr>
											<td>20,000,000</td>
											<td>400,000,000</td>
											<td>250,000,000</td>
											<td>5,000,000</td>
											<td>670,000,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
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