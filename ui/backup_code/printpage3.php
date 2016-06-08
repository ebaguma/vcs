

  <?php


   function LoadProjectClients() {
            include_once ('../code/code_masters_Bio.php');
            $Masters_Bio = new MastersBio();
            #$Masters_Bio -> select_occupation_id = $_GET["OccupationID"];

            if (isset($_GET['OccupationPage'])) {
                $GLOBALS['load_page'] = $_GET['OccupationPage'];
            } else {
                $GLOBALS['load_page'] = 1;
            }

            //set pagination parameters
            $Masters_Bio -> ReadPageParams();
            $GLOBALS['num_pages'] = $Masters_Bio -> occup_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['load_page'] == 1) {
                $Masters_Bio -> occup_record_num = 0;
                $Masters_Bio -> occup_data_offset = 0;
            } else if ($GLOBALS['load_page'] <= $Masters_Bio -> occup_last_page) {
                $Masters_Bio -> occup_data_offset = ($GLOBALS['load_page'] - 1) * $Masters_Bio -> occup_page_rows;
                $Masters_Bio -> occup_record_num = ($GLOBALS['load_page'] - 1) * $Masters_Bio -> occup_page_rows; ;
            } else {
                echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['load_page'] = 1;
                $Masters_Bio -> occup_record_num = 0;
                $Masters_Bio -> occup_data_offset = 0;
            }

            if (($GLOBALS['load_page'] + 1) <= $Masters_Bio -> occup_last_page) {
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
            $Masters_Bio -> LoadBioOccupation();
        }
		
		

?>







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
				if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ProjectID'])) { LoadBioOccupation(); }
										 #LoadBioOccupation(); ?>
									</table>
									<table class="detailNavigation">
									<!--<tr>
											<td><a href="<php
                                            if (isset($_GET['ClientID'])) {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients';
                                            }
											?>" >Previous</a></td>
											<td class="PageJump" style="width: 70px;"> <php echo $load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<php echo $num_pages; ?></td>
											<td><a href="<php
                                            if (isset($_GET['ClientID'])) {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients';
                                            }
											?>" >Next</a></td>
										</tr>
                                       -->
									</table>
								</div>
							</div>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                