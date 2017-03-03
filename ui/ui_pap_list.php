<! doctype html >

    <?php
    
    ob_start();
    
    if(isset($_GET['LogOut'])){ LogOut(); }
    
    ?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="Me" content="PapList">
    <title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;PAP List</title>
    
    <?php
    include ('ui_popup_header.php');
    ?>
    
    <?php
    
    function LoadProjectPaps() {
            include_once ('../code/code_pap_list.php');
            $load_project_paps = new ProjectPapList();
            $load_project_paps -> selected_project_id = $_GET["ProjectID"];

            if (isset($_GET['GridPage'])) {
                $GLOBALS['pap_load_page'] = $_GET['GridPage'];
            } else {
                $GLOBALS['pap_load_page'] = 1;
            }

            //set pagination parameters
            $load_project_paps -> ReadPageParams();
            $GLOBALS['pap_num_pages'] = $load_project_paps -> pap_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['pap_load_page'] == 1) {
                $load_project_paps -> pap_record_num = 0;
                $load_project_paps -> pap_data_offset = 0;
            } else if ($GLOBALS['pap_load_page'] <= $load_project_paps -> pap_last_page) {
                $load_project_paps -> pap_data_offset = ($GLOBALS['pap_load_page'] - 1) * $load_project_paps -> pap_page_rows;
                $load_project_paps -> pap_record_num = ($GLOBALS['pap_load_page'] - 1) * $load_project_paps -> pap_page_rows; ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['pap_load_page'] = 1;
                $load_project_paps -> pap_record_num = 0;
                $load_project_paps -> pap_data_offset = 0;
            }

            if (($GLOBALS['pap_load_page'] + 1) <= $load_project_paps -> pap_last_page) {
                $GLOBALS['pap_next_page'] = $GLOBALS['pap_load_page'] + 1;
            } else {
                $GLOBALS['pap_next_page'] = 1;
            }

            if (($GLOBALS['pap_load_page'] - 1) >= 1) {
                $GLOBALS['pap_prev_page'] = $GLOBALS['pap_load_page'] - 1;
            } else {
                $GLOBALS['pap_prev_page'] = 1;
            }

            //Loading Projects
            $load_project_paps -> LoadPaps();
        }

        function SearchProjectPaps() {
            include_once ('../code/code_pap_list.php');
            $search_project_paps = new ProjectPapList();
            $search_project_paps -> selected_project_id = $_GET["ProjectID"];
            $search_project_paps -> pap_search = $_GET["KeyWord"];

            if (isset($_GET['GridPage'])) {
                $GLOBALS['pap_load_page'] = $_GET['GridPage'];
            } else {
                $GLOBALS['pap_load_page'] = 1;
            }

            //set pagination parameters
            $search_project_paps -> SearchPageParams();
            $GLOBALS['pap_num_pages'] = $search_project_paps -> pap_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['pap_load_page'] == 1) {
                $search_project_paps -> pap_record_num = 0;
                $search_project_paps -> pap_data_offset = 0;
            } else if ($GLOBALS['pap_load_page'] <= $search_project_paps -> pap_last_page) {
                $search_project_paps -> pap_data_offset = ($GLOBALS['pap_load_page'] - 1) * $search_project_paps -> pap_page_rows;
                $search_project_paps -> pap_record_num = ($GLOBALS['pap_load_page'] - 1) * $search_project_paps -> pap_page_rows; ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['pap_load_page'] = 1;
                $search_project_paps -> pap_record_num = 0;
                $search_project_paps -> pap_data_offset = 0;
            }

            if (($GLOBALS['pap_load_page'] + 1) <= $search_project_paps -> pap_last_page) {
                $GLOBALS['pap_next_page'] = $GLOBALS['pap_load_page'] + 1;
            } else {
                $GLOBALS['pap_next_page'] = 1;
            }

            if (($GLOBALS['pap_load_page'] - 1) >= 1) {
                $GLOBALS['pap_prev_page'] = $GLOBALS['pap_load_page'] - 1;
            } else {
                $GLOBALS['pap_prev_page'] = 1;
            }

            //Loading Projects
            $search_project_paps -> SearchPaps();
        }
    
    ?>
    
    <?php

    function CheckReturnUser() {
        $time = $_SERVER['REQUEST_TIME'];
        include ('../code/code_index.php');
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (session_status() == PHP_SESSION_ACTIVE && $time < $_COOKIE["session_expire"]) {
            if (($time - $_SESSION['Last_Activity']) < 1800) {
                // isset($_SESSION['session_user_id'])
                include_once ('../code/code_index.php');
                $CheckReturnUser = new LogInOut();
                $CheckReturnUser->user_id = $_SESSION['session_user_id'];
                $CheckReturnUser->CheckLoginStatus();
                #CheckPapSelection();
                if ($CheckReturnUser->return_session_id == session_id() && $CheckReturnUser->login_status == "TRUE") {
                    // header('Location: ui/ui_project_list.php?PageNumber=1');
                    echo 'SetActivePage()';
                    $_SESSION['Last_Activity'] = $time;
                } else {
                    session_unset();
                    session_destroy();
                    header('Location: ../index.php?Message=DB_Session_Expired');
                }
            } else {
                include_once ('../code/code_index.php');
                $InactiveReturnUser = new LogInOut();
                $InactiveReturnUser->user_id = $_SESSION['session_user_id'];
                $InactiveReturnUser->LogOff();
                session_unset();
                session_destroy();
                header('Location: ../index.php?Message=Inactive_Session_Expired');
            }
        } else if ($time > $_COOKIE["session_expire"]) {
            include_once ('../code/code_index.php');
            $InactiveReturnUser = new LogInOut();
            # $InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
            $InactiveReturnUser->user_id = $_COOKIE["last_user"];
            $InactiveReturnUser->LogOff();
            session_unset();
            session_destroy();
            header('Location: ../index.php?Message=Session_Expired');
        } else {
            session_unset();
            session_destroy();
            header('Location: ../index.php?Message=Session_Expired');
        }
    }

    function LogOut() {
        include_once ('../code/code_index.php');

        $logout = new LogInOut();
        //session_start();
        if (isset($_SESSION['session_user_id'])) {
            $logout->user_id = $_SESSION['session_user_id'];
        } else {
            $logout->user_id = $_COOKIE["last_user"];
        }
        $logout->LogOff();
    }
    ?>
    
    <?php
    
    if (isset($_GET['logout'])) { LogOut(); } 
    
    ?>
    
    <div id="ContentP" class="ContentParent" style="top: 30px;">
      <div class="Content">
        <div class="ContentTitle2">Change PAP Selection</div>
        
        <div class="SearchPap">
          <form>
            <fieldset class="fieldset" style="height:140px; width:1000px;">
              <legend class="legend" style="width:200px;"><span class="legendText" >
              Search By PAP Details
              </span></legend>
              
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>" method="GET" autocomplete="off" >
              <table>
                <tr>
                  <td class="formLabel">Project Affected Person Search</td>
                  <!-- td class="formLabel">Reference Number</td>
                  <td class="formLabel">Other Details</td -->
                </tr>
                <tr>
                  <td>
                      <input type="hidden" name="Mode" value="<?php echo 'SearchPap'; ?>" />
                      <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                      <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
                      <span class="formSingleLineBox" style="width: 750px;" >
                      <input name="KeyWord" type="text" value="<?php if (isset($_GET['KeyWord'])) { echo $_GET['KeyWord'];  } ?>" style="width: 650px;" placeholder="Search For PAP by HHID, PAP Name, Plot Ref, Location Details" />
                      <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1'; ?>" class="LinkInBox" >Reset</a>
                      </span>
                      <input type="submit" style="position: absolute; left: -99999px;" />
                  </td>
                  <!-- td><span class="formSingleLineBox">Search By Ref No</span></td>
                  <td><span class="formSingleLineBox">Search By Other Details</span></td -->
                </tr>
              </table>
              </form>
            </fieldset>
          </form>
        </div>
        
        <!-- div class="SearchPap">
        <form>
            <fieldset class="fieldset" style="height:130px; width:1000px;">
              <legend class="legend" style="width:250px;"><span class="legendText" >
              Search By Location Details
              </span></legend>
              <table>
                <tr>
                  <td class="formLabel">District</td>
                  <td class="formLabel">Village</td>
                  <td class="formLabel">Other Location Details</td>
                </tr>
                <tr>
                  <td><span class="formSingleLineBox">Search By District</span></td>
                  <td><span class="formSingleLineBox">Search By Village</span></td>
                  <td><span class="formSingleLineBox">Search By Other Details</span></td>
                </tr>
              </table>
            </fieldset>
          </form>
        </div -->
        
        <div class="PapGrid">
        <form>
        <fieldset class="fieldset" style="height:600px; width:1000px;">
              <legend class="legend" style="width:180px;"><span class="legendText" >
              Summary PAP List
              </span></legend>
                <table class="detailGrid" style="width:1000px;">
                    <tr>
                        <td class="detailGridHead">#</td>
                        <td class="detailGridHead">HHID</td>
                        <td class="detailGridHead">PAP Name</td>
                        <td class="detailGridHead">Plot Ref</td>
                        <td class="detailGridHead">Designation</td>
                        <td class="detailGridHead">PAP Type</td>
                        <td class="detailGridHead">Delete</td>
                    </tr>
                    <?php if ($_GET['Mode'] == 'Read') { LoadProjectPaps(); } else if ($_GET['Mode'] == 'SearchPap'){ SearchProjectPaps();  } else { LoadProjectPaps(); } ?>
                </table>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>" method="GET" autocomplete="off" >
                    <input type="hidden" name="Mode" value="<?php if ($_GET['Mode'] == 'SearchPap') { echo 'SearchPap'; } else { echo 'Read'; } ?>" />
                    <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                    <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
                    <input type="hidden" name="KeyWord" value="<?php if (isset($_GET['KeyWord'])) {echo $_GET['KeyWord']; } else {echo ''; } ?>" />
                    <span style="white-space: nowrap; float:left;">
                        <a href="<?php if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_prev_page'] ; } 
                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_prev_page'] ; } ?>" id="PreviousPage">Previous</a>
                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $_GET['GridPage'] . ' / ' . $GLOBALS['pap_num_pages'] ; } else {echo '1 / ' . $GLOBALS['pap_num_pages'] ; } ?>" style="width: 75px; height: 35px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                        <a href="<?php if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_next_page'] ; } 
                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_next_page'] ; } ?>" id="NextPage">Next</a>
                    </span>
                    <input type="submit" style="position: absolute; left: -99999px;" />
                </form>
                
                </fieldset>
                </form>
              </div>
        </div>
        <br>
      </div>
    </div>

    <?php
    include ('ui_footer.php');
    ?>

</body>
</html>