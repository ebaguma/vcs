<! doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="Me" content="Masters">
		<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Masters</title>

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
		?>
	<?php
        
        function LoadBioOccupation() {
            include_once ('../code/code_masters_BioID.php');
            $Masters_Occup = new MastersBio();
            #$project_clients -> select_project_id = $_GET["ProjectID"];
			
			//Check if Page Number is set
            if (isset($_GET['OccupPage'])) {
                $GLOBALS['load_page'] = $_GET['OccupPage'];
            } else {
                $GLOBALS['load_page'] = 1;
            }

            //set pagination parameters
            $Masters_Occup-> ReadPageParamsOccup();
            $GLOBALS['num_pages'] = $Masters_Occup -> occup_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['load_page'] == 1) {
                $Masters_Occup -> occup_record_num = 0;
                $Masters_Occup -> occup_data_offset = 0;
            } else if ($GLOBALS['load_page'] <= $Masters_Occup -> occup_last_page) {
                $Masters_Occup -> occup_data_offset = ($GLOBALS['load_page'] - 1) * $Masters_Occup -> occup_page_rows;
                $Masters_Occup -> occup_record_num = ($GLOBALS['load_page'] - 1) * $Masters_Occup -> occup_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['load_page'] = 1;
                $Masters_Occup -> occup_record_num = 0;
                $Masters_Occup -> occup_data_offset = 0;
            }

            if (($GLOBALS['load_page'] + 1) <= $Masters_Occup -> occup_last_page) {
                $GLOBALS['next_page'] = $GLOBALS['load_page'] + 1;
            } else {
                $GLOBALS['next_page'] = 1;
            }

            if (($GLOBALS['load_page'] - 1) >= 1) {
                $GLOBALS['prev_page'] = $GLOBALS['load_page'] - 1;
            } else {
                $GLOBALS['prev_page'] = 1;
            }

            //Loading Projects
            $Masters_Occup -> LoadBioOccupation();
        } 

       function SelectBioOccupation() {
            // echo '<script>alert(' . $_GET['OccupID'] . ');</script>';
            include_once ('../code/code_masters_BioID.php');
            $Masters_Occup = new MastersBio();
            $Masters_Occup -> occupation_id = $_GET['OccupID'];
            $Masters_Occup -> SelectBioOccupation();
            //$client_name = $project_client -> client_name;
            $GLOBALS['occupation_name'] = $Masters_Occup -> occupation_name;
            $GLOBALS['occupation_other'] = $Masters_Occup -> occupation_other;
        }

       function UpdateBioOccupation() {
             // echo '<script>alert(' . $_GET['OccupID'] . ');</script>';
            include_once ('../code/code_masters_BioID.php');
            $update_occup = new MastersBio();
            //$client_name = $project_client -> client_name;
            $update_occup -> occupation_id = $_POST['OccupID'];
            $update_occup -> occupation_name = $_POST['OccupName'];
            $update_occup -> occupation_other = $_POST['OccupOther'];
            

            $update_occup -> UpdateOccupation();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=ViewClients&ProjectID=' . $update_client -> select_project_id . '&ProjectCode=' . $update_client -> select_project_code . '&ClientID=' . $update_client -> client_id . '#Clients');
            exit();
        }

        function DeleteBioOccupation() {
            include_once ('../code/code_masters_BioID.php');
            $delete_occup = new MastersBio();
            $delete_occup -> occupation_id = $_GET['OccupID'];
            
            $delete_occup -> DeleteOccupation();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio');
            exit();
        }

        function InsertBioOccupation() {
            // echo '<script>alert(' . $_GET['OccupID'] . ');</script>';
            include_once ('../code/code_masters_BioID.php');
            $insert_occup = new MastersBio();
            //$client_name = $project_client -> client_name;
            $insert_occup -> occupation_name = $_POST['OccupName'];
            $insert_occup -> occupation_other = $_POST['OccupOther'];
           

            $insert_occup -> InsertOccup();
            unset($_POST);
            header('Refresh:0; url=ui_masters.php?Mode=Read&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio');
            
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

							<!-- First Form for Occupation -->
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form action="<?php
                            if ($_GET['Mode'] == 'ViewOccupation') {
                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=EditOccupation=' . $GLOBALS['occupation_name'] . '#Bio';
                            } else if ($_GET['Mode'] == 'Read') {
                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertOccupation=' . $GLOBALS['occupation_name'] . '#Bio';;
                            }
                            ?>" method="POST" autocomplete="off">  
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
			<td><span class="formSingleLineBox" style="width:250px;"> <input type="text" value=
			"<?php if (isset($GLOBALS['occupation_name'])) { echo $GLOBALS['occupation_name'];  }?>"
			name = "OccupName" placeholder="Enter Job Name" style="width:245px;" /></span></td>
             <input type="hidden" name="Mode" value="<?php echo 'Insert Occup'; ?>" />
            
            <td rowspan="4"><span class="formMultiLineBox" style="width:300px; height:125px;">
     		<input type="text" value=
            "<?php if (isset($GLOBALS['occupation_other'])) { echo $GLOBALS['occupation_other'];  }?>"
  			name = "OccupOther" placeholder="Enter Any Other Remarks" style="width:295px;" /></span></td>
	</tr>
											<tr>
												<td class="formLabel"> Job Sector: </td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter Sector Name:</span></td>
											</tr>
                                            
                                            
                                            
											<tr>
												<td>
                                                <span class="saveButtonArea">
												<input type="submit" value="<?php if ($_GET['Mode'] == 'ViewOccupations') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;" />
												<?php $new_occup = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&Occupations=' . $_GET['OccupID'] . '&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                                if ($_GET['Mode'] == 'ViewOccupations') {echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_occup . '>New Occupation</a></span>'; } ?>
                                                </span>
                                                </td>
                                                
											</tr>
                                            
										</table>
                                        
                                        
                                        
                                        
										<table class="detailGrid" style="width:560px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Occupation:</td>
												<td class = "detailGridHead">Job Sector:</td>
												<td class = "detailGridHead" colspan="2">Modify:</td>
											</tr>
											
                                            
                                            <?php 
										$uv=123;
										if ($uv==123) 
										{ LoadBioOccupation(); } ?>
                                            
										</table>
										<table class="detailNavigation">
                                        <tr>
                                        <td><a href="<?php
                                            if (isset($_GET['OccupID'])) {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewOccupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                            }
											?>" >Previous</a></td>
											<td class="PageJump" style="width: 70px;"> <?php echo $load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo $num_pages; ?></td>
											<td><a href="<?php
                                            if (isset($_GET['OccupID'])) {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewOccupations=' . $GLOBALS['occupation_name'] . '#Bio';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) .  '?Mode=Read&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                            }
											?>" >Next</a></td>
                                        
                                        
											
												
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

		
		<div id="FooterP" class="FooterParent" style="top: 1400px;">
			<div class="FooterContent">
				&copy;&nbsp;&nbsp;Copyright&nbsp;&nbsp;2015:&nbsp;&nbsp;Dataplus Systems Uganda
			</div>
		</div>


		</body>
</html>