<! doctype html>
    
    <?php
    
    ob_start();
    
    if(isset($_GET['LogOut'])){ LogOut(); }
        
    if (isset($_GET['HHID'])) { SelectPap(); }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'Read') { LoadPapBasicInfo(); }
    
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
		
		<?php
		
		function LoadPapBasicInfo(){
		    include_once ('../code/code_pap_basic_info.php');

            $pap_basic_info = new PapBasicInfo();
            
            $pap_basic_info -> selected_project_id = $_GET['ProjectID'];
            $pap_basic_info -> selected_project_code = $_GET['ProjectCode'];
            
            if (isset($_GET['HHID'])) { $pap_basic_info -> pap_hhid = $_GET['HHID']; } 
            else if (session_status() == PHP_SESSION_NONE) { session_start(); $pap_basic_info -> pap_hhid = $_SESSION['session_pap_hhid']; } 
            else if (session_status() == PHP_SESSION_ACTIVE) { $pap_basic_info -> pap_hhid = $_SESSION['session_pap_hhid']; }
            
            $pap_basic_info -> LoadBasicInfo();
            
            $GLOBALS['pap_hhid'] = $pap_basic_info -> pap_hhid;
            $GLOBALS['pap_name'] = $pap_basic_info -> pap_name;
            #$this -> pap_dob = $row -> DOB;
            #$this -> pap_sex = $row -> SEX;
            $GLOBALS['pap_plot_ref'] = $pap_basic_info -> pap_plot_ref;
            #$this -> pap_birth_place = $row -> BIRTH_PLACE;
            #$this -> pap_is_married = $row -> IS_MARRIED;
            #$this -> pap_tribe_id = $row -> TRIBE_ID;
            #$this -> pap_religion_id = $row -> RELGN_ID;
            #$this -> pap_occupation_id = $row -> OCCUPN_ID;
            #$this -> pap_status_id = $row -> PAP_STATUS_ID;

            
		}
		
		function BindTribe(){
		    include_once ('../code/code_pap_basic_info.php');
            $bind_pap_tribes = new PapBasicInfo();
            $bind_pap_tribes -> selected_project_id = $_GET["ProjectID"];
            $bind_pap_tribes -> BindTribe();
		}

        function BindReligion(){
            include_once ('../code/code_pap_basic_info.php');
            $bind_pap_religion = new PapBasicInfo();
            $bind_pap_religion -> selected_project_id = $_GET["ProjectID"];
            $bind_pap_religion -> BindReligion();
        }
        
        function BindOccupation(){
            include_once ('../code/code_pap_basic_info.php');
            $bind_pap_occupation = new PapBasicInfo();
            $bind_pap_occupation -> selected_project_id = $_GET["ProjectID"];
            $bind_pap_occupation -> BindOccupation();
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
								<table class="formTable">
									<tr>
										<td class="formLabel">HHID:</td>
										<td class="formLabel">Reference Number</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_hhid'])) { echo $GLOBALS['pap_hhid']; } ?>" name="HHID" />
										</span></td>
										<td><span class="formSingleLineBox">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_plot_ref'])) { echo $GLOBALS['pap_plot_ref']; } ?>" name="PlotRef" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">PAP Name:</td>
									</tr>
									<tr>
										<td colspan="2" ><span class="formSingleLineBox" style="width:610px;">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_name'])) { echo $GLOBALS['pap_name']; } ?>" name="PapName" style="width: 580px;" /> 
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Date Of Birth:</td>
										<td class="formLabel">Place Of Birth:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
											<input title="DD/MM/YYYY" type="text" id="birth_date" value="<?php if (isset($project_name)) { echo $start_date; } ?>" placeholder="DD/MM/YYYY" name="BirthDate" readonly />
										</span></td>
										<td><span class="formSingleLineBox">
											<input type="text" value="<?php # if (isset($GLOBALS['pap_name'])) { echo $GLOBALS['pap_name']; } ?>" name="BirthPlace" placeholder="Enter Birth Place" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Sex:</td>
										<td class="formLabel">Marital Status:</td>
									</tr>
									<tr>
										<td><span class="formDropDownBox">
											<select name="PapType" onchange="" >
                                                <option value="">-- Select Sex --</option>
                                                <option value="IND" <?php # if (isset($GLOBALS['pap_type']) && $GLOBALS['pap_type'] == 'IND') { echo 'selected'; }  ?> >Female</option>
                                                <option value="INS" <?php # if (isset($GLOBALS['pap_type']) && $GLOBALS['pap_type'] == 'INS') { echo 'selected'; }  ?>>Male</option> 
                                        	</select>
										</span></td>
										<td><span class="formDropDownBox">
											<select name="PapType" onchange="" >
                                                <option value="">-- Select Status --</option>
                                                <option value="IND" <?php # if (isset($GLOBALS['pap_type']) && $GLOBALS['pap_type'] == 'IND') { echo 'selected'; }  ?> >Married</option>
                                                <option value="INS" <?php # if (isset($GLOBALS['pap_type']) && $GLOBALS['pap_type'] == 'INS') { echo 'selected'; }  ?>>Single</option> 
                                        	</select>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Tribe:</td>
										<td class="formLabel">Religion:</td>
									</tr>
									<tr>
										<td><span class="formDropDownBox">
										    <select name="Tribes" id="SelectTribe" >
                                               <option value="">-- Select Tribe --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['TribeID'])) { BindTribe(); } ?>
                                            </select><a class="LinkInBox" href="#">New</a>
										</span></td>
										<td><span class="formDropDownBox">
										    <select name="Religions" id="SelectReligion" >
                                                <option value="">-- Select Religion --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['ReligionID'])) { BindReligion(); } ?>
                                            </select><a class="LinkInBox" href="#">New</a>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Occupation:</td>
										<td class="formLabel">Phone Number:</td>
									</tr>
									<tr>
										<td><span class="formDropDownBox">
										    <select name="Occupation" id="SelectOccupation" >
                                                <option value="">-- Select Occupation --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['OccupnID'])) { BindOccupation(); } ?>
                                            </select><a class="LinkInBox" href="#">New</a>
										</span></td>
										<td><span class="formSingleLineBox">
											<input type="text" value="<?php # if (isset($GLOBALS['pap_name'])) { echo $GLOBALS['pap_name']; } ?>" name="ContactNo" placeholder="Enter Phone No" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Email Address:</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">
											<input type="text" value="<?php # if (isset($GLOBALS['pap_name'])) { echo $GLOBALS['pap_name']; } ?>" name="ContactEmail" style="width: 580px;" placeholder="Enter Contact Email" />
										</span></td>
									</tr>
									<tr>
										<td><span class="saveButtonArea"> <input type="submit" value="Update" name="UpdateBasicInfo"/></span></td>
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
										<td><span class="formDropDownBox" >
											<select name="Occupation" id="SelectOccupation" >
                                                <option value="" >-- Select District --</option>
                                            </select><a class="LinkInBox" href="#" >New</a>
                                            </span></td>
										<td><span class="formDropDownBox">
											<select name="Occupation" id="SelectOccupation" >
                                                <option value="" >-- Select Sub County --</option>
                                            </select><a class="LinkInBox" href="#" >New</a>
                                            </span></td>
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
		
		<script src="js/date_picker/pikaday.js"></script>
		<script>
			var picker = new Pikaday({
				field : document.getElementById('birth_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1980, 0, 1),
				maxDate : new Date(2050, 12, 31),
				yearRange : [1980, 2050]
			});
		</script>

		</body>
</html>