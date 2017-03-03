<! doctype html>
    
    <?php
    
    ob_start();
	
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
    
    if(isset($_GET['LogOut'])){ LogOut(); }
        
    if (isset($_GET['HHID'])) { SelectPap(); }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'Read') {  LoadPapBasicInfo(); LoadIDPhoto(); }
	
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteIDPhoto') { LoadIDPhoto(); DeleteIDPhoto(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateBasicInfo') { UpdatePapBasicInfo(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewAddress') { LoadPapBasicInfo(); SelectPapAddr(); LoadIDPhoto(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertAddress') { InsertPapAddr(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateAddress') { UpdatePapAddr(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteAddress') { DeletePapAddr(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewMember') { LoadPapBasicInfo(); SelectFamilyMember(); LoadIDPhoto(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertMember') { InsertFamilyMember(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateMember') { UpdateFamilyMember(); }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteMember') { DeleteFamilyMember(); }
    
    ?>

<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="Me" content="Bio">
		<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Bio Information</title>

		<?php
                include ('ui_header.php');

                function CheckReturnUser() {
                    # include ('../code/code_index.php');
                    $time = $_SERVER['REQUEST_TIME'];

                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    if (session_status() == PHP_SESSION_ACTIVE && $time < $_COOKIE["session_expire"]) {
                        if (($time - $_SESSION['Last_Activity']) < 1800) {
                            include_once ('../code/code_index.php');
                            $CheckReturnUser = new LogInOut();
                            $CheckReturnUser->user_id = $_SESSION['session_user_id'];
                            $CheckReturnUser->CheckLoginStatus();
                            CheckPapSelection();
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

                function CheckPapSelection() {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])) {
                            header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1');
                        }
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])) {
                            header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1');
                        }
                    }
                }

                function SelectPap() {

                    include_once ('../code/code_pap_list.php');
                    $select_project_pap = new ProjectPapList();
                    $select_project_pap->pap_hhid = $_GET["HHID"];
                    $select_project_pap->SelectPap();
                    $GLOBALS['pap_name'] = $select_project_pap->pap_name;

                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                        $_SESSION['session_pap_hhid'] = $_GET['HHID'];
                        $_SESSION['session_pap_name'] = $GLOBALS['pap_name'];
                    } else if (session_status() == PHP_SESSION_ACTIVE) {
                        $_SESSION['session_pap_hhid'] = $_GET['HHID'];
                        $_SESSION['session_pap_name'] = $GLOBALS['pap_name'];
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

		function LoadPapBasicInfo() {
			include_once ('../code/code_pap_basic_info.php');

			$pap_basic_info = new PapBasicInfo();

			$pap_basic_info -> selected_project_id = $_GET['ProjectID'];
			$pap_basic_info -> selected_project_code = $_GET['ProjectCode'];

			if (isset($_GET['HHID'])) { $pap_basic_info -> pap_hhid = $_GET['HHID'];
			} else if (session_status() == PHP_SESSION_NONE) { session_start();
				$pap_basic_info -> pap_hhid = $_SESSION['session_pap_hhid'];
			} else if (session_status() == PHP_SESSION_ACTIVE) { $pap_basic_info -> pap_hhid = $_SESSION['session_pap_hhid'];
			}

			$pap_basic_info -> LoadBasicInfo();

			$GLOBALS['pap_hhid'] = $pap_basic_info -> pap_hhid;
			$GLOBALS['pap_name'] = $pap_basic_info -> pap_name;
			$GLOBALS['pap_dob'] = $pap_basic_info -> pap_dob;
			$GLOBALS['pap_sex'] = $pap_basic_info -> pap_sex;
			$GLOBALS['pap_plot_ref'] = $pap_basic_info -> pap_plot_ref;
			$GLOBALS['pap_birth_place'] = $pap_basic_info -> pap_birth_place;
			$GLOBALS['pap_is_married'] = $pap_basic_info -> pap_is_married;
			$GLOBALS['pap_phone_no'] = $pap_basic_info -> pap_phone_no;
			$GLOBALS['other_pap_phone_no'] = $pap_basic_info -> pap_other_phone_no;
			$GLOBALS['email'] = $pap_basic_info -> pap_email;
			$GLOBALS['pap_photo'] = $pap_basic_info -> pap_photo;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$_SESSION['pap_religion_id'] = $pap_basic_info -> pap_religion_id;
				$_SESSION['pap_tribe_id'] = $pap_basic_info -> pap_tribe_id;
				$_SESSION['pap_occupation_id'] = $pap_basic_info -> pap_occupation_id;
			} else {
				$_SESSION['pap_religion_id'] = $pap_basic_info -> pap_religion_id;
				$_SESSION['pap_tribe_id'] = $pap_basic_info -> pap_tribe_id;
				$_SESSION['pap_occupation_id'] = $pap_basic_info -> pap_occupation_id;

			}

			#$this -> pap_status_id = $row -> PAP_STATUS_ID;

		}

		function BindTribe() {
			include_once ('../code/code_pap_basic_info.php');
			$bind_pap_tribes = new PapBasicInfo();
			$bind_pap_tribes -> selected_project_id = $_GET["ProjectID"];
			$bind_pap_tribes -> BindTribe();
		}

		function BindReligion() {
			include_once ('../code/code_pap_basic_info.php');
			$bind_pap_religion = new PapBasicInfo();
			$bind_pap_religion -> selected_project_id = $_GET["ProjectID"];
			$bind_pap_religion -> BindReligion();
		}

		function BindOccupation() {
			include_once ('../code/code_pap_basic_info.php');
			$bind_pap_occupation = new PapBasicInfo();
			$bind_pap_occupation -> selected_project_id = $_GET["ProjectID"];
			$bind_pap_occupation -> BindOccupation();
		}

		function UpdatePapBasicInfo() {
			include_once ('../code/code_pap_basic_info.php');
			$update_basic_info = new PapBasicInfo();
			// set update parameters
			$update_basic_info -> pap_hhid = $_POST['HHID'];
			$update_basic_info -> pap_name = $_POST['PapName'];
			$update_basic_info -> pap_dob = $_POST['BirthDate'];
			$update_basic_info -> pap_sex = $_POST['PapSex'];
			$update_basic_info -> pap_plot_ref = $_POST['PlotRef'];
			$update_basic_info -> pap_birth_place = $_POST['BirthPlace'];
			$update_basic_info -> pap_is_married = $_POST['MaritalStatus'];
			# $update_basic_info->pap_address_id=$_POST[''];
			$update_basic_info -> pap_tribe_id = $_POST['PapTribe'];
			$update_basic_info -> pap_phone_no = $_POST['PhoneNo'];
			$update_basic_info -> pap_other_phone_no = $_POST['OtherPhoneNo'];
			$update_basic_info -> pap_email = $_POST['PapEmail'];
			$update_basic_info -> pap_religion_id = $_POST['PapReligion'];
			$update_basic_info -> pap_occupation_id = $_POST['PapOccupation'];
			# $update_basic_info->pap_status_id=$_POST['PapStatus'];

			$update_basic_info -> selected_project_id = $_POST['ProjectID'];
			$update_basic_info -> selected_project_code = $_POST['ProjectCode'];

			$update_basic_info -> UpdatePapBasicInfo();
			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=Read&ProjectID=' . $update_basic_info -> selected_project_id . '&ProjectCode=' . $update_basic_info -> selected_project_code . '&HHID=' . $update_basic_info -> pap_hhid . '#PapBasicInfo');
			exit();
		}

		function LoadIDPhoto(){
			include_once ('../code/code_doc.php');
			$id_photo = new PapDocPhoto();
			
			if(isset($_GET['HHID'])){ $pap_id = $_GET['HHID']; }
			else{ if (session_status() == PHP_SESSION_NONE) { session_start(); $pap_id = $_SESSION['session_pap_hhid']; }
			else{ $pap_id = $_SESSION['session_pap_hhid']; } }
			
			$id_photo -> pap_id = $pap_id;
			$id_photo -> GetIDPhoto();
			$GLOBALS['pap_id_photo'] = $id_photo->file_path . '/' . $id_photo->file_name;
			
			# echo '<script>alert("things ' . $GLOBALS['pap_id_photo'] . ' are not working");</script>';

		}
		
		function DeleteIDPhoto(){
			include_once ('../code/code_doc.php');
			$delete_photo = new PapDocPhoto();

			if(isset($_GET['HHID'])){ $pap_id = $_GET['HHID']; }
			else{ if (session_status() == PHP_SESSION_NONE) { session_start(); $pap_id = $_SESSION['session_pap_hhid']; }
			else{ $pap_id = $_SESSION['session_pap_hhid']; } }
			
			$delete_photo -> pap_id = $pap_id;
			$delete_photo -> DeleteIDPhoto();
			
			$pap_id_photo = '../uploads/' . $GLOBALS['pap_id_photo'];
			if (is_file($pap_id_photo)){ unlink($pap_id_photo); }
			
			header('Location: ' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#BasicInfo');
			# header('Refresh:0; url=' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#BasicInfo');
			# echo '<script>alert("Data Deleted Successfully");</script>';
		}
		
		?>
		
		<?php

		function LoadPapAddr() {
			include_once ('../code/code_pap_address.php');
			$load_pap_addr = new PapAddress();

			$load_pap_addr -> selected_project_id = $_GET['ProjectID'];
			$load_pap_addr -> selected_project_code = $_GET['ProjectCode'];

			if (isset($_GET['HHID'])) {
				$load_pap_addr -> pap_addr_pap_id = $_GET['HHID'];
			} else if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$load_pap_addr -> pap_addr_pap_id = $_SESSION['session_pap_hhid'];
			} else if (session_status() == PHP_SESSION_ACTIVE) {
				$load_pap_addr -> pap_addr_pap_id = $_SESSION['session_pap_hhid'];
			}

			if (isset($_GET['GridPage'])) {
				$GLOBALS['pap_addr_load_page'] = $_GET['GridPage'];
			} else {
				$GLOBALS['pap_addr_load_page'] = 1;
			}

			# set pagination parameters
			$load_pap_addr -> PapAddrPageParams();
			$GLOBALS['pap_addr_num_pages'] = $load_pap_addr -> pap_addr_last_page;

			# Handling grid pages and navigation
			if ($GLOBALS['pap_addr_load_page'] == 1) {
				$load_pap_addr -> pap_addr_record_num = 0;
				$load_pap_addr -> pap_addr_data_offset = 0;
			} else if ($GLOBALS['pap_addr_load_page'] <= $load_pap_addr -> pap_addr_last_page) {
				$load_pap_addr -> pap_addr_data_offset = ($GLOBALS['pap_addr_load_page'] - 1) * $load_pap_addr -> pap_addr_page_rows;
				$load_pap_addr -> pap_addr_record_num = ($GLOBALS['pap_addr_load_page'] - 1) * $load_pap_addr -> pap_addr_page_rows;
				;
			} else {
				// echo '<script>alert("Page Is Out Of Range");</script>';
				$GLOBALS['pap_addr_load_page'] = 1;
				$load_pap_addr -> pap_addr_record_num = 0;
				$load_pap_addr -> pap_addr_data_offset = 0;
			}

			# Setting next, and previous page numbers
			if (($GLOBALS['pap_addr_load_page'] + 1) <= $load_pap_addr -> pap_addr_last_page) {
				$GLOBALS['pap_addr_next_page'] = $GLOBALS['pap_addr_load_page'] + 1;
			} else {
				$GLOBALS['pap_addr_next_page'] = 1;
			}

			if (($GLOBALS['pap_addr_load_page'] - 1) >= 1) {
				$GLOBALS['pap_addr_prev_page'] = $GLOBALS['pap_addr_load_page'] - 1;
			} else {
				$GLOBALS['pap_addr_prev_page'] = 1;
			}

			# Loading Pap Addresses
			$load_pap_addr -> LoadPapAddr();
		}

		function SelectPapAddr() {
			include_once ('../code/code_pap_address.php');
			$select_pap_addr = new PapAddress();

			$select_pap_addr -> selected_project_id = $_GET['ProjectID'];
			$select_pap_addr -> selected_project_code = $_GET['ProjectCode'];
			$select_pap_addr -> pap_addr_id = $_GET['AddrID'];

			$select_pap_addr -> SelectPapAddr();

			$GLOBALS['pap_addr_road'] = $select_pap_addr -> pap_addr_road;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$_SESSION['pap_addr_dist_id'] = $select_pap_addr -> pap_addr_dist_id;
				$_SESSION['pap_addr_cty_id'] = $select_pap_addr -> pap_addr_cty_id;
				$_SESSION['pap_addr_subcty_id'] = $select_pap_addr -> pap_addr_subcty_id;
				$_SESSION['pap_addr_vill_id'] = $select_pap_addr -> pap_addr_vill_id;
			} else {
				$_SESSION['pap_addr_dist_id'] = $select_pap_addr -> pap_addr_dist_id;
				$_SESSION['pap_addr_cty_id'] = $select_pap_addr -> pap_addr_cty_id;
				$_SESSION['pap_addr_subcty_id'] = $select_pap_addr -> pap_addr_subcty_id;
				$_SESSION['pap_addr_vill_id'] = $select_pap_addr -> pap_addr_vill_id;
			}

		}

		function InsertPapAddr() {
			// echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
			include_once ('../code/code_pap_address.php');
			$insert_pap_addr = new PapAddress();

			$insert_pap_addr -> selected_project_id = $_GET['ProjectID'];
			$insert_pap_addr -> selected_project_code = $_GET['ProjectCode'];

			$insert_pap_addr -> pap_addr_road = $_POST['Road'];
			$insert_pap_addr -> pap_addr_vill_id = $_POST['Villages'];

			if (isset($_GET['HHID'])) {
				$insert_pap_addr -> pap_addr_pap_id = $_GET['HHID'];
			}

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$insert_pap_addr -> session_user_id = $_SESSION['session_user_id'];
				$insert_pap_addr -> pap_addr_pap_id = $_SESSION['session_pap_hhid'];
			} else if (session_status() == PHP_SESSION_ACTIVE) {
				$insert_pap_addr -> session_user_id = $_SESSION['session_user_id'];
				$insert_pap_addr -> pap_addr_pap_id = $_SESSION['session_pap_hhid'];
			}

			$insert_pap_addr -> InsertPapAddr();

			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=Read&ProjectID=' . $insert_pap_addr -> selected_project_id . '&ProjectCode=' . $insert_pap_addr -> selected_project_code . '#PapAddress');
			exit();
		}

		function UpdatePapAddr() {
			include_once ('../code/code_pap_address.php');
			$update_pap_addr = new PapAddress();

			$update_pap_addr -> selected_project_id = $_GET['ProjectID'];
			$update_pap_addr -> selected_project_code = $_GET['ProjectCode'];

			$update_pap_addr -> pap_addr_road = $_POST['Road'];
			$update_pap_addr -> pap_addr_vill_id = $_POST['Villages'];
			$update_pap_addr -> pap_addr_id = $_POST['AddrID'];

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$update_pap_addr -> session_user_id = $_SESSION['session_user_id'];
			} else if (session_status() == PHP_SESSION_ACTIVE) {
				$update_pap_addr -> session_user_id = $_SESSION['session_user_id'];
			}

			$update_pap_addr -> UpdatePapAddr();
			
			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=ViewAddress&ProjectID=' . $update_pap_addr -> selected_project_id . '&ProjectCode=' . $update_pap_addr -> selected_project_code . '&AddrID=' . $update_pap_addr -> pap_addr_id . '#PapAddress');
			exit();
		}

		function DeletePapAddr() {
			include_once ('../code/code_pap_address.php');
			$delete_pap_addr = new PapAddress();

			$delete_pap_addr -> selected_project_id = $_GET['ProjectID'];
			$delete_pap_addr -> selected_project_code = $_GET['ProjectCode'];

			$delete_pap_addr -> pap_addr_id = $_GET['AddrID'];

			$delete_pap_addr -> DeletePapAddr();
			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=Read&ProjectID=' . $delete_pap_addr -> selected_project_id . '&ProjectCode=' . $delete_pap_addr -> selected_project_code . '#PapAddress');
			exit();
		}

		function SelectDistrict() {
			include_once ('../code/code_pap_address.php');
			$bind_districts = new PapAddress();
			# $bind_districts -> selected_project_id = $_GET["ProjectID"];
			$bind_districts -> BindDistrict();

		}

		function SelectCounty() {
			include_once ('../code/code_pap_address.php');
			$bind_counties = new PapAddress();
			$bind_counties -> pap_addr_dist_id = $_SESSION['pap_addr_dist_id'];
			$bind_counties -> BindCounties();

		}

		function SelectSubCounty() {
			include_once ('../code/code_pap_address.php');
			$bind_sub_cty = new PapAddress();
			$bind_sub_cty -> pap_addr_cty_id = $_SESSION['pap_addr_cty_id'];
			$bind_sub_cty -> BindSubCounties();

		}

		function SelectVillage() {
			include_once ('../code/code_pap_address.php');
			$bind_village = new PapAddress();
			$bind_village -> pap_addr_subcty_id = $_SESSION['pap_addr_subcty_id'];
			$bind_village -> BindVillage();

		}
		?>
		
		<?php

		function LoadFamilyMember() {
			include_once ('../code/code_pap_family.php');
			$load_fam_mbr = new FamilyMember();

			$load_fam_mbr -> selected_project_id = $_GET['ProjectID'];
			$load_fam_mbr -> selected_project_code = $_GET['ProjectCode'];

			if (isset($_GET['HHID'])) {
				$load_fam_mbr -> fam_mbr_pap_id = $_GET['HHID'];
			} else if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$load_fam_mbr -> fam_mbr_pap_id = $_SESSION['session_pap_hhid'];
			} else if (session_status() == PHP_SESSION_ACTIVE) {
				$load_fam_mbr -> fam_mbr_pap_id = $_SESSION['session_pap_hhid'];
			}

			if (isset($_GET['GridPage'])) {
				$GLOBALS['fam_mbr_load_page'] = $_GET['GridPage'];
			} else {
				$GLOBALS['fam_mbr_load_page'] = 1;
			}

			# set pagination parameters
			$load_fam_mbr -> FamilyPageParams();
			$GLOBALS['fam_mbr_num_pages'] = $load_fam_mbr -> fam_mbr_last_page;

			# Handling grid pages and navigation
			if ($GLOBALS['fam_mbr_load_page'] == 1) {
				$load_fam_mbr -> fam_mbr_record_num = 0;
				$load_fam_mbr -> fam_mbr_data_offset = 0;
			} else if ($GLOBALS['fam_mbr_load_page'] <= $load_fam_mbr -> fam_mbr_last_page) {
				$load_fam_mbr -> fam_mbr_data_offset = ($GLOBALS['fam_mbr_load_page'] - 1) * $load_fam_mbr -> fam_mbr_page_rows;
				$load_fam_mbr -> fam_mbr_record_num = ($GLOBALS['fam_mbr_load_page'] - 1) * $load_fam_mbr -> fam_mbr_page_rows;
			} else {
				// echo '<script>alert("Page Is Out Of Range");</script>';
				$GLOBALS['fam_mbr_load_page'] = 1;
				$load_fam_mbr -> fam_mbr_record_num = 0;
				$load_fam_mbr -> fam_mbr_data_offset = 0;
			}

			# Setting next, and previous page numbers
			if (($GLOBALS['fam_mbr_load_page'] + 1) <= $load_fam_mbr -> fam_mbr_last_page) {
				$GLOBALS['fam_mbr_next_page'] = $GLOBALS['fam_mbr_load_page'] + 1;
			} else {
				$GLOBALS['fam_mbr_next_page'] = 1;
			}

			if (($GLOBALS['fam_mbr_load_page'] - 1) >= 1) {
				$GLOBALS['fam_mbr_prev_page'] = $GLOBALS['fam_mbr_load_page'] - 1;
			} else {
				$GLOBALS['fam_mbr_prev_page'] = 1;
			}

			# Loading Pap Addresses
			$load_fam_mbr -> LoadFamilyMember();
		}

		function SelectFamilyMember() {
			include_once ('../code/code_pap_family.php');
			$select_fam_mbr = new FamilyMember();

			$select_fam_mbr -> selected_project_id = $_GET['ProjectID'];
			$select_fam_mbr -> selected_project_code = $_GET['ProjectCode'];
			$select_fam_mbr -> fam_mbr_id = $_GET['MemberID'];

			$select_fam_mbr -> SelectFamilyMember();

			$GLOBALS['fam_mbr_name'] = $select_fam_mbr -> fam_mbr_name;
			$GLOBALS['fam_mbr_sex'] = $select_fam_mbr -> fam_mbr_sex;
			$GLOBALS['fam_mbr_dob'] = $select_fam_mbr -> fam_mbr_dob;
			$GLOBALS['fam_mbr_birth_place'] = $select_fam_mbr -> fam_mbr_birth_place;

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$_SESSION['fam_mbr_rltn_id'] = $select_fam_mbr -> fam_mbr_rltn_id;
				$_SESSION['fam_mbr_tribe_id'] = $select_fam_mbr -> fam_mbr_tribe_id;
				$_SESSION['fam_mbr_relgn_id'] = $select_fam_mbr -> fam_mbr_relgn_id;
			} else {
				$_SESSION['fam_mbr_rltn_id'] = $select_fam_mbr -> fam_mbr_rltn_id;
				$_SESSION['fam_mbr_tribe_id'] = $select_fam_mbr -> fam_mbr_tribe_id;
				$_SESSION['fam_mbr_relgn_id'] = $select_fam_mbr -> fam_mbr_relgn_id;
			}

		}

		function InsertFamilyMember() {
			// echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
			include_once ('../code/code_pap_family.php');
			$insert_fam_mbr = new FamilyMember();

			$insert_fam_mbr -> selected_project_id = $_GET['ProjectID'];
			$insert_fam_mbr -> selected_project_code = $_GET['ProjectCode'];

			$insert_fam_mbr -> fam_mbr_name = $_POST['MemberName'];
			$insert_fam_mbr -> fam_mbr_rltn_id = $_POST['MemberRelation'];
			$insert_fam_mbr -> fam_mbr_dob = $_POST['MemberBirthDate'];
			$insert_fam_mbr -> fam_mbr_birth_place = $_POST['MemberBirthPlace'];
			$insert_fam_mbr -> fam_mbr_sex = $_POST['MemberSex'];
			$insert_fam_mbr -> fam_mbr_tribe_id = $_POST['MemberTribe'];
			$insert_fam_mbr -> fam_mbr_relgn_id = $_POST['MemberReligion'];
			# $insert_fam_mbr -> fam_mbr_other_dtl = $_POST['MemberOtherDtl'];

			if (isset($_GET['HHID'])) {
				$insert_fam_mbr -> fam_mbr_pap_id = $_GET['HHID'];
			}

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$insert_fam_mbr -> session_user_id = $_SESSION['session_user_id'];
				$insert_fam_mbr -> fam_mbr_pap_id = $_SESSION['session_pap_hhid'];
			} else if (session_status() == PHP_SESSION_ACTIVE) {
				$insert_fam_mbr -> session_user_id = $_SESSION['session_user_id'];
				$insert_fam_mbr -> fam_mbr_pap_id = $_SESSION['session_pap_hhid'];
			}

			$insert_fam_mbr -> InsertFamilyMember();

			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=Read&ProjectID=' . $insert_fam_mbr -> selected_project_id . '&ProjectCode=' . $insert_fam_mbr -> selected_project_code . '#PapFamily');
			exit();
		}

		function UpdateFamilyMember() {
			include_once ('../code/code_pap_family.php');
			$update_fam_mbr = new FamilyMember();

			$update_fam_mbr -> selected_project_id = $_GET['ProjectID'];
			$update_fam_mbr -> selected_project_code = $_GET['ProjectCode'];
			
			$update_fam_mbr -> fam_mbr_id = $_POST['MemberID'];
			$update_fam_mbr -> fam_mbr_name = $_POST['MemberName'];
			$update_fam_mbr -> fam_mbr_rltn_id = $_POST['MemberRelation'];
			$update_fam_mbr -> fam_mbr_dob = $_POST['MemberBirthDate'];
			$update_fam_mbr -> fam_mbr_birth_place = $_POST['MemberBirthPlace'];
			$update_fam_mbr -> fam_mbr_sex = $_POST['MemberSex'];
			$update_fam_mbr -> fam_mbr_tribe_id = $_POST['MemberTribe'];
			$update_fam_mbr -> fam_mbr_relgn_id = $_POST['MemberReligion'];

			if (session_status() == PHP_SESSION_NONE) {
				session_start();
				$update_fam_mbr -> session_user_id = $_SESSION['session_user_id'];
			} else if (session_status() == PHP_SESSION_ACTIVE) {
				$update_fam_mbr -> session_user_id = $_SESSION['session_user_id'];
			}

			$update_fam_mbr -> UpdateFamilyMember();
			
			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=ViewMember&ProjectID=' . $update_fam_mbr -> selected_project_id . '&ProjectCode=' . $update_fam_mbr -> selected_project_code . '&MemberID=' . $update_fam_mbr -> fam_mbr_id . '#PapFamily');
			exit();
		}

		function DeleteFamilyMember() {
			include_once ('../code/code_pap_family.php');
			$delete_fam_mbr = new FamilyMember();

			$delete_fam_mbr -> selected_project_id = $_GET['ProjectID'];
			$delete_fam_mbr -> selected_project_code = $_GET['ProjectCode'];

			$delete_fam_mbr -> fam_mbr_id = $_GET['MemberID'];

			$delete_fam_mbr -> DeleteFamilyMember();
			unset($_POST);
			header('Refresh:0; url=ui_pap_info.php?Mode=Read&ProjectID=' . $delete_fam_mbr -> selected_project_id . '&ProjectCode=' . $delete_fam_mbr -> selected_project_code . '#PapFamily');
			exit();
		}
		
		function BindMemberTribe() {
			include_once ('../code/code_pap_family.php');
			$bind_fam_tribes = new FamilyMember();
			$bind_fam_tribes -> selected_project_id = $_GET["ProjectID"];
			$bind_fam_tribes -> BindTribe();
		}

		function BindMemberReligion() {
			include_once ('../code/code_pap_family.php');
			$bind_fam_religion = new FamilyMember();
			$bind_fam_religion -> selected_project_id = $_GET["ProjectID"];
			$bind_fam_religion -> BindReligion();
		}
		
		function BindMemberRelation() {
			include_once ('../code/code_pap_family.php');
			$bind_fam_relation = new FamilyMember();
			$bind_fam_relation -> selected_project_id = $_GET["ProjectID"];
			$bind_fam_relation -> BindRelation();
		}

		
		?>
		
		
		<script type="text/javascript">
			
		function BindCounties(){
			var httpxml;
                try {
                    // Firefox, Opera 8.0+, Safari
                    httpxml = new XMLHttpRequest();
                } catch (e) {
                    // Internet Explorer
                    try {
                        httpxml = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            httpxml = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            alert("Your browser does not support AJAX!");
                            return false;
                        }
                    }
                }

                function stateck() {
                    if (httpxml.readyState == 4) {
                    	
                    	// dataType: 'JSON';
                        var myarray = JSON.parse(httpxml.responseText);
                        //var myarray = httpxml.responseText;
                        // Remove the options from 2nd dropdown list
                        counties = document.getElementById('SelectCounty');
                        sub_counties = document.getElementById('SelectSubCty');
                        villages = document.getElementById('SelectVillage');
                        
                        for ( j = counties.options.length - 1; j >= 0; j--) {
                            counties.remove(j);
                        }
                        
                        for ( j = sub_counties.options.length - 1; j >= 0; j--) {
                            sub_counties.remove(j);
                        }
                        
                        for ( j = villages.options.length - 1; j >= 0; j--) {
                            villages.remove(j);
                        }
                        
                        var default_cty = document.createElement("option");
                        default_cty.text = "-- Select County --";
                        counties.options.add(default_cty);
                        
                        var default_scty = document.createElement("option");
                        default_scty.text = "-- Select Sub County --";
                        sub_counties.options.add(default_scty);
                        
                        var default_vill = document.createElement("option");
                        default_vill.text = "-- Select Village --";
                        villages.options.add(default_vill);
						
                        for ( i = 0; i < myarray.data.length; i++) {
	                            var optn = document.createElement("option");
	                            optn.text = myarray.data[i].COUNTY;
	                            optn.value = myarray.data[i].ID;
	                            counties.options.add(optn);
                        }
                    }
                }// end of function stateck
				
                var url = "../code/code_drop_county.php";
                var element_id = document.getElementById('SelectDistrict').value;
                url = url + "?Element=" + element_id;
                //httpxml.setRequestHeader("Content-Type", "application/json");
                httpxml.onreadystatechange = stateck;
                httpxml.open("GET", url, true);
                httpxml.send(null);
			
		}
		
		function BindSubCounties(){
			var httpxml;
                try {
                    // Firefox, Opera 8.0+, Safari
                    httpxml = new XMLHttpRequest();
                } catch (e) {
                    // Internet Explorer
                    try {
                        httpxml = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            httpxml = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            alert("Your browser does not support AJAX!");
                            return false;
                        }
                    }
                }

                function stateck() {
                    if (httpxml.readyState == 4) {
                        var myarray = JSON.parse(httpxml.responseText);
                        // Remove the options from 2nd dropdown list
                        sub_counties = document.getElementById('SelectSubCty');
                        villages = document.getElementById('SelectVillage');
                        
                        for ( j = sub_counties.options.length - 1; j >= 0; j--) {
                            sub_counties.remove(j);
                        }
                        
                        for ( j = villages.options.length - 1; j >= 0; j--) {
                            villages.remove(j);
                        }
                        
                        var default_scty = document.createElement("option");
                        default_scty.text = "-- Select Sub County --";
                        sub_counties.options.add(default_scty);
                        
                        var default_vill = document.createElement("option");
                        default_vill.text = "-- Select Village --";
						villages.options.add(default_vill);
						
                        for ( i = 0; i < myarray.data.length; i++) {
	                            var optn = document.createElement("option");
	                            optn.text = myarray.data[i].SUBCOUNTY;
	                            optn.value = myarray.data[i].ID;
	                            sub_counties.options.add(optn);
                        }
                    }
                }// end of function stateck
				
                var url = "../code/code_drop_subcty.php";
                var element_id = document.getElementById('SelectCounty').value;
                url = url + "?Element=" + element_id;
                httpxml.onreadystatechange = stateck;
                httpxml.open("GET", url, true);
                httpxml.send(null);
		}
		
		function BindVillages(){
			var httpxml;
                try {
                    // Firefox, Opera 8.0+, Safari
                    httpxml = new XMLHttpRequest();
                } catch (e) {
                    // Internet Explorer
                    try {
                        httpxml = new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            httpxml = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            alert("Your browser does not support AJAX!");
                            return false;
                        }
                    }
                }

                function stateck() {
                    if (httpxml.readyState == 4) {
                        var myarray = JSON.parse(httpxml.responseText);
                        // Remove the options from 2nd dropdown list
                        villages = document.getElementById('SelectVillage');
                        
                        for ( j = villages.options.length - 1; j >= 0; j--) {
                            villages.remove(j);
                        }
                        
                        var default_vill = document.createElement("option");
                        default_vill.text = "-- Select Village --";
                        villages.options.add(default_vill);
						
                        for ( i = 0; i < myarray.data.length; i++) {
	                            var optn = document.createElement("option");
	                            optn.text = myarray.data[i].VILLAGE;
	                            optn.value = myarray.data[i].ID;
	                            villages.options.add(optn);
                        }
                    }
                }// end of function stateck
				
                var url = "../code/code_drop_village.php";
                var element_id = document.getElementById('SelectSubCty').value;
                url = url + "?Element=" + element_id;
                httpxml.onreadystatechange = stateck;
                httpxml.open("GET", url, true);
                httpxml.send(null);
		}
			
			
		</script>

		<div class="ContentParent">
			<div class="Content">
				<div class="ContentTitle2">
					PAP Personal Information
				</div>
				<br>
				<div class="container">
					<ul class="nav nav-tabs">
						<li class="inactive"><a >&nbsp;</a></li>
						<li class="active"><a data-toggle="tab" href="#PapBasicInfo">Basic Info</a></li>
						<li><a data-toggle="tab" href="#PapAddress">Address</a></li>
						<li><a data-toggle="tab" href="#PapFamily">Family</a></li>
                                                <!-- li><a data-toggle="tab" href="#PapWelfare">Welfare</a></li -->
                                                <li><a data-toggle="tab" href="#PapAssets">Welfare</a></li>
                                                <li><a data-toggle="tab" href="#PapLivelihood">Livelihood</a></li>
                                                <li><a data-toggle="tab" href="#PapHealth">Health</a>						</li>
                                                
                                                <!-- li><a data-toggle="tab" href="#PapBusiness">Other Businesses</a></li -->
						<li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
						<li class="inactive"><a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
					</ul>
					<div class="tab-content">

						<!-- This is the Basic Info Screen -->
						<div id="PapBasicInfo" class="tab-pane fade in active" style="height:1000px;">
							<p>
								This is the Basic Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#BasicInfo'; ?>" method="post" autocomplete="off">
								<table class="formTable">
									<input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                        <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                        <tr>
                                                                            <td><table><tr><td class="formLabel" style="width:125px">HHID:</td>
                                                                                        <td class="formLabel">ID Number:</td></tr></table></td >
                                                                            <td class="formLabel">Reference Number:</td>
                                                                            
                                                                        </tr>
                                                                        <tr>
                                                                            <td><table><tr>
                                                                                        <td><span class="formSingleLineBox" style="width:145px;">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_hhid'])) { echo $GLOBALS['pap_hhid']; } ?>" name="HHID" readonly style="width:125px; font-size: 15px;" />
										</span></td>
										<td><span class="formSingleLineBox" style=" width:145px; ">
                                                                                        <input type="text" value="<?php # if (isset($GLOBALS['pap_plot_ref'])) { echo $GLOBALS['pap_plot_ref']; } ?>" placeholder="ID Number" name="IDNo" style="width:125px; " />
                                                                                    </span></td></tr></table>
                                                                                    </td >
                                                                            <td><span class="formSingleLineBox">
											<input type="text" value="<?php if (isset($GLOBALS['pap_plot_ref'])) { echo $GLOBALS['pap_plot_ref']; } ?>" placeholder="Plot Ref" name="PlotRef" style="font-size: 15px;" />
										</span></td>
                                                                                
                                                                        </tr>
                                                                        <tr>
                                                                            <td><table><tr><td class="formLabel" style="width:125px;  ">Pap Status:</td>
                                                                                        <td class="formLabel">Pap Type:</td></tr></table></td>
                                                                            <td class="formLabel">Residence Status:</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><table><tr>
                                                                                <td><span class="formSingleLineBox" style=" width:145px; float:left;">
                                                                                        <select name="PapStatus" style="width:125px;">
                                                                                        <option value="">-- Status --</option>    
                                                                                        <option value="LO">Land Owner</option>
                                                                                        <option value='LIC' >Licensee</option>
                                                                                        <option value='TEN' >Tenant</option> 
                                                                                        </select>
                                                                                </span></td>
                                                                                <td><span class="formSingleLineBox" style=" width:145px; ">
                                                                                        <select name="PapType" style="width:125px;">
                                                                                        <option value="">-- Pap Type --</option>    
                                                                                        <option value="IND">Individual</option>
                                                                                        <option value='ENT' >Entity</option>
                                                                                        <option value='GRP' >Group</option> 
                                                                                        </select>
                                                                                         </span></td></tr></table>
                                                                                    </td>
                                                                                <td ><span class="formSingleLineBox" style="float:left; ">
											<select name="PapResidence" >
                                                                                        <option value="">-- Residence Status --</option>    
                                                                                        <option value="RES">Resident</option>
                                                                                        <option value='NON' >Non Resident</option>
                                                                                        <option value='TEN' >Tenant</option> 
                                                                                        </select>
										</span>
										</td>
                                                                        </tr>
                                                                        <tr>
										<td class="formLabel">PAP Name:</td>
									</tr>
									<tr>
										<td colspan="2" ><span class="formSingleLineBox" style="width:610px;">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_name'])) { echo $GLOBALS['pap_name']; } ?>" name="PapName" style="width: 580px;" /> 
										</span></td>
									</tr>
                                                                        <!-- tr>
										<td class="formLabel">HHID:</td>
										<td class="formLabel">Reference Number</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_hhid'])) { echo $GLOBALS['pap_hhid']; } ?>" name="HHID" readonly />
										</span></td>
										<td><span class="formSingleLineBox">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_plot_ref'])) { echo $GLOBALS['pap_plot_ref']; } ?>" name="PlotRef" />
										</span></td>
									</tr -->
									
									<tr>
										<td class="formLabel">Date Of Birth:</td>
										<td class="formLabel">Place Of Birth:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
											<input title="DD/MM/YYYY" type="text" id="birth_date" value="<?php if (isset($GLOBALS['pap_dob'])) { echo $GLOBALS['pap_dob']; } ?>" placeholder="DD/MM/YYYY" name="BirthDate" readonly />
										</span></td>
										<td><span class="formSingleLineBox">
											<input type="text" value="<?php if (isset($GLOBALS['pap_birth_place'])) { echo $GLOBALS['pap_birth_place']; } ?>" name="BirthPlace" placeholder="Enter Birth Place" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Select Sex:</td>
										<td class="formLabel">Select Marital Status:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
											<select name="PapSex" >
                                                                                        <option value="">-- Select Sex --</option>
                                                                                        <option value="Female" <?php if (isset($GLOBALS['pap_sex']) && $GLOBALS['pap_sex'] == 'Female') { echo 'selected'; }  ?> >Female</option>
                                                                                        <option value="Male" <?php if (isset($GLOBALS['pap_sex']) && $GLOBALS['pap_sex'] == 'Male') { echo 'selected'; }  ?> >Male</option> 
                                                                                        </select>
										</span></td>
										<td><span class="formSingleLineBox">
											<select name="MaritalStatus" >
                                                                                        <option value="">-- Select Status --</option>
                                                                                        <option value='true' <?php if (isset($GLOBALS['pap_is_married']) && $GLOBALS['pap_is_married'] == "true") { echo 'selected'; }  ?> >Married</option>
                                                                                        <option value='false' <?php if (isset($GLOBALS['pap_is_married']) && $GLOBALS['pap_is_married'] == "false") { echo 'selected'; }  ?> >Single</option> 
                                                                                        </select>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Select Tribe:</td>
										<td class="formLabel">Select Religion:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <select name="PapTribe" id="SelectTribe" >
                                                                                        <option value="">-- Select Tribe --</option>
                                                                                         <?php if (isset($_GET['ProjectID']) || isset($_GET['TribeID'])) { BindTribe(); } ?>
                                                                                     <!-- select><a class="LinkInBoxOther" href="#">New</a -->
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="PapReligion" id="SelectReligion" >
                                                                                        <option value="">-- Select Religion --</option>
                                                                                        <?php if (isset($_GET['ProjectID']) || isset($_GET['ReligionID'])) { BindReligion(); } ?>
                                                                                    <!-- select><a class="LinkInBoxOther" href="#">New</a -->
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Select Occupation:</td>
                                                                                <td class="formLabel">Literacy Level</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <select name="PapOccupation" id="SelectOccupation" >
                                                                                    <option value="">-- Select Occupation --</option>
                                                                                    <?php if (isset($_GET['ProjectID']) || isset($_GET['OccupnID'])) { BindOccupation(); } ?>
                                                                                <!-- select><a class="LinkInBoxOther" href="#">New</a -->
										</span></td>
                                                                                <td><span class="formSingleLineBox">
											<select name="LiteracyLevel" >
                                                                                        <option value="">-- Select Literacy --</option>
                                                                                        </select>
										</span></td>
										
									</tr>
									<tr>
										<td class="formLabel">Email Address:</td>
                                                                                <td class="formLabel">Phone Number:</td>
									</tr>
									<tr>
										<td ><span class="formSingleLineBox" >
											<input type="text" value="<?php if (isset($GLOBALS['email'])) { echo $GLOBALS['email']; } ?>" name="PapEmail"  placeholder="Enter Contact Email" />
										</span></td>
                                                                                <td><span class="formSingleLineBox" style="width: 145px; float: left;">
											<input type="text" value="<?php if (isset($GLOBALS['pap_phone_no'])) { echo $GLOBALS['pap_phone_no']; } ?>" name="PhoneNo" placeholder="Phone No" style="width: 125px;"/>
										</span>
										<span class="formSingleLineBox" style="width: 145px; float:left;">
											<input type="text" value="<?php if (isset($GLOBALS['other_pap_phone_no'])) { echo $GLOBALS['other_pap_phone_no']; } ?>" name="OtherPhoneNo" placeholder="Alternate Phone" style="width: 125px;" />
										</span>
										</td>
									</tr>
                                                                        <tr>
                                                                            <td class="formLabel">Interviewer:</td>
                                                                            <td class="formLabel">Interview Date:</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><span class="formSingleLineBox">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_hhid'])) {  } ?>" placeholder="Interviewer" name="Interviewer" readonly />
										</span></td>
                                                                                <td><span class="formSingleLineBox">
											<input title="DD/MM/YYYY" type="text" id="interview_date" value="<?php if (isset($GLOBALS['pap_dob'])) {  } ?>" placeholder="DD/MM/YYYY" name="InterviewDate" readonly />
										</span></td>
                                                                        </tr>
									<tr>
										<td><span class="saveButtonArea"> <input type="submit" value="Update" name="UpdateBasicInfo"/></span></td>
										<td><span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=PapDoc&Tag=PapBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
											<span class="formLinks"><a href="<?php echo 'ui_doc.php?Mode=PapPhoto&Tag=PapBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Photos</a></span></td>
									</tr>
								</table>
								</form>
							</div>
							
							
							<div style="width:300px; float:left; margin-top:10px; margin-left:20px;">
								<table  style="margin-bottom:20px; width:250px;">
									<tr>
										<td class="formLabel"></td>
									</tr>
									<tr>
										<td>
										<div class="PhotoBox">
											<img src="<?php if($GLOBALS['pap_id_photo'] == "/"){ echo 'images/placeholder.jpg'; }else{ echo '../uploads/' . $GLOBALS['pap_id_photo']; } ?>" width="250" height="250" />'; ?>
										</div></td>
									</tr>
									<tr>
										<td><span class="formLinks SideBar" ><a href="<?php echo 'ui_doc.php?Mode=IDPhoto&Tag=PapBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Upload Photo</a></span>
											<span class="formLinks" ><a <?php if($GLOBALS['pap_id_photo'] == "/"){ echo 'style="display:none"'; } ?> onClick="return Confirm();" href="<?php 
											if(isset($_GET['HHID'])){ $pap_id = $_GET['HHID']; }
											else{ if (session_status() == PHP_SESSION_NONE) { session_start(); $pap_id = $_SESSION['session_pap_hhid']; }
											else{ $pap_id = $_SESSION['session_pap_hhid']; } }
											echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=DeleteIDPhoto&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $pap_id ; 
											?>">Delete Photo</a></span></td>
									</tr>
								</table>
								
							</div>
						</div>

						<!-- This is the PAP Address Screen -->
						<div id="PapAddress" class="tab-pane fade">
							<p>
								This is the PAP Address Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
                                                            <form name="Address" action="<?php
                                                            if ($_GET['Mode'] == 'Read') {
                                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PapAddress';
                                                            } else if ($_GET['Mode'] == 'ViewAddress') {
                                                                echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PapAddress';
                                                            }
                                                            ?>" method="POST" autocomplete="off">
                                                                <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                <input type="hidden" name="AddrID" value="<?php if (isset($_GET['AddrID'])) {
                                                                echo $_GET['AddrID'];
                                                            } ?>" />
								<table class="formTable">
									<tr>
										<td class="formLabel">Plot No, Road:</td>
										<td class="formLabel">
										<input type="checkbox">
                                                                                &nbsp;Is Residence?&nbsp;&nbsp;&nbsp;
                                                                                <input type="checkbox">
										&nbsp;Affected Plot?</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_addr_road'])) { echo $GLOBALS['pap_addr_road']; } ?>" name="Road" placeholder="Enter Address" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Select District:</td>
										<td class="formLabel">Select County:</td>
									</tr>
									<tr>
                                                                            <td><span class="formSingleLineBox" >
                                                                                    <select name="Districts" id="SelectDistrict" onchange="BindCounties()" >
                                                                                        <option value="" <?php
                                                                                        if ($_GET['Mode'] == 'Read') {
                                                                                            echo 'selected';
                                                                                            unset($_SESSION['pap_addr_dist_id']);
                                                                                        }
                                                                                        ?> >-- Select District --</option>
                                                                                                <?php
                                                                                                if (isset($_GET['ProjectID'])) {
                                                                                                    SelectDistrict();
                                                                                                }
                                                                                                ?>
                                                                                        <!-- select><a class="LinkInBoxOther" href="#" >New</a -->
                                                                                </span>
                                                                            </td>
                                                                            <td><span class="formSingleLineBox">
                                                                                    <select name="Counties" id="SelectCounty" onchange="BindSubCounties()" >
                                                                                        <option value="" >-- Select County --</option>
                                                                                        <?php if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewAddress') {
                                                                                            SelectCounty();
                                                                                        } ?>
                                                                                        <!-- select><a class="LinkInBoxOther" href="#" >New</a -->
                                                                                </span>
                                                                            </td>
									</tr>
									<tr>
										<td class="formLabel">Select SubCounty:</td>
										<td class="formLabel">Select Village:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox" >
											<select name="SubCounties" id="SelectSubCty" onchange="BindVillages()" >
                                                <option value="" >-- Select Sub County --</option>
                                                <?php if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewAddress') { SelectSubCounty(); } ?>
                                            <!-- select><a class="LinkInBoxOther" href="#" >New</a -->
                                            </span>
                                        </td>
										<td><span class="formSingleLineBox" >
											<select name="Villages" id="SelectVillage" >
                                                <option value="" >-- Select Village --</option>
                                                <?php if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewAddress') { SelectVillage(); } ?>
                                            <!-- select><a class="LinkInBoxOther" href="#" >New</a -->
                                            </span>
                                        </td>
									</tr>
									<tr>
										<td> 
											<span class="saveButtonArea">
												<input type="submit" value="<?php if ($_GET['Mode'] == 'ViewAddress') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;" />
												<?php $new_address = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PapAddress';
                                                if ($_GET['Mode'] == 'ViewAddress') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_address . '>New Address</a></span>'; } ?>
                                            </span>
                                        </td>
										<td align="right">
                                                                                    <span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=PapDoc&Tag=PapAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
											<span class="formLinks"><a href="<?php echo 'ui_doc.php?Mode=PapPhoto&Tag=PapAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Photos</a></span></td>
									</tr>
								</table>
								</form>
							</div>
							
							<div class="GridArea" style="width: 750px;">
                                                            <form>
                                                                <fieldset class="fieldset" style="width:800px; margin:0px;;">
                                                                    <legend class="legend" style="width:200px;">
                                                                        <span class="legendText" >Pap Addresses:</span>
                                                                    </legend>
                                                                    <table class="detailGrid" style="width:800px; margin:10px 0px;">
                                                                        <tr>
                                                                            <td class = "detailGridHead">#</td>
                                                                            <td  class = "detailGridHead">Address</td>
                                                                            <td  class = "detailGridHead">Village</td>
                                                                            <td  class = "detailGridHead">District</td>
                                                                            <td  class = "detailGridHead">Modify</td>
                                                                        </tr>
                                                                        <?php if (isset($_GET['ProjectID'])) {
                                                                            LoadPapAddr();
                                                                        } ?>
                                                                    </table>
                                                                
								
								<!-- table class="detailNavigation">
									<tr>
										<td><a href="#">Previous</a></td>
										<td class="PageJump">1 / 2</td>
										<td><a href="#">Next</a></td>
									</tr>
								</table -->
								
								<span style="white-space: nowrap;">
                                                                    <a href="<?php if (isset($_GET['AddrID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&AddrID=' . $_GET['AddrID'] . '&GridPage=' . $GLOBALS['pap_addr_prev_page'] . '#PapAddress'; } 
                                                                    else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_addr_prev_page'] . '#PapAddress'; } ?>" >Previous</a>
                                                                    &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $pap_addr_load_page . ' / ' . $pap_addr_num_pages ; } else {echo '1 / ' . $pap_addr_num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                                                    <a href="<?php if (isset($_GET['AddrID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] .  '&AddrID=' . $_GET['AddrID'] . '&GridPage=' . $GLOBALS['pap_addr_next_page'] . '#PapAddress'; } 
                                                                    else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_addr_next_page'] . '#PapAddress'; } ?>" >Next</a>
                                                                </span>
							
                                                                </fieldset>
                                                            </form>
							</div>
						</div>

						<!-- This is the family info screen -->
						<div id="PapFamily" class="tab-pane">
							<p>
								This is the Family Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form name="Address" action="<?php 
                                                                                if($_GET['Mode'] == 'Read'){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Family'; }
                                                                                else if ($_GET['Mode'] == 'ViewMember') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Family'; } ?>" method="POST" autocomplete="off">
                                                                    <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                    <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                    <input type="hidden" name="MemberID" value="<?php if (isset($_GET['MemberID'])) { echo $_GET['MemberID']; } ?>" />
								<table class="formTable">
									<tr>
										<td class="formLabel">Family Member Name:</td>
									</tr>
									<tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">
										    <input type="text" value="<?php if (isset($GLOBALS['fam_mbr_name'])) { echo $GLOBALS['fam_mbr_name']; } ?>" name="MemberName" placeholder="Enter Family Member Name" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Sex:</td>
										<td class="formLabel">Relation Type:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
											<select name="MemberSex" >
                                                <option value="">-- Select Sex --</option>
                                                <option value="Female" <?php if (isset($GLOBALS['fam_mbr_sex']) && $GLOBALS['fam_mbr_sex'] == 'Female') { echo 'selected'; }  ?> >Female</option>
                                                <option value="Male" <?php if (isset($GLOBALS['fam_mbr_sex']) && $GLOBALS['fam_mbr_sex'] == 'Male') { echo 'selected'; }  ?> >Male</option> 
                                        	</select>
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="MemberRelation" id="SelectRelation" >
                                                <option value="" <?php if ($_GET['Mode'] == 'Read'){ echo 'selected'; unset($_SESSION['fam_mbr_rltn_id']); } ?> >-- Select Relation --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['RelationID'])) { BindMemberRelation(); } ?>
                                            <!-- select><a class="LinkInBoxOther" href="#">New</a -->
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Date Of Birth:</td>
										<td class="formLabel">Place Of Birth:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
											<input title="DD/MM/YYYY" type="text" id="member_birth_date" value="<?php if (isset($GLOBALS['fam_mbr_dob'])) { echo $GLOBALS['fam_mbr_dob']; } ?>" placeholder="DD/MM/YYYY" name="MemberBirthDate" readonly />
										</span></td>
										<td><span class="formSingleLineBox">
											<input type="text" value="<?php if (isset($GLOBALS['fam_mbr_birth_place'])) { echo $GLOBALS['fam_mbr_birth_place']; } ?>" name="MemberBirthPlace" placeholder="Enter Birth Place" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Tribe:</td>
										<td class="formLabel">Religion:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <select name="MemberTribe" id="SelectMemberTribe" >
                                               <option value="" <?php if ($_GET['Mode'] == 'Read'){ echo 'selected'; unset($_SESSION['fam_mbr_tribe_id']); } ?> >-- Select Tribe --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['MemberID'])) { BindMemberTribe(); } ?>
                                            <!-- select><a class="LinkInBoxOther" href="#">New</a -->
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="MemberReligion" id="SelectMemberReligion" >
                                                <option value="" <?php if ($_GET['Mode'] == 'Read'){ echo 'selected'; unset($_SESSION['fam_mbr_relgn_id']); } ?> >-- Select Religion --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['MemberID'])) { BindMemberReligion(); } ?>
                                            <!-- select><a class="LinkInBoxOther" href="#">New</a -->
										</span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">Education Level:</td>
										<td class="formLabel">Current School Status:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox" style="float: left; ">
											<select name="EducLevel" >
                                                                                        <option value="">-- Literacy Level --</option>    
                                                                                        <option value="LO">Illiterate</option>
                                                                                        <option value='LIC' >Primary</option>
                                                                                        <option value='TEN' >Secondary</option>
                                                                                        <option value='TEN' >Vocational</option>
                                                                                        <option value='TEN' >Tertiary</option>
                                                                                        </select>
                                                                                </span></td>
										<td><span class="formSingleLineBox" style="float: left; ">
											<select name="CurrentSchool" >
                                                                                        <option value="">-- School Status --</option>    
                                                                                        <option value="LO">Continuing</option>
                                                                                        <option value='LIC' >Completed</option>
                                                                                        <option value='TEN' >Dropped Out</option>
                                                                                        <option value='TEN' >Never Attended</option>
                                                                                        </select>
                                                                                </span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">Reason for Non Attendance:</td>
										<td class="formLabel">Reason for Drop Out:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox" style="float: left; ">
											<select name="EducLevel" >
                                                                                        <option value="">-- Non Attendance --</option>    
                                                                                        <option value="LO">Expensive</option>
                                                                                        <option value='LIC' >Not Applicable</option>
                                                                                        <option value='TEN' >Long Distance</option>
                                                                                        <option value='TEN' >Disabled</option>
                                                                                        <option value='TEN' >Orphaned</option>
                                                                                        <option value='TEN' >Needed to Work</option>
                                                                                        <option value='TEN' >Health</option>
                                                                                        </select>
                                                                                </span></td>
										<td><span class="formSingleLineBox" style="float: left; ">
											<select name="CurrentSchool" >
                                                                                        <option value="">-- Drop Out Reason --</option>    
                                                                                        <option value="LO">Attained Desired Level</option>
                                                                                        <option value="LO">Expensive</option>
                                                                                        <option value='LIC' >Not Applicable</option>
                                                                                        <option value='TEN' >Long Distance</option>
                                                                                        <option value='TEN' >Disabled</option>
                                                                                        <option value='TEN' >Orphaned</option>
                                                                                        <option value='TEN' >Needed to Work</option>
                                                                                        <option value='TEN' >Health</option>
                                                                                        </select>
                                                                                </span></td>
									</tr>
									<tr>
										<td> 
											<span class="saveButtonArea">
												<input type="submit" value="<?php if ($_GET['Mode'] == 'ViewMember') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;" />
												<?php $new_member = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PapFamily';
                                                if ($_GET['Mode'] == 'ViewMember') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_member . '>New Member</a></span>'; } ?>
                                            </span>
                                        </td>
										<td align="right">
                                                                                    <span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=PapDoc&Tag=PapFamily&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
											<span class="formLinks"><a href="<?php echo 'ui_doc.php?Mode=PapPhoto&Tag=PapFamily&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Photos</a></span></td>
									</tr>
								</table>
								</form>
							</div>
							
							<div class="GridArea" style="width: 750px;">
                                                            <form>
                                                                <fieldset class="fieldset" style="width:800px; margin:0px;">
                                                                    <legend class="legend" style="width:200px;">
                                                                        <span class="legendText" >Family Members:</span>
                                                                    </legend>
                                                                    <table class="detailGrid" style="width:800px; margin:10px 0px;">
                                                                        <tr>
                                                                            <td class = "detailGridHead">#</td>
                                                                            <td  class = "detailGridHead">Member Name</td>
                                                                            <td  class = "detailGridHead">Sex</td>
                                                                            <td  class = "detailGridHead">Age</td>
                                                                            <td  class = "detailGridHead">Relation</td>
                                                                            <td  class = "detailGridHead">Modify</td>
                                                                        </tr>
									<?php if (isset($_GET['ProjectID'])) { LoadFamilyMember(); } ?>
                                                                    </table>
                                                                
								
								
								<!-- table class="detailNavigation">
									<tr>
										<td><a href="#">Previous</a></td>
										<td class="PageJump">1 / 2</td>
										<td><a href="#">Next</a></td>
									</tr>
								</table -->
								
								<span style="white-space: nowrap; ">
                                    <a href="<?php if (isset($_GET['MemberID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&MemberID=' . $_GET['MemberID'] . '&GridPage=' . $GLOBALS['fam_mbr_prev_page'] . '#PapFamily'; } 
                                    else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['fam_mbr_prev_page'] . '#PapFamily'; } ?>" >Previous</a>
                                    &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $fam_mbr_load_page . ' / ' . $fam_mbr_num_pages ; } else {echo '1 / ' . $fam_mbr_num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                    <a href="<?php if (isset($_GET['MemberID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewMember&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] .  '&MemberID=' . $_GET['MemberID'] . '&GridPage=' . $GLOBALS['fam_mbr_next_page'] . '#PapFamily'; } 
                                    else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['fam_mbr_next_page'] . '#PapFamily'; } ?>" >Next</a>
                            	</span>

                                                                </fieldset>
                                                            </form>
							</div>
						</div>
                                                
                                                <!-- This is the Welfare info screen -->
                                                <div id="PapWelfare" class="tab-pane">
                                                    
                                                </div>
                                                
                                                <!-- This is the Livelihood info screen -->
                                                <div id="PapLivelihood" class="tab-pane">
                                                    <p>
								This is the Pap Livelihood Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form name="Address" action="" method="POST" autocomplete="off">
                                                                <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                <input type="hidden" name="MemberID" value="<?php if (isset($_GET['MemberID'])) { echo $_GET['MemberID']; } ?>" />
								<table class="formTable">
									<tr>
										<td class="formLabel">Activity:</td>
                                                                                <td class="formLabel">Income Per Cycle:</td>
									</tr>
									<tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td ><span class="formSingleLineBox" style="">
										    Enter Activity Name
										</span></td>
                                                                                <td ><span class="formSingleLineBox" style="">
										    Enter Income per Season
										</span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">Other Detail:</td>
									</tr>
                                                                        <tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td colspan="2"><span class="formMultiLineBox">
										    Other details about this activity
										</span></td>
                                                                        </tr>
                                                                        <tr>
									<td ><a class="saveButtonArea" href="#">Save / Finish</a></td>
									<td >
										<span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=ValuationDoc&Tag=Land&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
										<span class="formLinks"><a href="#">Photos</a></span>
									</td>
								</tr>
							</table>
                                                        </div>
                                                        
                                                        <div class="GridArea" style="width: 750px;">
                                                            <form>
                                                                <fieldset class="fieldset" style="width:800px; margin:0px;">
                                                                    <legend class="legend" style="width:250px;">
                                                                        <span class="legendText" >Pap Livelihood Information:</span>
                                                                    </legend>
								<table class="detailGrid" style="width:700px; margin:10px 0px;">
										<tr>
											<td class = "detailGridHead">#</td>
											<td class = "detailGridHead">Crop Name:</td>
											<td  class = "detailGridHead">Crop Type:</td>
											<td  class = "detailGridHead">Crop Description:</td>
											<td  class = "detailGridHead">Units:</td>
											<td  class = "detailGridHead">Crop Rate:</td>
											<td  class = "detailGridHead">Crop Total:</td>
											<td  class = "detailGridHead" colspan="2">Modify:</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Orange Trees</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Eucalyptus Trees</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Nsambya Tree</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
									<table class="detailNavigation">
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 2</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
                                                                </fieldset>
                                                            </form>
                                                        </div>
                                                        
                                                </div>
                                                
                                                <!-- This is the health info screen -->
                                                <div id="PapHealth" class="tab-pane">
                                                    <p>
								This is the Pap Health Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form name="Address" action="" method="POST" autocomplete="off">
                                                                <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                <input type="hidden" name="MemberID" value="<?php if (isset($_GET['MemberID'])) { echo $_GET['MemberID']; } ?>" />
								<table class="formTable">
									<tr>
										<td class="formLabel">Common Disease:</td>
                                                                                <td class="formLabel">Number Affected:</td>
									</tr>
									<tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td ><span class="formSingleLineBox" style="">
										    Enter Common disease
										</span></td>
                                                                                <td ><span class="formSingleLineBox" style="">
										    Enter no Of members affected
										</span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">First Aid Step:</td>
                                                                                <td class="formLabel">Nearest Health Center:</td>
									</tr>
									<tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td ><span class="formSingleLineBox" style="">
										    Enter First Aid Step
										</span></td>
                                                                                <td ><span class="formSingleLineBox" style="">
										    Enter Nearest Health Center
										</span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">Other Detail:</td>
									</tr>
                                                                        <tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td colspan="2"><span class="formMultiLineBox">
										    Other details about this disease
										</span></td>
                                                                        </tr>
                                                                        <tr>
									<td ><a class="saveButtonArea" href="#">Save / Finish</a></td>
									<td >
										<span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=ValuationDoc&Tag=Land&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
										<span class="formLinks"><a href="#">Photos</a></span>
									</td>
								</tr>
							</table>
                                                                </form>
                                                        </div>
                                                        
                                                        <div class="GridArea" style="width: 750px;">
                                                            
                                                            <form>
                                                                <fieldset class="fieldset" style="width:800px; margin:0px;">
                                                                    <legend class="legend" style="width:200px;">
                                                                        <span class="legendText" >Pap Addresses:</span>
                                                                    </legend>
								<table class="detailGrid" style="width:700px; margin:10px 0px;">
										<tr>
											<td class = "detailGridHead">#</td>
											<td class = "detailGridHead">Crop Name:</td>
											<td  class = "detailGridHead">Crop Type:</td>
											<td  class = "detailGridHead">Crop Description:</td>
											<td  class = "detailGridHead">Units:</td>
											<td  class = "detailGridHead">Crop Rate:</td>
											<td  class = "detailGridHead">Crop Total:</td>
											<td  class = "detailGridHead" colspan="2">Modify:</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Orange Trees</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Eucalyptus Trees</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Banana Plants</td>
											<td>Seasonal</td>
											<td>Young, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Nsambya Tree</td>
											<td>Perenial</td>
											<td>Mature, Good Condition</td>
											<td>100</td>
											<td>25,000</td>
											<td>2,500,000</td>
											<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
										</tr>
									</table>
									<table class="detailNavigation">
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 2</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
                                                                </fieldset>
                                                            </form>
                                                        </div>
                                                </div>
                                                
                                                <!-- This is the Other Assets info screen -->
                                                <div id="PapAssets" class="tab-pane">
                                                    <p>
								This is the Pap Assets Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form name="Address" action="" method="POST" autocomplete="off">
                                                                <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                                                <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                                                <input type="hidden" name="MemberID" value="<?php if (isset($_GET['MemberID'])) { echo $_GET['MemberID']; } ?>" />
								<table class="formTable">
									<tr>
										<td class="formLabel">Asset/Business Name:</td>
                                                                                <td class="formLabel">Number of Assets:</td>
									</tr>
									<tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td ><span class="formSingleLineBox" style="">
										    Enter Asset Name
										</span></td>
                                                                                <td ><span class="formSingleLineBox" style="">
										    Enter Number of Assets
										</span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">Purpose:</td>
									</tr>
									<tr>
                                                                                <td ><span class="formSingleLineBox" style="">
										    Enter purpose of Asset
										</span></td>
									</tr>
                                                                        <tr>
										<td class="formLabel">Other Detail:</td>
									</tr>
                                                                        <tr>
										<!-- td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td -->
										<td colspan="2"><span class="formMultiLineBox">
										    Other details about this Asset
										</span></td>
                                                                        </tr>
                                                                        <tr>
									<td ><a class="saveButtonArea" href="#">Save / Finish</a></td>
									<td >
										<span class="formLinks SideBar"><a href="<?php echo 'ui_doc.php?Mode=ValuationDoc&Tag=Land&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>">Documents</a></span>
										<span class="formLinks"><a href="#">Photos</a></span>
									</td>
								</tr>
							</table>
                                                        </div>
                                                        
                                                        <div class="GridArea" style="width: 750px;">
                                                            <form>
                                                                <fieldset class="fieldset" style="width:800px; margin:0px;">
                                                                    <legend class="legend" style="width:200px;">
                                                                        <span class="legendText" >Pap Addresses:</span>
                                                                    </legend>
                                                                    <table class="detailGrid" style="width:700px; margin:10px 0px;">
                                                                        <tr>
                                                                            <td class = "detailGridHead">#</td>
                                                                            <td class = "detailGridHead">Crop Name:</td>
                                                                            <td  class = "detailGridHead">Crop Type:</td>
                                                                            <td  class = "detailGridHead">Crop Description:</td>
                                                                            <td  class = "detailGridHead">Units:</td>
                                                                            <td  class = "detailGridHead">Crop Rate:</td>
                                                                            <td  class = "detailGridHead">Crop Total:</td>
                                                                            <td  class = "detailGridHead" colspan="2">Modify:</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>Banana Plants</td>
                                                                            <td>Seasonal</td>
                                                                            <td>Young, Good Condition</td>
                                                                            <td>100</td>
                                                                            <td>25,000</td>
                                                                            <td>2,500,000</td>
                                                                            <td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>Orange Trees</td>
                                                                            <td>Seasonal</td>
                                                                            <td>Young, Good Condition</td>
                                                                            <td>100</td>
                                                                            <td>25,000</td>
                                                                            <td>2,500,000</td>
                                                                            <td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>Eucalyptus Trees</td>
                                                                            <td>Perenial</td>
                                                                            <td>Mature, Good Condition</td>
                                                                            <td>100</td>
                                                                            <td>25,000</td>
                                                                            <td>2,500,000</td>
                                                                            <td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>Banana Plants</td>
                                                                            <td>Seasonal</td>
                                                                            <td>Young, Good Condition</td>
                                                                            <td>100</td>
                                                                            <td>25,000</td>
                                                                            <td>2,500,000</td>
                                                                            <td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td>Nsambya Tree</td>
                                                                            <td>Perenial</td>
                                                                            <td>Mature, Good Condition</td>
                                                                            <td>100</td>
                                                                            <td>25,000</td>
                                                                            <td>2,500,000</td>
                                                                            <td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
                                                                        </tr>
                                                                    </table>
                                                                    <table class="detailNavigation">
                                                                        <tr>
                                                                            <td><a href="#">Previous</a></td>
                                                                            <td class="PageJump">1 / 2</td>
                                                                            <td><a href="#">Next</a></td>
                                                                        </tr>
                                                                    </table>
                                                                </fieldset>
                                                            </form>
                                                        </div>
                                                </div>
                                                
                                                <!-- This is the Other Business info screen -->
                                                <!-- div id="PapBusiness" class="tab-pane">
                                                    
                                                </div -->

					</div>
				</div>
			</div>
		</div>

		<?php // include ('ui_footer.php'); ?>
        
                <div id="FooterP" class="FooterParent" style="top:1300px;">
                    <div class="FooterContent">&copy;&nbsp;&nbsp;Copyright&nbsp;&nbsp;2015:&nbsp;&nbsp;Dataplus Systems Uganda</div>
                  </div>
		
		<script src="js/date_picker/pikaday.js"></script>
		<script>
		
			var picker = new Pikaday({
				field : document.getElementById('birth_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1900, 0, 1),
				maxDate : new Date(2100, 12, 31),
				yearRange : [1900, 2100]
			});
                        
                        var picker = new Pikaday({
				field : document.getElementById('interview_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1900, 0, 1),
				maxDate : new Date(2100, 12, 31),
				yearRange : [1900, 2100]
			});
			
			var picker = new Pikaday({
				field : document.getElementById('member_birth_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1900, 0, 1),
				maxDate : new Date(2100, 12, 31),
				yearRange : [1900, 2100]
			});
			
			function Confirm(){
				return confirm('Are You Sure, Delete?');
			}
		</script>

		</body>
</html>