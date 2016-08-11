<! doctype html>

<?php
    
    ob_start();
    
    if(isset($_GET['LogOut'])){ LogOut(); }
        
    if (isset($_GET['HHID'])) { SelectPap(); }
    
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

            if (session_status() == PHP_SESSION_ACTIVE && $time < $_SESSION['Expire']) {
                if (($time - $_SESSION['Last_Activity']) < 1800) {
                    // isset($_SESSION['session_user_id'])
                    include_once ('../code/code_index.php');
                    $CheckReturnUser = new LogInOut();
                    $CheckReturnUser -> user_id = $_SESSION['session_user_id'];
                    $CheckReturnUser -> CheckLoginStatus();
                    CheckPapSelection();
                    if ($CheckReturnUser -> return_session_id == session_id() && $CheckReturnUser -> login_status == "TRUE") {
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
					$InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
					$InactiveReturnUser-> LogOff();
                    session_unset();
                    session_destroy();
                    header('Location: ../index.php?Message=Inactive_Session_Expired');
                }
            } else {
            	include_once ('../code/code_index.php');
					$InactiveReturnUser = new LogInOut();
					$InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
					$InactiveReturnUser-> LogOff();
                session_unset();
                session_destroy();
                header('Location: ../index.php?Message=Session_Expired');
            }
        }

        function CheckPapSelection(){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])){ header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1'); }
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])){ header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1'); }
            }
            
        }
        
        function SelectPap(){
            
            include_once ('../code/code_pap_list.php');
            $select_project_pap = new ProjectPapList();
            $select_project_pap -> pap_hhid = $_GET["HHID"];
            $select_project_pap -> SelectPap();
            $GLOBALS['pap_name'] = $select_project_pap -> pap_name;
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $_SESSION['session_pap_hhid'] = $_GET['HHID'];
                $_SESSION['session_pap_name'] = $GLOBALS['pap_name'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $_SESSION['session_pap_hhid'] = $_GET['HHID'];
                $_SESSION['session_pap_name'] = $GLOBALS['pap_name'];
            }
        }

        function LogOut() {
            include_once ('../code/code_index.php');
    
            $logout = new LogInOut();
            //session_start();
            if (isset($_SESSION['session_user_id'])) {
                $logout -> user_id = $_SESSION['session_user_id'];
            } else {
                $logout -> user_id = $_COOKIE["last_user"];
            }
            $logout -> LogOff();

        }
        
        
        ?>

		<div class="ContentParent">
			<div class="Content">
				<div class="ContentTitle2">
					Valuation Information:
				</div>
				<br>
				<div class="container">
					<ul class="nav nav-tabs">
						<li class="inactive">
							<a>&nbsp;</a>
						</li>
						<li class="active">
							<a data-toggle="tab" href="#Land">Land Valuation</a>
						</li>
						<li>
							<a data-toggle="tab" href="#Crops">Crops Valuation</a>
						</li>
						<li>
							<a data-toggle="tab" href="#Improvements">Improvement Valuation</a>
						</li>
						<li>
							<a data-toggle="tab" href="#Financials">Consolidated Financials</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
					</ul>
					<div class="tab-content">

						<!-- This is the Land Valuation Screen -->
						<div id="Land" class="tab-pane fade in active">
							<table>
								<tr>
									<td class="formLabel" colspan="2">Right Of Way</td>
								</tr>
								<tr>
									<td colspan="2"><span class="formSingleLineBox">Enter ROW Measurement <a class="LinkInBox" href="#">Acres</a></span></td>
									<td colspan="2"><span class="formSingleLineBox">Total ROW</span></td>
								</tr>
								<tr>
									<td class="formLabel" colspan="2">Reserve Area:</td>
								</tr>
								<tr>
									<td colspan="2"><span class="formSingleLineBox">Enter Reserve Measurement <a class="LinkInBox" href="#">Acres</a></span></td>
									<td colspan="2"><span class="formSingleLineBox">Total Reserve</span></td>
								</tr>
								<tr>
									<td class="formLabel" colspan="2">Land Rate:</td>
									<td class="formLabel" colspan="2">Designation:</td>
								</tr>
								<tr>
									<td colspan="2"><span class="formSingleLineBox">Enter Land Rate</span></td>
									<td colspan="2"><span class="formSingleLineBox">Select Designation</span></td>
								</tr>
								<tr>
									<td class="formLabel">Ownership (%)age:</td>
									<td class="formLabel">Usage (%)age:</td>
									<td class="formLabel" colspan="2">Land Tenure (Ownership)</td>
								</tr>
								<tr>
									<td><span class="formSingleLineBox" style="width:145px;">Ownership (%)age</span></td>
									<td><span class="formSingleLineBox" style="width:145px;">Usage (%)age</span></td>
									<td colspan="2"><span class="formSingleLineBox">Select Land Tenure</span></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td class="formLabel" colspan="2">
									<input type="checkbox">
									&nbsp;&nbsp;&nbsp;Is Registered?</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<td><span class="formSingleLineBox" style="width:145px;"> <a class="LinkInBox" href="#">Block</a></span></td>
									<td><span class="formSingleLineBox" style="width:145px;"><a class="LinkInBox" href="#">Plot</a></span></td>
								</tr>
								<tr>
									<td colspan="2"><a class="saveButtonArea" href="#">Save / Finish</a></td>
									<td colspan="2">
										<span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=ValuationDoc&Tag=Land&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
										<span class="formLinks"><a href="#">Photos</a></span>
									</td>
								</tr>
							</table>
							<form>
								<fieldset class="fieldset" style="width:900px; margin:15px 0px;;">
									<legend class="legend" style="width:200px;">
										<span class="legendText" >Land Assessment:</span>
									</legend>
									<table class="detailGrid" style="width:850px; margin-top:10px;">
										<tr>
											<td class = "detailGridHead">Land Tenure</td>
											<td class = "detailGridHead">Designation</td>
											<td  class = "detailGridHead">Total Area</td>
											<td  class = "detailGridHead">Land Rate</td>
											<td  class = "detailGridHead">Disturbance</td>
											<td  class = "detailGridHead">Total Award</td>
											<td  class = "detailGridHead" colspan="2">Modify</td>
										</tr>
										<tr>
											<td>Land Owner</td>
											<td>LO</td>
											<td>2.3560</td>
											<td>30,000,000</td>
											<td>10,000,000</td>
											<td>56,000,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
								</fieldset>
							</form>
							<form>
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
											<!-- td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td -->
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
							</form>
						</div>

						<!-- This is the Crop Valuation Screen -->
						<div id="Crops" class="tab-pane fade">
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

						<!-- This is the improvements Screen -->
						<div id="Improvements" class="tab-pane fade">
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

						<!-- Consolidated Financials -->
						<div id="Financials" class="tab-pane fade">

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