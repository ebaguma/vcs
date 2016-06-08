<?php
ob_start();
?>

<!doctype html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="Me" content="ProjectList">
	<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;PAP List</title>

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
        include_once ('../code/code_project_list.php');

        echo '<script>ShowNavigation();</script>';

        $projects = new ProjectList();

        //Check if Page Number is set
        if (isset($_GET['PageNumber'])) {
            $projects -> load_page = $_GET['PageNumber'];
        } else {
            $_GET['PageNumber'] = 1;
        }

        //set pagination parameters
        $projects -> ReadPageParams();
        global $num_pages;
        $num_pages = $projects -> read_last_page;

        //Handling grid pages and navigation
        if ($_GET['PageNumber'] == 1) {
            $projects -> record_num = 0;
            $projects -> data_offset = 0;
        } else if ($_GET['PageNumber'] <= $projects -> read_last_page) {
            $projects -> data_offset = ($_GET['PageNumber'] - 1) * $projects -> page_rows;
            $projects -> record_num = ($_GET['PageNumber'] - 1) * $projects -> page_rows; ;
        } else {
            echo '<script>alert("Page Is Out Of Range");</script>';
            $_GET['PageNumber'] = 1;
            $projects -> record_num = 0;
            $projects -> data_offset = 0;
        }

        if (($_GET['PageNumber'] + 1) <= $projects -> read_last_page) {
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
        $projects -> LoadProjects();
    }

    function SelectProject() {
        session_start();
        if (isset($_GET["ProjectID"])) {
            $_SESSION["select_project_id"] = $_GET["ProjectID"];
        }
        header('Location: ui_project_detail.php?ProjectID=' . $_SESSION["select_project_id"]);
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
				Change Project Selection
			</div>
			<div class="SearchPap">
				<form  autocomplete="false" >
					<fieldset class="fieldset" style="height:130px; width:1000px;">
						<legend class="legend" style="width:250px;">
							<span class="legendText" > Search By Project Details </span>
						</legend>
						<table>
							<tr>
								<td class="formLabel">Project Name</td>
								<td class="formLabel">Project Code</td>
								<td class="formLabel" style="display:none;">Other Details</td>
							</tr>
							<tr>
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" autocomplete="false" >
									<td><span class="formSingleLineBox">
										<input type="hidden" name="Mode" value="Search" />
										<input type="hidden" name="PageNumber" value="1" />
										<input type="text" id="Project" name="ProjectName" class="formSingleLineInput"  placeholder="Search By Project Name" style="width:200px;" value="<?php
                                        if (isset($_GET['ProjectName'])) {echo $_GET['ProjectName'];
                                        }
										?>" />
										<a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&PageNumber=1'; ?>" class="LinkInBox" >Reset</a></span></td>
									<td><span class="formSingleLineBox">
										<input type="text" name="ProjectCode" class="formSingleLineInput" placeholder="Search By Project Code" style="width:200px;" value="<?php
                                        if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode'];
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
								<td  class = "detailGridHead">Project Name:</td>
								<td class = "detailGridHead">Project Code:</td>
								<td class = "detailGridHead">Project Manager:</td>
								<td class = "detailGridHead">Start Date:</td>
								<td class = "detailGridHead">End Date:</td>
							</tr>
							
							<!-- @formatter:on -->
							<?php
                            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['Mode']) && $_GET['Mode'] == 'Read') {
                                //ReadProjects();

                                include_once ('../code/code_project_list.php');

                                echo '<script>ShowNavigation();</script>';

                                $projects = new ProjectList();

                                //Check if Page Number is set
                                if (isset($_GET['PageNumber'])) {
                                    $projects -> load_page = $_GET['PageNumber'];
                                } else {
                                    $_GET['PageNumber'] = 1;
                                }

                                //set pagination parameters
                                $projects -> ReadPageParams();
                                //global $num_pages;
                                $num_pages = $projects -> read_last_page;

                                //Handling grid pages and navigation
                                if ($_GET['PageNumber'] == 1) {
                                    $projects -> record_num = 0;
                                    $projects -> data_offset = 0;
                                } else if ($_GET['PageNumber'] <= $projects -> read_last_page) {
                                    $projects -> data_offset = ($_GET['PageNumber'] - 1) * $projects -> page_rows;
                                    $projects -> record_num = ($_GET['PageNumber'] - 1) * $projects -> page_rows; ;
                                } else {
                                    echo '<script>alert("Page Is Out Of Range");</script>';
                                    $_GET['PageNumber'] = 1;
                                    $projects -> record_num = 0;
                                    $projects -> data_offset = 0;
                                }

                                if (($_GET['PageNumber'] + 1) <= $projects -> read_last_page) {
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
                                $projects -> LoadProjects();

                            } else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['Mode']) && $_GET['Mode'] == 'Search') {
                                //SearchProjects();
                                if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "" && isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") {
                                    echo '<script>alert("You can only search by one item!");</script>';
                                    unset($_GET['ProjectName']);
                                    $_GET['ProjectName'] = "";
                                    unset($_GET['ProjectCode']);
                                    $_GET['ProjectCode'] = "";
                                    $_GET['Mode'] = 'Read';
                                    $_GET['PageNumber'] = 1;
                                    ReadProjects();
                                } else {
                                    include_once ('../code/code_project_list.php');

                                    $projects = new ProjectList();

                                    if (isset($_GET['PageNumber'])) {
                                        $projects -> search_load_page = $_GET['PageNumber'];
                                    } else {
                                        $_GET['PageNumber'] = 1;
                                    }

                                    // set pagination parameters
                                    if (isset($_GET['ProjectName'])) {
                                        $projects -> search_proj_name = $_GET['ProjectName'];
                                    } else {
                                        $projects -> search_proj_name = "";
                                    }
                                    if (isset($_GET['ProjectCode'])) {
                                        $projects -> search_proj_code = $_GET['ProjectCode'];
                                    } else {
                                        $projects -> search_proj_code = "";
                                    }
                                    $projects -> SearchPageParams();
                                    global $num_pages;
                                    $num_pages = $projects -> search_last_page;

                                    // Handling grid pages and navigation
                                    if ($projects -> search_load_page == 1) {
                                        $projects -> search_record_num = 0;
                                        $projects -> search_data_offset = 0;
                                    } else if ($projects -> search_load_page <= $projects -> search_last_page) {
                                        $projects -> search_data_offset = ($projects -> search_load_page - 1) * $projects -> page_rows;
                                        $projects -> search_record_num = ($projects -> search_load_page - 1) * $projects -> page_rows;
                                        ;
                                    } else {
                                        echo '<script>alert("Empty Resultset !");</script>';
                                        $projects -> search_load_page = 1;
                                        $projects -> search_record_num = 0;
                                        $projects -> search_data_offset = 0;
                                    }

                                    if (($projects -> search_load_page + 1) <= $projects -> search_last_page) {
                                        global $next_page;
                                        $next_page = $projects -> search_load_page + 1;
                                    } else {
                                        $next_page = 1;
                                    }

                                    if (($projects -> search_load_page - 1) >= 1) {
                                        global $prev_page;
                                        $prev_page = $projects -> search_load_page - 1;
                                    } else {
                                        $prev_page = 1;
                                    }

                                    //Loading Projects
                                    $projects -> SearchProjects();
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
<<<<<<< HEAD
								<td><a href="<?php
                                if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectName=' . $_GET['ProjectName'] . '&PageNumber=' . $prev_page;
                                } else if (isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PageNumber=' . $prev_page;
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
									<input type="hidden" name="ProjectName" value="<?php
                                    if (isset($_GET['ProjectName'])) {echo $_GET['ProjectName'];
                                    } else {echo '';
                                    }
									?>" />
									<input type="hidden" name="ProjectCode" value="<?php
                                    if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode'];
                                    } else {echo '';
                                    }
									?>" />
									<input name="PageNumber" type="text"
									value="<?php
                                    if (isset($_GET['PageNumber'])) {echo $_GET['PageNumber'];
                                    } else {echo 1;
                                    }
									?>" class="NavBoxes" />
									/&nbsp;&nbsp;<?php echo $num_pages; ?>
									<input type="submit" style="position: absolute; left: -99999px;" />
								</form></td>
								<td><a href="<?php
                                if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectName=' . $_GET['ProjectName'] . '&PageNumber=' . $next_page;
                                } else if (isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PageNumber=' . $next_page;
                                } else {
                                    echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $next_page;
                                }
								?>" >Next</a></td>
=======
								<td><a href="<?php if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectName=' . $_GET['ProjectName'] . '&PageNumber=' . $prev_page; } 
								else if (isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PageNumber=' . $prev_page; } 
								else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $prev_page; } ?>" >Previous</a></td>
								<td class="PageJump" style="width:70px;">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" autocomplete="false" >
									<input type="hidden" name="Mode" value="<?php if (isset($_GET['Mode'])) {echo $_GET['Mode']; } else {echo 'Read'; } ?>" />
									<input type="hidden" name="ProjectName" value="<?php if (isset($_GET['ProjectName'])) {echo $_GET['ProjectName']; } else {echo ''; } ?>" />
									<input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
									<input name="PageNumber" type="text" value="<?php if (isset($_GET['PageNumber'])) {echo $_GET['PageNumber']; } else {echo 1; } ?>" class="NavBoxes" />
									/&nbsp;&nbsp;<?php echo $num_pages; ?>
									<input type="submit" style="position: absolute; left: -99999px;" />
								</form></td>
								<td><a href="<?php if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectName=' . $_GET['ProjectName'] . '&PageNumber=' . $next_page; } 
								else if (isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PageNumber=' . $next_page; } 
								else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $next_page; } ?>" >Next</a></td>
>>>>>>> bd5e3fa0efd989c0071c01ec33304d8e710f6619
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
