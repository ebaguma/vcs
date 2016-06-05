<! doctype html>

<?php
    
    ob_start(NULL, 0, PHP_OUTPUT_HANDLER_CLEANABLE);
    
    if(isset($_GET['LogOut'])){ LogOut(); }
    
?>

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

		?>

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
								<form>
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
												<td><span class="formSingleLineBox" style="width:250px;">Enter Job Name <a class="LinkInBox" href="#">New</a></span></td>
												<td rowspan="4"><span class="formMultiLineBox" style="width:300px; height:125px;">Enter Any Other Remarks</span></td>
											</tr>
											<tr>
												<td class="formLabel"> Job Sector: </td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter Sector Name:</span></td>
											</tr>
											<tr>
												<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
											</tr>
										</table>
										<table class="detailGrid" style="width:560px; margin-top:25px;">
											<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Occupation:</td>
												<td class = "detailGridHead">Job Sector:</td>
												<td class = "detailGridHead" colspan="2">Modify:</td>
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