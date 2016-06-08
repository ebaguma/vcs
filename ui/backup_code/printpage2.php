  <!-- Client Area Tab Starts here -->
                    <div id="Clients" class="tab-pane fade">
                        <p> This is the Project's Clients and Stakeholders Screen </p>

                        <!-- Main Client Form -->
                        <div class="left-form" style="width: 650px;">
                            <!-- @formatter:off -->
                            <form action="<?php
                            if ($_GET['Mode'] == 'ViewOccupation') {
                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=EditClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients';
                            } else if ($_GET['Mode'] == 'Read') {
                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients';
                            }
                            ?>" method="POST" autocomplete="off">    
                            <table class="formTable">
                                <tr>
                                    <td colspan="3" class="formLabel">Company Name</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span class="formSingleLineBox" style="width: 625px;">
                                            <input type="text" value="<?php
                                            if (isset($GLOBALS['client_name'])) {
                                                echo $GLOBALS['client_name'];
                                            }
                                                ?>" name = "ClientName" placeholder="Enter Client Name" style="width:600px;" /></span></td>
                                                <input type="hidden" name="ClientID" value="<?php echo $_GET['ClientID']; ?>" />
                                                <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                <input type="hidden" name="ClientPage" value="<?php echo $_GET['ClientPage']; ?>" />
                                                <input type="hidden" name="Mode" value="<?php echo 'Insert Client'; ?>" />
                                                
                                </tr>
                                <tr>
                                    <td class="formLabel">Company Number</td>
                                    <td class="formLabel">Company Email</td>
                                    <td class="formLabel">Company Website</td>
                                    
                                </tr>
                                <tr>
                                    <td><span class="formSingleLineBox" style="width: 200px;">
                                        <input type="text" value="<?php
                                        if (isset($GLOBALS['client_number'])) {
                                            echo $GLOBALS['client_number'];
                                        }
											?>" name = "ClientNumber" placeholder="Enter Client Number" style="width: 180px;" /></span></td>
                                    <td><span class="formSingleLineBox" style="width: 200px;">
                                        <input type="text" value="<?php
                                        if (isset($GLOBALS['client_email'])) {
                                            echo $GLOBALS['client_email'];
                                        }
											?>" name = "ClientEmail" placeholder="Enter Client Email" style="width: 180px;" /></span></td>
                                    <td><span class="formSingleLineBox" style="width: 200px;">
                                        <input type="text" value="<?php
                                        if (isset($GLOBALS['client_website'])) {
                                            echo $GLOBALS['client_website'];
                                        }
                                                ?>" name = "ClientWebsite" placeholder="Enter Website" style="width: 180px;" /></span></td>
                                </tr>
                                <tr>
                                    <td class="formLabel">Principal Contact</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span class="formSingleLineBox" style="width: 625px;">
                                            <input type="text" value="<?php
                                            if (isset($GLOBALS['client_contact_person'])) {
                                                echo $GLOBALS['client_contact_person'];
                                            }
                                                ?>" name = "PrincipalContact" placeholder="Enter Principal Contact" style="width:600px;" />
                                        </span></td>
                                </tr>
                                <tr>
                                    <td class="formLabel">Company Address</td>
                                </tr>
                                <tr>
                                    <td colspan="3" nowrap="nowrap">
                                        <span class="formMultiLineBox" style="width: 625px;">
                                            <!-- formatter:off -->
                                            <textarea type="text" name = "ClientAddr" placeholder="Enter Address"><?php
                                            if (isset($GLOBALS['client_addr'])) {echo $GLOBALS['client_addr'];
                                            }
                                            ?></textarea></span></td>
										</tr>
										<tr>
											<td colspan="2"> <span class="saveButtonArea">
												<input type="submit" value="<?php if ($_GET['Mode'] == 'ViewClients') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;" />
												<?php $new_client = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients';
                                                if ($_GET['Mode'] == 'ViewClients') {echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_client . '>New Client</a></span>'; } ?>
                                                </span></td>
											<td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
											class="formLinks"><a href="#">Photos</a></span></td>
										</tr>
									</table>
								</form>

								<!-- Grid Area Showing Client List -->
								<div class="GridArea" style="width: 650px;">
									<table class="detailGrid" style="width: 625px;">
										<tr>
											<td class="detailGridHead">#</td>
											<td class="detailGridHead">Company Name</td>
											<!--  class="detailGridHead">Company Address</td -->
											<td class="detailGridHead">Company Email</td>
											<td class="detailGridHead">Company Website</td>
											<td class="detailGridHead">Edit:</td>
										</tr>
										<!-- @formatter:on -->
										<?php if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ProjectID'])) { LoadProjectClients(); } ?>
									</table>
									<table class="detailNavigation">
										<tr>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients';
                                            }
											?>" >Previous</a></td>
											<td class="PageJump" style="width: 70px;"> <?php echo $load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo $num_pages; ?></td>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients';
                                            } else {
                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients';
                                            }
											?>" >Next</a></td>
										</tr>
									</table>
								</div>
							</div>

							<!-- Side Form for Client Staff -->
							<div style="width: 325px; float: left; margin-top: 10px; margin-left: 10px;">
								<!-- form -->
								<fieldset class="fieldset" style="padding: 20px; width: 350px;">
									<legend class="legend" style="width: 120px;">
										<span class="legendText">Client Staff:</span>
									</legend>

									<form action="<?php
                                    if (!isset($_GET['StaffID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertStaff&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '#Clients';
                                    } else if (isset($_GET['StaffID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateStaff&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '#Clients';
                                    }
										?>" method="POST" autocomplete="off" >
										<table class="formTable" style="width:300px; margin-bottom: 20px;">
											<tr>
												<td class="formLabel">Staff Name:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">
													<input type="text" name="StaffName" value="<?php
                                                    if (isset($GLOBALS['staff_name'])) {echo $GLOBALS['staff_name'];
                                                    }
													?>" placeholder="Enter Staff Name" />
													<input type="hidden" name="ClientID" value="<?php echo $_GET['ClientID']; ?>" />
													<input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
													<input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
													<input type="hidden" name="StaffID" value="<?php echo $_GET['StaffID']; ?>" />
													<!-- a class="LinkInBox" href="#">New</a --></span></td>
											</tr>
											<tr>
												<td class="formLabel">Mobile Number:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">
													<input type="text" name="StaffNumber" value="<?php
                                                    if (isset($GLOBALS['staff_number'])) {echo $GLOBALS['staff_number'];
                                                    }
													?>" placeholder="Enter Staff Number" />
												</span></td>
											</tr>
											<tr>
												<td class="formLabel">Email Address:</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">
													<input type="text" name="StaffEmail" value="<?php
                                                    if (isset($GLOBALS['staff_email'])) {echo $GLOBALS['staff_email'];
                                                    }
												?>" placeholder="Enter Staff Email" />
												</span></td>
											</tr>
											<tr>
												<td class="formLabel">Staff Role</td>
											</tr>
											<tr>
												<td><span class="formSingleLineBox">
													<input type="text" name="StaffRole" value="<?php
                                                    if (isset($GLOBALS['staff_role'])) {echo $GLOBALS['staff_role'];
                                                    }
													?>"  placeholder="Enter Staff Role" />
												</span></td>
											</tr>
											<tr>
												<td><span class="saveButtonArea" href="#"> <!-- @formatter:off -->
													<?php
                                                    if ($_GET['Mode'] == 'ViewClients' && !isset($_GET['StaffID'])) { echo '<input type="submit" value="Add Staff" style="float:left;"/>';
                                                    } else if (isset($_GET['StaffID'])) { echo '<input type="submit" value="Update" style="float:left;"/>';
                                                    }
													?>
													<?php
                                                    if (isset($_GET['StaffID'])) {
                                                        $new_staff = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '#Clients';
                                                        echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_staff . '>New Staff</a></span>';
                                                    }
													?></span></td>
											</tr>
										</table>
									</form>

									<!-- Grid Area showing client staff -->
									<table class="detailGridSmall" style="width: 300px; margin-top: 25px;">
										<tr>
											<td class="detailGridHead">#</td>
											<td class="detailGridHead">Staff Name:</td>
											<!-- td class="detailGridHead">Role:</td -->
											<td class="detailGridHead">Modify:</td>
										</tr>
										<?php
                                        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ClientID'])) {
                                            LoadClientStaff();
                                        }?>
									</table>

									<table class="detailNavigation">
										<tr>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $_GET['ClientPage'] . '&StaffPage=' . $GLOBALS['staff_prev_page'] . '#Clients';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients';
                                            }
											?>" >Previous</a></td>
											<td class="PageJump" style="width: 70px;"> <?php
                                            if (isset($GLOBALS['staff_load_page'])) {echo $GLOBALS['staff_load_page'];
                                            } else { echo 1;
                                            }
											?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php
                                            if (isset($GLOBALS['staff_num_pages'])) { echo $GLOBALS['staff_num_pages'];
                                            } else { echo 0;
                                            }
											?></td>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $_GET['ClientPage'] . '&StaffPage=' . $GLOBALS['staff_next_page'] . '#Clients';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients';
                                            }
											?>" >Next</a></td>
										</tr>
									</table>
								</fieldset>
								<!-- /form -->
							</div>
						</div>