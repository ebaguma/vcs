<?php
ob_start();
?>

<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="Me" content="PapList">
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
    
    if (isset($_GET['logout'])) { LogOut(); } 
    
    ?>
    
    <div id="ContentP" class="ContentParent" style="top: 30px;">
      <div class="Content">
        <div class="ContentTitle2">Change PAP Selection</div>
        
        <div class="SearchPap">
          <form>
            <fieldset class="fieldset" style="height:130px; width:1000px;">
              <legend class="legend" style="width:200px;"><span class="legendText" >
              Search By PAP Details
              </span></legend>
              <table>
                <tr>
                  <td class="formLabel">PAP Name</td>
                  <!-- td class="formLabel">Reference Number</td>
                  <td class="formLabel">Other Details</td -->
                </tr>
                <tr>
                  <td><span class="formSingleLineBox" style="width: 610px;" >Search By Name</span></td>
                  <!-- td><span class="formSingleLineBox">Search By Ref No</span></td>
                  <td><span class="formSingleLineBox">Search By Other Details</span></td -->
                </tr>
              </table>
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
        <fieldset class="fieldset" style="height:470px; width:1000px;">
              <legend class="legend" style="width:180px;"><span class="legendText" >
              Summary PAP List
              </span></legend>
                <table class="detailGrid" style="width:750px;">
                  <tr>
                    <td class = "detailGridHead">#</td>
                    <td  class = "detailGridHead">Project Name:</td>
                    <td class = "detailGridHead">Project Code:</td>
                    <td class = "detailGridHead">Project Manager:</td>
                    <td class = "detailGridHead" colspan="2">Action:</td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Karuma Project</td>
                    <td>KIP</td>
                    <td>Edwin Baguma</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Standard Gauge Railway</td>
                    <td>SGR</td>
                    <td>Byabagambi</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Uganda Kenya Oil Pipeline</td>
                    <td>UKP</td>
                    <td>AB Byandala</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>Kasese Kinshasa Super Highway</td>
                    <td>KKSH</td>
                    <td>AB Byandala</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>5</td>
                    <td>Entebbe Express Project</td>
                    <td>EEP</td>
                    <td>AB Byandala</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td>Southern ByPass Project</td>
                    <td>SBP</td>
                    <td>AB Byandala</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>7</td>
                    <td>Tororo Lira TLine Project</td>
                    <td>TOL</td>
                    <td>Richard Mungati</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>8</td>
                    <td>Mbarara Kabale Highway Project</td>
                    <td>MKHP</td>
                    <td>Richard Mungati</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>9</td>
                    <td>Kampala Gulu Highway Project</td>
                    <td>KGHP</td>
                    <td>Richard Mungati</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>10</td>
                    <td>Gulu Juba Super Highway</td>
                    <td>GJSH</td>
                    <td>Basajjakawa Jjemba</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>11</td>
                    <td>Karuma Project</td>
                    <td>KIP</td>
                    <td>Edwin Baguma</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
                  </tr>
                  <tr>
                    <td>12</td>
                    <td>Standard Gauge Railway</td>
                    <td>SGR</td>
                    <td>Byabagambi</td>
                    <td><a href="#">Edit</a></td>
                    <td><a onClick="HideGrid()" href="#">Select</a></td>
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
        <br>
      </div>
    </div>

    <?php
    include ('ui_footer.php');
    ?>

</body>
</html>