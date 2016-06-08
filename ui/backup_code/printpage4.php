=============================================================================================================================
code
=============================================================================================================================

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
=============================================================================================================================
form
=============================================================================================================================

 <!-- Client Area Tab Starts here -->
                    <div id="Clients" class="tab-pane fade">
                        <p> This is the Project's Clients and Stakeholders Screen </p>

                        <!-- Main Client Form -->
                        <div class="left-form" style="width: 650px;">
                            <!-- @formatter:off -->
                            <form action="<?php
                            if ($_GET['Mode'] == 'ViewOccupation') {
                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=EditOccupation=' . $GLOBALS['occupation_name'] . '#Bio';
                            } else if ($_GET['Mode'] == 'Read') {
                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertOccupation=' . $GLOBALS['occupation_name'] . '#Bio';;
                            }
                            ?>" method="POST" autocomplete="off">    
                            <table class="formTable">
                                <tr>
												<td class="formLabel">Occupation Name:</td>
												<td class="formLabel">Other Details:</td>
								</tr>
                                <tr>
                                    <td>
                                        <span class="formSingleLineBox" style="width:250px;">
              <input type="text" value="<?php
                   if (isset($GLOBALS['occupation_name'])) { echo $GLOBALS['occupation_name'];  }?>"
              name = "OccupName" placeholder="Enter Job Name" style="width:245px;" /></span></td>
              
                  <input type="hidden" name="Mode" value="<?php echo 'Insert Client'; ?>" />

	<td rowspan="4"><span class="formMultiLineBox" style="width:300px; height:125px;">
    
    <input type="text" value="<?php
                   if (isset($GLOBALS['occupation_other'])) { echo $GLOBALS['occupation_other'];  }?>"
              name = "OccupOther" placeholder="Enter Any Other Remarks" style="width:295px;" /></span></td>
											</tr>
                                
                                
                                
                               <tr>
												<td class="formLabel"> Job Sector: </td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox" style="width:250px;">Enter Sector Name:</span></td>
											</tr>
										<tr>
											<td colspan="2"> <span class="saveButtonArea">
												<input type="submit" value="<?php if ($_GET['Mode'] == 'ViewOccupations') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;" />
												<?php $new_occup = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&Occupations=' . $_GET['OccupID'] . '&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                                if ($_GET['Mode'] == 'ViewOccupations') {echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_occup . '>New Occupation</a></span>'; } ?>
                                                </span></td>
											<td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
											class="formLinks"><a href="#">Photos</a></span></td>
										</tr>
									</table>
								</form>


=============================================================================================================================
table
=============================================================================================================================
					<div class="GridArea" style="width: 650px;">
									<table class="detailGrid" style="width: 625px;">
										<tr>
												<td class = "detailGridHead">#</td>
												<td  class = "detailGridHead">Occupation:</td>
												<td class = "detailGridHead">Job Sector:</td>
												<td class = "detailGridHead" colspan="2">Modify:</td>
											</tr>
										<!-- @formatter:on -->
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
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewOccupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) .  '?Mode=Read&Occupations=' . $GLOBALS['Occupation_name'] . '#Bio';
                                            }
											?>" >Next</a></td>
										</tr>
									</table>
								</div>
							</div>