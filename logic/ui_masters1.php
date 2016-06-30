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
}
if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewOccupation') {
	#LoadBioOccupation();
    SelectBioOccupation();   
}
if (isset($_GET['Mode']) && $_GET['Mode'] == 'EditOccupation') {
	UpdateBioOccupation();
}
if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertOccupation') {
	InsertBioOccupation();
}      
if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteOccupation') {
	DeleteBioOccupation();
}
        
        
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

<!--php include('printpage.php');?-->


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
											<tr>
												<td>1</td>
												<td>Karuma Project</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Standard Gauge Railway</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Uganda Kenya Oil Pipeline</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Kasese Kinshasa Super Highway</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Entebbe Express Project</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
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
											<tr>
												<td>1</td>
												<td>Karuma Project</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Standard Gauge Railway</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Uganda Kenya Oil Pipeline</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Kasese Kinshasa Super Highway</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Entebbe Express Project</td>
												<td></td>
												<td><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
											</tr>
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