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
		<meta name="Me" content="Bio">
		<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Bio Information</title>

		<?php

        include ('ui_header.php');

        function CheckReturnUser() {
            $time = $_SERVER['REQUEST_TIME'];

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (session_status() == PHP_SESSION_ACTIVE && $time < $_SESSION['Expire']) {
                if (($time - $_SESSION['Last_Activity']) < 1800) {
                    // isset($_SESSION['session_user_id'])
                    include ('../code/code_index.php');
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

                    session_unset();
                    session_destroy();
                    header('Location: ../index.php?Message=Inactive_Session_Expired');
                }
            } else {
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
					Social Economic, Bio Data
				</div>
				<br>
				<div class="container">
					<ul class="nav nav-tabs">
						<li class="inactive">
							<a >&nbsp;</a>
						</li>
						<li class="active">
							<a data-toggle="tab" href="#BasicInfo">Basic Info</a>
						</li>
						<li>
							<a data-toggle="tab" href="#PapAddress">Addresses</a>
						</li>
						<li>
							<a data-toggle="tab" href="#Family">Family Members</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
					</ul>
					<div class="tab-content">

						<!-- This is the Basic Info Screen -->
						<div id="BasicInfo" class="tab-pane active">
							<p>
								This is the Basic Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<table>
									<tr>
										<td class="formLabel">HHID:</td>
										<td class="formLabel">Reference Number</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">HHID</span></td>
										<td><span class="formSingleLineBox">Enter Reference Number</span></td>
									</tr>
									<tr>
										<td class="formLabel">PAP Name:</td>
									</tr>
									<tr>
										<td colspan="2" ><span class="formSingleLineBox" style="width:610px;">Enter PAP Name</span></td>
									</tr>
									<tr>
										<td class="formLabel">Date Of Birth:</td>
										<td class="formLabel">Place Of Birth:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select DOB</span></td>
										<td><span class="formSingleLineBox">Enter Place Of Birth</span></td>
									</tr>
									<tr>
										<td class="formLabel">Sex:</td>
										<td class="formLabel">Marital Status:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select Sex</span></td>
										<td><span class="formSingleLineBox">Select Marital Status</span></td>
									</tr>
									<tr>
										<td class="formLabel">Tribe:</td>
										<td class="formLabel">Religion:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select A Tribe</span></td>
										<td><span class="formSingleLineBox">Enter A Religion</span></td>
									</tr>
									<tr>
										<td class="formLabel">Occupation:</td>
										<td class="formLabel">Phone Number:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select Occupation</span></td>
										<td><span class="formSingleLineBox">Enter Phone Number</span></td>
									</tr>
									<tr>
										<td class="formLabel">Email Address:</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Email Address</span></td>
									</tr>
									<tr>
										<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>
							</div>
							<div style="width:300px; float:left; margin-top:10px; margin-left:20px;">
								<table  style="margin-bottom:20px; width:250px;">
									<tr>
										<td class="formLabel"></td>
									</tr>
									<tr>
										<td>
										<div class="PhotoBox">
											<img src="images/20150912_161516.png" width="250" height="250" alt=""/>
										</div></td>
									</tr>
									<tr>
										<td><span class="formLinks SideBar" ><a href="#">Upload Photo</a></span><span class="formLinks" ><a href="#">Delete Photo</a></span></td>
									</tr>
								</table>
								
							</div>
						</div>

						<!-- This is the PAP Address Screen -->
						<div id="PapAddress" class="tab-pane">
							<p>
								This is the PAP Address Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<table>
									<tr>
										<td class="formLabel">Plot No, Road:</td>
										<td class="formLabel">
										<input type="checkbox">
										&nbsp;&nbsp;Is Main Residence?</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Address Details</span></td>
									</tr>
									<tr>
										<td class="formLabel">District:</td>
										<td class="formLabel">Sub County Or Town:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select District<a class="LinkInBox" href="#">New</a></span></td>
										<td><span class="formSingleLineBox">Enter Town Details<a class="LinkInBox" href="#">New</a></span></td>
									</tr>
									<tr>
										<td class="formLabel">LC 1 Or Village:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Enter LC 1</span></td>
									</tr>
									<tr>
										<td><a class="saveButtonArea" href="#">Save / Finish </a></td>
									</tr>
								</table>
								<table class="detailGrid" style="width:560px; margin-top:25px;">
									<tr>
										<td class = "detailGridHead">#</td>
										<td  class = "detailGridHead">Address Details:</td>
										<td  class = "detailGridHead">Modify:</td>
									</tr>
									<tr>
										<td>1</td>
										<td>Karuma, Project</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>2</td>
										<td>Standard, Gauge, Railway</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>3</td>
										<td>Uganda, Kenya Oil, Pipeline</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>4</td>
										<td>Kasese, Kinshasa, Super Highway</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>5</td>
										<td>Entebbe, Express, Project</td>
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
							</div>
						</div>

						<!-- This is the family info screen -->
						<div id="Family" class="tab-pane">
							<p>
								This is the Family Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<table>
									<tr>
										<td class="formLabel">Family Member Name:</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td>
									</tr>
									<tr>
										<td class="formLabel">Sex:</td>
										<td class="formLabel">Relation Type:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select Sex</span></td>
										<td><span class="formSingleLineBox">Select Relation Type</span></td>
									</tr>
									<tr>
										<td class="formLabel">Date Of Birth:</td>
										<td class="formLabel">Place Of Birth:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select DOB</span></td>
										<td><span class="formSingleLineBox">Enter POB</span></td>
									</tr>
									<tr>
										<td class="formLabel">Tribe:</td>
										<td class="formLabel">Religion:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select Tribe</span></td>
										<td><span class="formSingleLineBox">Select Religion</span></td>
									</tr>
									<tr>
										<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>

								<table class="detailGrid" style="width:560px; margin-top:25px;">
									<tr>
										<td class = "detailGridHead">#</td>
										<td  class = "detailGridHead">Address Details:</td>
										<td  class = "detailGridHead">Age:</td>
										<td  class = "detailGridHead">Relation:</td>
										<td  class = "detailGridHead">Modify:</td>
									</tr>
									<tr>
										<td>1</td>
										<td>Karuma, Project</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>2</td>
										<td>Standard, Gauge, Railway</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>3</td>
										<td>Uganda, Kenya Oil, Pipeline</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>4</td>
										<td>Kasese, Kinshasa, Super Highway</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>5</td>
										<td>Entebbe, Express, Project</td>
										<td>30</td>
										<td>Son, Daughter</td>
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

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php include ('ui_footer.php'); ?>

		</body>
</html>