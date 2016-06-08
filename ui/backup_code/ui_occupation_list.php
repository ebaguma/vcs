<?php
ob_start();
?>

<!doctype html><head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="Me" content="ProjectList">
	<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;OCCUPATION List</title>

	<?php
    include ('ui_popup_header.php');
	?>

	<?php

    function CheckReturnUser() {
        $time = $_SERVER['REQUEST_TIME'];

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (session_status() == PHP_SESSION_ACTIVE && $time < $_SESSION['Expire']) {
            if (($time - $_SESSION['Last_Activity']) < 1800) {
                //isset($_SESSION['session_user_id'])
                include ('../code/code_index.php');
                $CheckReturnUser = new LogInOut();
                $CheckReturnUser -> user_id = $_SESSION['session_user_id'];
                $CheckReturnUser -> CheckLoginStatus();
                if ($CheckReturnUser -> return_session_id == session_id() && $CheckReturnUser -> login_status == "TRUE") {
                    //header('Location: ui/ui_project_list.php?PageNumber=1');
                    echo 'CheckThis()';
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

    function ReadProjects() {
        include_once ('../code/code_occupation_list.php');

        echo '<script>ShowNavigation();</script>';

        $occupation = new OccupationList();

        //Check if Page Number is set
        if (isset($_GET['PageNumber'])) {
            $occupation -> load_page = $_GET['PageNumber'];
        } else {
            $_GET['PageNumber'] = 1;
        }

        //set pagination parameters
        $occupation -> ReadPageParams();
        global $num_pages;
        $num_pages = $occupation -> read_last_page;

        //Handling grid pages and navigation
        if ($_GET['PageNumber'] == 1) {
            $occupation -> record_num = 0;
            $occupation -> data_offset = 0;
        } else if ($_GET['PageNumber'] <= $occupation -> read_last_page) {
            $occupation -> data_offset = ($_GET['PageNumber'] - 1) * $occupation -> page_rows;
            $occupation -> record_num = ($_GET['PageNumber'] - 1) * $occupation -> page_rows; ;
        } else {
            echo '<script>alert("Page Is Out Of Range");</script>';
            $_GET['PageNumber'] = 1;
            $occupation -> record_num = 0;
            $occupation -> data_offset = 0;
        }

        if (($_GET['PageNumber'] + 1) <= $occupation -> read_last_page) {
            global $next_page;
            $next_page = $_GET['PageNumber'] + 1;
        } else {
            $next_page = 1;
        }

        if (($_GET['PageNumber'] - 1) >= 1) {
            global $prev_page;
            $prev_page = $_GET['PageNumber'] - 1;
        } else {
            $prev_page = 1;
        }

        //Loading Projects
        $occupation -> LoadOccupation();
    }

    function SelectProject() {
        session_start();
        if (isset($_GET["OccupationID"])) {
            $_SESSION["select_occupation_id"] = $_GET["OccupationID"];
        }
        header('Location: ui_occupation_detail.php?OccupationID=' . $_SESSION["select_occupation_id"]);
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
    if (isset($_GET['logout'])) {
        LogOut();
    }
	?>

<!-- @formatter:off -->
	
<div id="ContentP" class="ContentParent" style="top: 30px; height:600px;">
		<div class="Content">
			<div class="ContentTitle2">
				Edite Occupation Selected
			</div>
			<div class="SearchPap">
				<form  autocomplete="false" >
					<fieldset class="fieldset" style="height:130px; width:1000px;">
						<legend class="legend" style="width:250px;">
							<span class="legendText" > Search For Occupation </span>
						</legend>
						<table>
							<tr>
								<td class="formLabel">Name</td>
								<td class="formLabel">any word</td>
								<td class="formLabel" style="display:none;">Other Details</td>
							</tr>
							<tr>
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" autocomplete="false" >
									<td><span class="formSingleLineBox">
										<input type="hidden" name="Mode" value="Search" />
										<input type="hidden" name="PageNumber" value="1" />
										<input type="text" id="Occupation" name="OccupationName" class="formSingleLineInput"  placeholder="Search By Occupation Name" style="width:200px;" value="<?php
                                        if (isset($_GET['OccupationName'])) {echo $_GET['OccupationName'];
                                        }
										?>" />
										<a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&PageNumber=1'; ?>" class="LinkInBox" >Reset</a></span></td>
									<td><span class="formSingleLineBox">
										<input type="text" name="OccupationOther" class="formSingleLineInput" placeholder="Search Any word" style="width:200px;" value="<?php
                                        if (isset($_GET['OccupationOther'])) {echo $_GET['OccupationOther'];
                                        }
										?>" />
										<a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&PageNumber=1'; ?>" class="LinkInBox" >Reset</a></span></td>
									<td><span class="formSingleLineBox" style="display:none;">Search By Other Details</span></td>
									<input type="submit" style="position: absolute; left: -99999px;" />
								</form>
							</tr>
						</table>
					</fieldset>
				</form>
			</div>
			<div class="GridArea">
				<form>
					<fieldset class="fieldset" style="height:425px; width:1000px;">
						<legend class="legend" style="width:250px;">
							<span class="legendText" > Summary Project Portfolio </span>
						</legend>

						<!-- <table class="detailGrid" style="width:750px;"> -->
						<table class="detailGrid" style="width:900px; margin: 20px 10px; font-size: 15px">
							<tr>
								<td class = "detailGridHead">#</td>
								<td  class = "detailGridHead">Name:</td>
								<td class = "detailGridHead">Details:</td>
								<td class = "detailGridHead">Project Manager:</td>
								<td class = "detailGridHead">Created:</td>
								<td class = "detailGridHead">Updated:</td>
							</tr>
							
							<!-- @formatter:on -->
							<?php 
                            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['Mode']) && $_GET['Mode'] == 'Read') {
                                //ReadProjects();

                               include_once ('../code/code_occupation_list.php');

                                echo '<script>ShowNavigation();</script>';

                                $occupation = new OccupationList();

                                //Check if Page Number is set
                                if (isset($_GET['PageNumber'])) {
                                     $occupation -> load_page = $_GET['PageNumber'];
                                } else {
                                    $_GET['PageNumber'] = 1;
                                }

                                //set pagination parameters
                                 $occupation -> ReadPageParams();
                                //global $num_pages;
                                $num_pages =  $occupation -> read_last_page;

                                //Handling grid pages and navigation
                                if ($_GET['PageNumber'] == 1) {
                                     $occupation -> record_num = 0;
                                     $occupation -> data_offset = 0;
                                } else if ($_GET['PageNumber'] <= $projects -> read_last_page) {
                                     $occupation -> data_offset = ($_GET['PageNumber'] - 1) *  $occupation -> page_rows;
                                     $occupation -> record_num = ($_GET['PageNumber'] - 1) *  $occupation -> page_rows; ;
                                } else {
                                    echo '<script>alert("Page Is Out Of Range");</script>';
                                    $_GET['PageNumber'] = 1;
                                     $occupation -> record_num = 0;
                                     $occupation -> data_offset = 0;
                                }

                                if (($_GET['PageNumber'] + 1) <= $occupation -> read_last_page) {
                                    //global $next_page;
                                    $next_page = $_GET['PageNumber'] + 1;
                                } else {
                                    $next_page = 1;
                                }

                                if (($_GET['PageNumber'] - 1) >= 1) {
                                    //global $prev_page;
                                    $prev_page = $_GET['PageNumber'] - 1;
                                } else {
                                    $prev_page = 1;
                                }

                                //Loading Projects
                                 $occupation -> LoadOccupation();

                            } else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['Mode']) && $_GET['Mode'] == 'Search') {
                                //SearchProjects();
                              if (isset($_GET['OccupationName']) && $_GET['OccupationName'] != "" && isset($_GET['OccupationOther']) && $_GET['OccupationOther'] != "") {
                                    echo '<script>alert("You can only search by one item!");</script>';
                                    unset($_GET['OccupationName']);
                                    $_GET['OccupationName'] = "";
                                    unset($_GET['OccupationOther']);
                                    $_GET['OccupationOther'] = "";
                                    $_GET['Mode'] = 'Read';
                                    $_GET['PageNumber'] = 1;
                                    ReadProjects();
                                } else {
                                    include_once ('../code/code_occupation_list.php');

                                    $occupation = new OccupationList();

                                    if (isset($_GET['PageNumber'])) {
                                        $occupation -> search_load_page = $_GET['PageNumber'];
                                    } else {
                                        $_GET['PageNumber'] = 1;
                                    }

                                    // set pagination parameters
                                    if (isset($_GET['OccupationName'])) {
                                        $occupation -> search_occ_name = $_GET['OccupationName'];
                                    } else {
                                        $occupation -> search_occ_name = "";
                                    }
                                    if (isset($_GET['OccupationOther'])) {
                                        $occupation -> search_occ_other = $_GET['OccupationOther'];
                                    } else {
                                        $occupation -> search_occ_other = "";
                                    }
                                    $occupation -> SearchPageParams();
                                    global $num_pages;
                                    $num_pages = $occupation -> search_last_page;

                                    // Handling grid pages and navigation
                                    if ($occupation -> search_load_page == 1) {
                                        $occupation -> search_record_num = 0;
                                        $occupation -> search_data_offset = 0;
                                    } else if ($occupation -> search_load_page <= $occupation -> search_last_page) {
                                        $occupation -> search_data_offset = ($occupation -> search_load_page - 1) * $occupation -> page_rows;
                                        $occupation -> search_record_num = ($occupation -> search_load_page - 1) * $occupation -> page_rows;
                                        ;
                                    } else {
                                        echo '<script>alert("Empty Resultset !");</script>';
                                        $occupation -> search_load_page = 1;
                                        $occupation -> search_record_num = 0;
                                        $occupation -> search_data_offset = 0;
                                    }

                                    if (($occupation -> search_load_page + 1) <= $occupation -> search_last_page) {
                                        global $next_page;
                                        $next_page = $occupation -> search_load_page + 1;
                                    } else {
                                        $next_page = 1;
                                    }

                                    if (($occupation -> search_load_page - 1) >= 1) {
                                        global $prev_page;
                                        $prev_page = $occupation -> search_load_page - 1;
                                    } else {
                                        $prev_page = 1;
                                    }

                                    //Loading Projects
                                    $occupation -> SearchOccupation();
                                }
                            } else {
                                //header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&PageNumber=1');
                                $_GET['Mode'] = "Read";
                                $_GET['PageNumber'] = 1;
                            }
							?>
						</table>
						<table id="GridNav" class="detailNavigation" style="margin: 20px 10px;">
							<tr>
								<td><a href="<?php
                                if (isset($_GET['OccupationName']) && $_GET['OccupationName'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&OccupationName=' . $_GET['OccupationName'] . '&PageNumber=' . $prev_page;
                                } else if (isset($_GET['OccupationOther']) && $_GET['OccupationOther'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&OccupationOther=' . $_GET['OccupationOther'] . '&PageNumber=' . $prev_page;
                                } else {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $prev_page;
                                }
								?>" >Previous</a></td>
								<td class="PageJump" style="width:70px;">
                                
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" autocomplete="false" >
									<input type="hidden" name="Mode" value="<?php
                                    if (isset($_GET['Mode'])) {echo $_GET['Mode'];
                                    } else {echo 'Read';
                                    }
									?>" />
									<input type="hidden" name="OccupationName" value="<?php
                                    if (isset($_GET['OccupationName'])) {echo $_GET['OccupationName'];
                                    } else {echo '';
                                    }
									?>" />
									<input type="hidden" name="OccupationOther" value="<?php
                                    if (isset($_GET['OccupationOther'])) {echo $_GET['OccupationOther'];
                                    } else {echo '';
                                    }
									?>" />
									<input name="PageNumber" type="text"
									value="<?php
                                    if (isset($_GET['PageNumber'])) {echo $_GET['PageNumber'];
                                    } else {echo 1;
                                    }
									?>" class="NavBoxes" />
									/&nbsp;&nbsp;<?php echo $num_pages;#$num_pages ?>
									<input type="submit" style="position: absolute; left: -99999px;" />
								</form></td>
                                
                                
								<td>
                                <a href="<?php
                                if (isset($_GET['OccupationName']) && $_GET['OccupationName'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&OccupationName=' . $_GET['OccupationName'] . '&PageNumber=' . $next_page;
                                } else if (isset($_GET['OccupationOther']) && $_GET['OccupationOther'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&OccupationOther=' . $_GET['OccupationOther'] . '&PageNumber=' . $next_page;
                                } else {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $next_page;
                                }
								?>" >Next</a></td>
							</tr>
						</table>

					</fieldset>
				</form>

			</div>
			<br>
		</div>
	</div>

	<?php
    include ('ui_footer.php');
	?>

	</body>
	</html>
