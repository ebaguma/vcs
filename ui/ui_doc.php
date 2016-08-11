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
    
    <style type="text/css">
    	.ActionLinks {
    		float: left; 
    		display: block; 
    		text-align: center; 
    		margin-top, margin-bottom: 20px;
    		 padding: 5px 5px;
    	}
    </style>
    
    <?php
    include ('ui_popup_header.php');
    ?>
    
    <?php
    
    function UploadFile() {
		include_once ('../code/code_config.php');
		$image_name = $_FILES['image']['name'];
		$target_dir = $main_doc_dir;
		$image_size = 2097152;
		$acceptable = array('image/jpeg', 'image/JPEG','image/jpg','image/JPG', 'image/png','image/PNG','image/x-png','image/X-PNG');

		# $image_name = addslashes($_FILES['image']['name']);
		$pap_hhid = "4312";
		$pap_project = "KIP";
		$pap_str = "PAP";
		# $file_count = 0;
		
		$ext = findexts($image_name);
		$new_image_name = $pap_hhid . '.' . $ext;
		$check_exists = glob($pap_hhid . ".*");

		if ($_FILES['image']['name'] == "") {
			echo '<script>alert("You haven\'t selected a file");</script>';
			header('Refresh:0; url=/test/image.php');
		} else {
			if (!in_array($_FILES['image']['type'], $acceptable)) {
				echo '<script>alert("Invalid Format, Only JPG, PNG Allowed");</script>';
				header('Refresh:0; url=/test/image.php');
			} else {
				if ($_FILES['image']['size'] > $image_size) {
					echo '<script>alert("Large File, Only 2MB Allowed");</script>';
					header('Refresh:0; url=/test/image.php');
				} else {

					$target_project_dir = $target_dir . $pap_project;
					$target_pap_dir = $target_project_dir . '/' . $pap_str;
					$target_hhid_dir = $target_pap_dir . '/' . $pap_hhid;
					
					if (!is_dir($target_project_dir)) { mkdir($target_project_dir); 					}
					if (!is_dir($target_pap_dir)) { mkdir($target_pap_dir); }
					if (!is_dir($target_hhid_dir)) { mkdir($target_hhid_dir); }
					
					$final_target = $target_hhid_dir . '/';
					$file_count = count(array_diff(scandir($final_target), array('.','..')));
					
					/* $images = glob($target_hhid_dir . '/*.{jpeg,JPEG,jpg,JPG,png,PNG}', GLOB_BRACE);
					if (empty($images)) { $echo = 'Yes'; }
					echo '<script>alert("' . $echo . '");</script>';
					header('Refresh:0; url=/test/image.php'); */
					
					if ($file_count == 0) {
						move_uploaded_file($_FILES['image']['tmp_name'], $target_hhid_dir . '/' . $new_image_name);
						include_once ('../code/code_pap_basic_info.php');
						$upload_image = new PapBasicInfo();
						$upload_image -> pap_hhid = intval($pap_hhid);
						$upload_image -> photo_name = $pap_hhid;
						$upload_image -> photo_path = $pap_project . '/' . $pap_str . '/' . $pap_hhid;
						$upload_image -> photo_ext = $ext;

						$upload_image -> UpdatePhoto(); 
						# echo '<script>alert("Data Uploaded Successfully");</script>';
						echo '<script>alert("Data Uploaded Successfully");</script>';
						echo '<script>CloseDialog();</script>';
						header('Refresh:0; url=/test/image.php');
						
					} else {
						echo '<script>alert("File Already Exists");</script>';
						echo '<script>CloseDialog();</script>';
						header('Refresh:0; url=/test/image.php');
					}

				}
			}
		}
	}

	function findexts($filename) {
		$filename = strtolower($filename);
		$exts = split("[/\\.]", $filename);
		$n = count($exts) - 1;
		$exts = $exts[$n];
		return $exts;
	}
    
    ?>
    
    <?php

    function CheckReturnUser() {
        $time = $_SERVER['REQUEST_TIME'];
		include ('../code/code_index.php');
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (session_status() == PHP_SESSION_ACTIVE && $time < $_SESSION['Expire']) {
                if (($time - $_SESSION['Last_Activity']) < 1800) {
                    // isset($_SESSION['session_user_id'])
                    include_once ('../code/code_index.php');
                    $CheckReturnUser = new LogInOut();
                    $CheckReturnUser -> user_id = $_SESSION['session_user_id'];
                    $CheckReturnUser -> CheckLoginStatus();
                    #CheckPapSelection();
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
					include_once ('../code/code_index.php');
					$InactiveReturnUser = new LogInOut();
					$InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
					$InactiveReturnUser-> LogOff();
                    session_unset();
                    session_destroy();
                    header('Location: ../index.php?Message=Inactive_Session_Expired');
                }
            } else {
            	include_once ('../code/code_index.php');
				$InactiveReturnUser = new LogInOut();
				$InactiveReturnUser -> user_id = $_SESSION['session_user_id'];
				$InactiveReturnUser-> LogOff();
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
        <div class="ContentTitle2">Document, Image Upload</div>
        
        <div class="SearchPap">
          <form>
            <fieldset class="fieldset" style="height:140px; width:1000px; padding: 20px;">
              <legend class="legend" style="width:200px;"><span class="legendText" >
              Document Details
              </span></legend>
              
              <form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Upload'; ?>" method="post" autcomplete="off">
              <table>
                <!-- tr>
                  <td class="formLabel"></td>
                </tr -->
                <tr>
                  <td style="vertical-align: middle;">
                      <!-- input type="hidden" name="Mode" value="<?php echo 'SearchPap'; ?>" />
                      <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                      <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" / -->
                      <span style="white-space: nowrap;">
                      	<input type="file" name="image" style="float: left; border: 1px solid; padding: 5px; width: 500px;" value="" >
                      	<input type="submit" value="Upload" style="padding: 7px; float: left; margin-left: 10px;">
                      </span>	
                  </td>
                </tr>
              </table>
              </form>
            </fieldset>
          </form>
        </div>
        
        <div class="PapGrid">
        <form>
        <fieldset class="fieldset" style="height:300px; width:1000px;">
              <legend class="legend" style="width:180px; margin-bottom: 20px;"><span class="legendText" >
              Summary PAP List
              </span></legend>
              
              <p>
              	<span class="ActionLinks" style=" width: 200px; border-left: 1px solid; ">
              		<a href="<?php 
              		if (strpos($_GET['Mode'], 'ID') !== false) { echo 'ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#' . $_GET['Tag']; }
					else if (strpos($_GET['Mode'], 'Pap') !== false) { echo 'ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#' . $_GET['Tag']; }
					else if (strpos($_GET['Mode'], 'Project') !== false) { echo 'ui_project_detail.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#' . $_GET['Tag']; }
					else if (strpos($_GET['Mode'], 'Valuation') !== false) { echo 'ui_valuation.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#' . $_GET['Tag']; } ?>">Back to <?php 
					if (strpos($_GET['Mode'], 'ID') !== false) { echo 'Pap Info'; }
					else if (strpos($_GET['Mode'], 'Pap') !== false) { echo 'Pap Info'; }
					else if (strpos($_GET['Mode'], 'Project') !== false) { echo 'Project Info'; }
					else if (strpos($_GET['Mode'], 'Valuation') !== false) { echo 'Valuation Info'; }  ?></a>
              	</span>
              	<!-- span class="ActionLinks" style=" width: 150px; ">
              		<a href="#">New Document</a>
              	</span -->
              	<!-- span class="ActionLinks" style=" width: 175px; ">
              		<a href="#">New Project Doc</a>
              	</span -->
              </p>
              
                <table class="detailGrid" style="float: left; width:950px; ">
                	<tr>
                        <td class="detailGridHead">#</td>
                        <td class="detailGridHead">File Name</td>
                        <td class="detailGridHead">File Type</td>
                        <td class="detailGridHead">Date Added</td>
                        <td class="detailGridHead">Added By</td>
                        <td class="detailGridHead">Delete</td>
                    </tr>
                    <?php # if ($_GET['Mode'] == 'Read') { LoadProjectPaps(); } else if ($_GET['Mode'] == 'SearchPap'){ SearchProjectPaps();  } else { LoadProjectPaps(); } ?>
                </table>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ; ?>" method="GET" autocomplete="off" >
                    <input type="hidden" name="Mode" value="<?php if ($_GET['Mode'] == 'SearchPap') { echo 'SearchPap'; } else { echo 'Read'; } ?>" />
                    <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                    <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
                    <input type="hidden" name="KeyWord" value="<?php if (isset($_GET['KeyWord'])) {echo $_GET['KeyWord']; } else {echo ''; } ?>" />
                    <span style="white-space: nowrap; float:left;">
                        <a href="<?php if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_prev_page'] ; } 
                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_prev_page'] ; } ?>" >Previous</a>
                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $_GET['GridPage'] . ' / ' . $GLOBALS['pap_num_pages'] ; } else {echo '1 / ' . $GLOBALS['pap_num_pages'] ; } ?>" style="width: 75px; height: 35px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                        <a href="<?php if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_next_page'] ; } 
                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_next_page'] ; } ?>" >Next</a>
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
    #include ('ui_footer.php');
    ?>
    
    <!-- div id="FooterP" class="FooterParent" style="bottom: 0px;">
  		<div class="FooterContent">&copy;&nbsp;&nbsp;Copyright&nbsp;&nbsp;2015:&nbsp;&nbsp;Dataplus Systems Uganda</div>
	</div -->

</body>
</html>