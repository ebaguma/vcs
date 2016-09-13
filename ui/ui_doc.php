<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

    <?php
    
    #ob_start();
    
    if(isset($_GET['LogOut'])){ LogOut(); }
	
	if (isset($_GET['Mode']) && $_GET['Mode']=='IDUpload'){ IDUpload(); }
	
	if (isset($_GET['InitialMode']) && ($_GET['InitialMode']=='ValDoc' || $_GET['InitialMode']=='PapDoc' || $_GET['InitialMode']=='ProjDoc')){ DocUpload(); }
	
	if (isset($_GET['InitialMode']) && ($_GET['InitialMode']=='ValPhoto' || $_GET['InitialMode']=='PapPhoto' || $_GET['InitialMode']=='ProjPhoto')){ PhotoUpload(); }
    
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
    		// text-align: center; 
    		margin-bottom: 10px;
    		padding: 10px 30px;
    	}
    </style>
    
    <?php
    include ('ui_popup_header.php');
    ?>
    
    <?php

    function LoadDocPhoto() {
        include_once ('../code/code_doc.php');
        $doc_photo = new PapDocPhoto();

        if (strpos($_GET['Mode'], 'Proj') !== false) {
            $doc_photo->proj_id = $_GET['ProjectID'];
            $doc_photo->pap_id = 1;
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $doc_photo->pap_id = $_SESSION['session_pap_hhid'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $doc_photo->pap_id = $_SESSION['session_pap_hhid'];
            }
            $doc_photo->proj_id = 1;
        }

        if ($_GET['Mode'] == "PapDoc") {
            $doc_photo->doc_type = "Pap Doc";
        } else if ($_GET['Mode'] == "PapPhoto") {
            $doc_photo->doc_type = "Pap Photo";
        } else if ($_GET['Mode'] == "ValDoc") {
            $doc_photo->doc_type = "Val Doc";
        } else if ($_GET['Mode'] == "ValPhoto") {
            $doc_photo->doc_type = "Val Photo";
        } else if ($_GET['Mode'] == "ProjDoc") {
            $doc_photo->doc_type = "Proj Doc";
        } else if ($_GET['Mode'] == "ProjPhoto") {
            $doc_photo->doc_type = "Proj Photo";
        }

        $doc_photo->doc_tag = $_GET['Tag'];

        if (isset($_GET['DocPage'])) {
            $GLOBALS['load_page'] = $_GET['DocPage'];
        } else {
            $GLOBALS['load_page'] = 1;
        }

        //set pagination parameters
        $doc_photo->DocPhotoPageParams();
        $GLOBALS['num_pages'] = $doc_photo->pap_doc_last_page;

        //Handling grid pages and navigation
        if ($GLOBALS['load_page'] == 1) {
            $doc_photo->pap_doc_record_num = 0;
            $doc_photo->pap_doc_data_offset = 0;
        } else if ($GLOBALS['load_page'] <= $doc_photo->pap_doc_last_page) {
            $doc_photo->pap_doc_data_offset = ($GLOBALS['load_page'] - 1) * $doc_photo->pap_doc_page_rows;
            $doc_photo->pap_doc_record_num = ($GLOBALS['load_page'] - 1) * $doc_photo->pap_doc_page_rows;
        } else {
            // echo '<script>alert("Page Is Out Of Range");</script>';
            $GLOBALS['load_page'] = 1;
            $doc_photo->pap_doc_record_num = 0;
            $doc_photo->pap_doc_data_offset = 0;
        }

        if (($GLOBALS['load_page'] + 1) <= $doc_photo->pap_doc_last_page) {
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
        $doc_photo->GetDocPhoto();
    }

    function IDUpload() {
        include_once ('../code/code_config.php');
        $file_name = strtolower($_FILES['upload']['name']);
        $target_dir = $main_doc_dir;
        $file_size = 5000000;
        $initial_mode = $_GET['InitialMode'];

        if ($_FILES['upload']['tmp_name'] == "") {
            echo '<script>alert("File ' . $file_name . ' is invalid, or exceeds 20MB");</script>';
        } else {
            $file_type = mime_content_type($_FILES['upload']['tmp_name']);
            $file_size = $_FILES['upload']['size'];
        }

        $accept_image = array('image/jpeg', 'image/jpg', 'image/png', 'image/x-png');

        # $image_name = addslashes($_FILES['image']['name']);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $pap_hhid = $_SESSION['session_pap_hhid'];
            $user_id = $_SESSION['session_user_id'];
        } else if (session_status() == PHP_SESSION_ACTIVE) {
            $pap_hhid = $_SESSION['session_pap_hhid'];
            $user_id = $_SESSION['session_user_id'];
        }

        $pap_project = $_GET['ProjectCode'];
        $sub_str = "PAP";
        $final_file_name = $pap_hhid . '.' . findexts($file_name);

        if ($_FILES['upload']['name'] == "") {
            echo '<script>alert("You haven\'t selected a file");</script>';
            header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
        } else if ($_FILES['upload']['tmp_name'] == "") {
            echo '<script>alert("File ' . $file_name . ' is invalid, or exceeds 20MB");</script>';
        } else {
            $file_type = mime_content_type($_FILES['upload']['tmp_name']);
            $file_size = $_FILES['upload']['size'];

            if (!in_array(strtolower($file_type), $accept_image)) {
                echo '<script>alert("Invalid Format, Only Images Allowed");</script>';
                header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
            } else {

                if ($_FILES['upload']['size'] > $file_size) {
                    echo '<script>alert("Large File, Only 5MB Allowed");</script>';
                    header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                } else {

                    $target_project_dir = $target_dir . $pap_project;
                    $target_sub_dir = $target_project_dir . '/' . $sub_str;
                    $target_hhid_dir = $target_sub_dir . '/' . $pap_hhid;

                    if (!is_dir($target_project_dir)) {
                        mkdir($target_project_dir);
                    }
                    if (!is_dir($target_sub_dir)) {
                        mkdir($target_sub_dir);
                    }
                    if (!is_dir($target_hhid_dir)) {
                        mkdir($target_hhid_dir);
                    }

                    $final_target = $target_hhid_dir . '/';

                    $file_count = 0;

                    $extensions[] = "png";
                    $extensions[] = "jpg";
                    $extensions[] = "jpeg";

                    foreach ($extensions as $ext) {
                        $file_check = $pap_hhid . "." . $ext;
                        if (file_exists($target_hhid_dir . '/' . $file_check)) {
                            $file_count = $file_count + 1;
                        }
                    }

                    if ($file_count == 0) {


                        move_uploaded_file($_FILES['upload']['tmp_name'], $final_target . $final_file_name);
                        include_once ('../code/code_doc.php');
                        $upload_doc = new PapDocPhoto();
                        $upload_doc->pap_id = intval($pap_hhid);
                        $upload_doc->proj_id = intval($_GET['ProjectID']);
                        $upload_doc->doc_type = "ID Photo";
                        $upload_doc->doc_tag = $_GET['Tag'];
                        $upload_doc->file_name = $final_file_name;
                        $upload_doc->file_path = $pap_project . '/' . $sub_str . '/' . $pap_hhid;
                        $upload_doc->created_by = intval($user_id);

                        $upload_doc->InsertDocPhoto();
                        header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                    } else {

                        echo '<script>alert("File Already Exists");</script>';
                        header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                    }
                }
            }
        }
    }

    function PhotoUpload() {
        include_once ('../code/code_config.php');
        $file_name = strtolower($_FILES['upload']['name']);
        $target_dir = $main_doc_dir;
        $file_size = 5000000;
        $initial_mode = $_GET['InitialMode'];

        if ($_FILES['upload']['tmp_name'] == "") {
            echo '<script>alert("File ' . $file_name . ' is invalid, or exceeds 20MB");</script>';
        } else {
            $file_type = mime_content_type($_FILES['upload']['tmp_name']);
            $file_size = $_FILES['upload']['size'];
        }

        $accept_image = array('image/jpeg', 'image/jpg', 'image/png', 'image/x-png');

        # $image_name = addslashes($_FILES['image']['name']);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            if (isset($_SESSION['session_pap_hhid'])) {
                $pap_hhid = $_SESSION['session_pap_hhid'];
            } else {
                $pap_hhid = null;
            }
            $user_id = $_SESSION['session_user_id'];
        } else if (session_status() == PHP_SESSION_ACTIVE) {
            if (isset($_SESSION['session_pap_hhid'])) {
                $pap_hhid = $_SESSION['session_pap_hhid'];
            } else {
                $pap_hhid = null;
            }
            $user_id = $_SESSION['session_user_id'];
        }

        $pap_project = $_GET['ProjectCode'];
        if ($_GET['InitialMode'] == "ValPhoto") {
            $sub_str = "VALN";
        } else if ($_GET['InitialMode'] == "PapPhoto") {
            $sub_str = "PAP";
        } else {
            $sub_str = "PROJ";
        }
        # $pap_str = "PAP";

        if ($_FILES['upload']['name'] == "") {
            echo '<script>alert("You haven\'t selected a file");</script>';
            header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
        } else if ($_FILES['upload']['tmp_name'] == "") {
            echo '<script>alert("File ' . $file_name . ' is invalid, or exceeds 20MB");</script>';
        } else {
            $file_type = mime_content_type($_FILES['upload']['tmp_name']);
            $file_size = $_FILES['upload']['size'];

            if (!in_array(strtolower($file_type), $accept_image)) {
                echo '<script>alert("Invalid Format, Only Images Accepted");</script>';
                header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
            } else {

                if ($_FILES['upload']['size'] > $file_size) {
                    echo '<script>alert("Large File, Only 5MB Allowed");</script>';
                    header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                } else {

                    $target_project_dir = $target_dir . $pap_project;
                    $target_sub_dir = $target_project_dir . '/' . $sub_str;
                    $target_hhid_dir = $target_sub_dir . '/' . $pap_hhid;

                    if ($_GET['InitialMode'] == "ProjPhoto") {
                        $target_tag_dir = $target_sub_dir . '/' . $_GET['Tag'];
                    } else {
                        $target_tag_dir = $target_hhid_dir . '/' . $_GET['Tag'];
                    }


                    if (!is_dir($target_project_dir)) {
                        mkdir($target_project_dir);
                    }
                    if (!is_dir($target_sub_dir)) {
                        mkdir($target_sub_dir);
                    }
                    if (!is_dir($target_hhid_dir)) {
                        mkdir($target_hhid_dir);
                    }
                    if (!is_dir($target_tag_dir)) {
                        mkdir($target_tag_dir);
                    }

                    $final_target = $target_tag_dir . '/';

                    if (!file_exists($target_tag_dir . '/' . $file_name)) {

                        move_uploaded_file($_FILES['upload']['tmp_name'], $target_tag_dir . '/' . $file_name);
                        include_once ('../code/code_doc.php');
                        $upload_doc = new PapDocPhoto();

                        if ($_GET['InitialMode'] == "ProjPhoto") {
                            $upload_doc->pap_id = null;
                        } else {
                            $upload_doc->pap_id = $pap_hhid;
                        }

                        $upload_doc->proj_id = intval($_GET['ProjectID']);

                        if ($_GET['InitialMode'] == "ValPhoto") {
                            $upload_doc->doc_type = "Val Photo";
                        } else if ($_GET['InitialMode'] == "ProjPhoto") {
                            $upload_doc->doc_type = "Proj Photo";
                        } else {
                            $upload_doc->doc_type = "Pap Photo";
                        }

                        $upload_doc->doc_tag = $_GET['Tag'];
                        $upload_doc->file_name = $file_name;

                        if ($_GET['InitialMode'] == "ProjPhoto") {
                            $upload_doc->file_path = $pap_project . '/' . $sub_str . '/' . $_GET['Tag'];
                        } else {
                            $upload_doc->file_path = $pap_project . '/' . $sub_str . '/' . $pap_hhid . '/' . $_GET['Tag'];
                        }

                        $upload_doc->created_by = intval($user_id);

                        $upload_doc->InsertDocPhoto();
                        header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                    } else {

                        echo '<script>alert("File Already Exists");</script>';
                        header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                    }
                }
            }
        }
    }

    function DocUpload() {
        include_once ('../code/code_config.php');
        $file_name = strtolower($_FILES['upload']['name']);
        $target_dir = $main_doc_dir;
        $file_size = 5000000;
        $initial_mode = $_GET['InitialMode'];

        if ($_FILES['upload']['tmp_name'] == "") {
            echo '<script>alert("File ' . $file_name . ' is invalid, or exceeds 20MB");</script>';
        } else {
            $file_type = mime_content_type($_FILES['upload']['tmp_name']);
            $file_size = intval($_FILES['upload']['size']);
        }

        $accept_doc = array('application/pdf');

        # $image_name = addslashes($_FILES['image']['name']);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            if (isset($_SESSION['session_pap_hhid'])) {
                $pap_hhid = $_SESSION['session_pap_hhid'];
            } else {
                $pap_hhid = null;
            }
            $user_id = $_SESSION['session_user_id'];
        } else if (session_status() == PHP_SESSION_ACTIVE) {
            if (isset($_SESSION['session_pap_hhid'])) {
                $pap_hhid = $_SESSION['session_pap_hhid'];
            } else {
                $pap_hhid = null;
            }
            $user_id = $_SESSION['session_user_id'];
        }

        $pap_project = $_GET['ProjectCode'];
        if ($_GET['InitialMode'] == "ValDoc") {
            $sub_str = "VALN";
        } else if ($_GET['InitialMode'] == "PapDoc") {
            $sub_str = "PAP";
        } else {
            $sub_str = "PROJ";
        }
        # $proj_str = "PROJ";

        if ($_FILES['upload']['name'] == "") {
            echo '<script>alert("You haven\'t selected a file");</script>';
            header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
        } else if ($_FILES['upload']['tmp_name'] == "") {
            echo '<script>alert("File ' . $file_name . ' is invalid, or exceeds 20MB");</script>';
        } else {
            $file_type = mime_content_type($_FILES['upload']['tmp_name']);
            $file_size = $_FILES['upload']['size'];

            if (!in_array(strtolower($file_type), $accept_doc)) {
                echo '<script>alert("Invalid Format, Only PDF Accepted");</script>';
                header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
            } else {

                if ($_FILES['upload']['size'] > $file_size) {
                    echo '<script>alert("Large File, Only 5MB Allowed");</script>';
                    header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                } else {

                    $target_project_dir = $target_dir . $pap_project;
                    $target_sub_dir = $target_project_dir . '/' . $sub_str;
                    $target_hhid_dir = $target_sub_dir . '/' . $pap_hhid;

                    if ($_GET['InitialMode'] == "ProjDoc") {
                        $target_tag_dir = $target_sub_dir . '/' . $_GET['Tag'];
                    } else {
                        $target_tag_dir = $target_hhid_dir . '/' . $_GET['Tag'];
                    }


                    if (!is_dir($target_project_dir)) {
                        mkdir($target_project_dir);
                    }
                    if (!is_dir($target_sub_dir)) {
                        mkdir($target_sub_dir);
                    }
                    if (!is_dir($target_hhid_dir)) {
                        mkdir($target_hhid_dir);
                    }
                    if (!is_dir($target_tag_dir)) {
                        mkdir($target_tag_dir);
                    }



                    $final_target = $target_tag_dir . '/';

                    if (!file_exists($target_tag_dir . '/' . $file_name)) {

                        move_uploaded_file($_FILES['upload']['tmp_name'], $target_tag_dir . '/' . $file_name);
                        include_once ('../code/code_doc.php');
                        $upload_doc = new PapDocPhoto();

                        if ($_GET['InitialMode'] == "ProjDoc") {
                            $upload_doc->pap_id = null;
                        } else {
                            $upload_doc->pap_id = $pap_hhid;
                        }

                        $upload_doc->proj_id = intval($_GET['ProjectID']);

                        if ($_GET['InitialMode'] == "ValDoc") {
                            $upload_doc->doc_type = "Val Doc";
                        } else if ($_GET['InitialMode'] == "ProjDoc") {
                            $upload_doc->doc_type = "Proj Doc";
                        } else {
                            $upload_doc->doc_type = "Pap Doc";
                        }

                        $upload_doc->doc_tag = $_GET['Tag'];
                        $upload_doc->file_name = $file_name;

                        if ($_GET['InitialMode'] == "ProjDoc") {
                            $upload_doc->file_path = $pap_project . '/' . $sub_str . '/' . $_GET['Tag'];
                        } else {
                            $upload_doc->file_path = $pap_project . '/' . $sub_str . '/' . $pap_hhid . '/' . $_GET['Tag'];
                        }

                        $upload_doc->created_by = intval($user_id);

                        $upload_doc->InsertDocPhoto();
                        header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
                    } else {

                        echo '<script>alert("File Already Exists");</script>';
                        header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $initial_mode . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']);
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
    
    
    
    <div id="ContentP" class="ContentParent" style="top: 30px;">
      <div class="Content">
        <div class="ContentTitle2"><?php if(strpos($_GET['Mode'],"Doc")){ echo 'Document Upload'; } else { echo 'Image Upload'; } ?></div>
        
        <div class="SearchPap">
          <!-- form -->
            <fieldset class="fieldset" style="height:140px; width:1000px; padding: 20px;">
              <legend class="legend" style="width:200px;"><span class="legendText" >
              <?php if(strpos($_GET['Mode'],"Doc")){ echo 'Document Details'; } else { echo 'Image Details'; } ?>
              </span></legend>
              
              <!--  . $_GET['Mode'] . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] -->
              <form enctype="multipart/form-data" action="<?php 
                if($_GET['Mode'] == "IDPhoto"){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=IDUpload&InitialMode=' . $_GET['Mode'] . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] ; }
                else if(($_GET['Mode'] == "PapDoc")||($_GET['Mode'] == "PapPhoto")){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=PapUpload&InitialMode=' . $_GET['Mode'] . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] ; }
                else if(($_GET['Mode'] == "ProjDoc")||($_GET['Mode'] == "ProjPhoto")){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=PapUpload&InitialMode=' . $_GET['Mode'] . '&Tag=' . $_GET['Tag'] . '&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] ; }?>" 
              method="post" >
              <table>
                <!-- tr>
                  <td class="formLabel"></td>
                </tr -->
                <tr>
                  <td style="vertical-align: middle;">
                      <span style="white-space: nowrap;">
                      	<input type="file" name="upload"  style="float: left; border: 1px solid; padding: 5px; width: 500px;" value="" >
                      	<input type="hidden" name="MAX_FILE_SIZE" value="20000000"  style="float: left; border: 1px solid; padding: 5px; width: 500px;" value="" >
                      	<input type="submit" value="Upload" style="padding: 7px; float: left; margin-left: 10px;" >
                      	
                      	<!-- input type="file" name="image" style="border: 1px solid; padding: 5px; width: 300px; " value="" >&nbsp;&nbsp;<input type="submit" value="Upload" -->
                      </span>	
                  </td>
                </tr>
              </table>
              </form>
            </fieldset>
          <!-- /form -->
        </div>
        
        <div class="PapGrid">
        <form>
        <fieldset class="fieldset" style="height:300px; width:1000px;">
              <legend class="legend" style="width:180px;"><span class="legendText" >
              <?php if(strpos($_GET['Mode'],"Doc")){ echo 'Document List'; } else { echo 'Image List'; } ?>
              </span></legend>
              
              <p>
              	<span class="ActionLinks" style=" width: 200px; border-left: 1px solid;  margin-top: 20px;">
              		<a href="<?php 
              		if (strpos($_GET['Mode'], 'ID') !== false) { echo 'ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; }
                        else if (strpos($_GET['Mode'], 'Pap') !== false) { if($_GET['Tag'] == "PapBasicInfo") {  echo 'ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; }else{ echo 'ui_pap_info.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#' . $_GET['Tag']; } }
                        else if (strpos($_GET['Mode'], 'Proj') !== false) { if($_GET['Tag'] == "ProjDetails") {  echo 'ui_project_detail.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; }else{ echo 'ui_project_detail.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#' . $_GET['Tag']; } }
                        else if (strpos($_GET['Mode'], 'Val') !== false) { echo 'ui_valuation.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']. '#' . $_GET['Tag']; }  ?>">Back to <?php 
                        if (strpos($_GET['Mode'], 'ID') !== false) { echo 'Pap Info'; }
                        else if (strpos($_GET['Mode'], 'Pap') !== false) { echo 'Pap Info'; }
                        else if (strpos($_GET['Mode'], 'Proj') !== false) { echo 'Project Info'; }
                        else if (strpos($_GET['Mode'], 'Val') !== false) { echo 'Valuation Info'; }  ?></a>
                    
                    
              	</span>
              	<!-- span class="ActionLinks" style=" width: 150px; ">
              		<a href="#">New Document</a>
              	</span -->
              	<!-- span class="ActionLinks" style=" width: 175px; ">
              		<a href="#">New Project Doc</a>
              	</span -->
              </p>
              
                <table id="DocumentList" class="detailGrid" style="float: left; width:950px; ">
                    <tr>
                        <td class="detailGridHead">#</td>
                        <td class="detailGridHead">File Name</td>
                        <td class="detailGridHead">File Type</td>
                        <td class="detailGridHead">File Path</td>
                        <td class="detailGridHead">Delete</td>
                    </tr>
                    <?php if (strpos($_GET['Mode'],'Upload') == false) { LoadDocPhoto(); } 
                    ?>
                    
                </table>
            <table style="float: left; width:950px; ">
                <tr>
                        <td>
                            <span style="white-space: nowrap;">
                                 <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#ProjClients'; } 
                                 else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#ProjClients'; } ?>" >Previous</a>
                                 &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['ClientPage'])) { echo $load_page . ' / ' . $num_pages ; } else {echo '1 / ' . $num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                 <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['next_page'] . '#ProjClients'; } 
                                 else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['next_page'] . '#ProjClients'; } ?>" >Next</a>
                             </span> 
                        </td>
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
    #include ('ui_footer.php');
    ?>
    
    <!-- div id="FooterP" class="FooterParent" style="bottom: 0px;">
  		<div class="FooterContent">&copy;&nbsp;&nbsp;Copyright&nbsp;&nbsp;2015:&nbsp;&nbsp;Dataplus Systems Uganda</div>
	</div -->

</body>
</html>