<! doctype html>
    
    <?php
    
    ob_start();
    
    if(isset($_GET['LogOut'])){ LogOut(); }
        
    if (isset($_GET['HHID'])) { SelectPap(); }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'Read') { LoadPapBasicInfo(); }
	
	if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateBasicInfo') { UpdatePapBasicInfo(); }
	
	
    
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
            $time = $_SERVER['REQUEST_TIME'];

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (session_status() == PHP_SESSION_ACTIVE && $time < $_SESSION['Expire']) {
                if (($time - $_SESSION['Last_Activity']) < 1800) {
                    // isset($_SESSION['session_user_id'])
                    include ('../code/code_index.php');
                    $CheckReturnUser = new LogInOut();
                    $CheckReturnUser -> user_id = $_SESSION['session_user_id'];
                    $CheckReturnUser -> CheckLoginStatus();
                    CheckPapSelection();
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

        function CheckPapSelection(){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])){ header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1'); }
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                if (!isset($_SESSION['session_pap_hhid']) && isset($_GET['ProjectID']) && isset($_GET['ProjectCode'])){ header('Location: ui_pap_list.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=1'); }
            }
            
        }
        
        function SelectPap(){
            
            include_once ('../code/code_pap_list.php');
            $select_project_pap = new ProjectPapList();
            $select_project_pap -> pap_hhid = $_GET["HHID"];
            $select_project_pap -> SelectPap();
            $GLOBALS['pap_name'] = $select_project_pap -> pap_name;
            
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
                $logout -> user_id = $_SESSION['session_user_id'];
            } else {
                $logout -> user_id = $_COOKIE["last_user"];
            }
            $logout -> LogOff();

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
			header('Refresh:0; url=ui_pap_info.php?Mode=Read&ProjectID=' . $update_basic_info -> selected_project_id . '&ProjectCode=' . $update_basic_info -> selected_project_code . '&HHID=' . $update_basic_info -> pap_hhid . '#BasicInfo');
			exit();
		}
		?>
		
		<?php
		
		function LoadPapAddr(){
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
                $load_pap_addr -> pap_addr_record_num = ($GLOBALS['pap_addr_load_page'] - 1) * $load_pap_addr -> pap_addr_page_rows; ;
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
		
		function SelectPapAddr(){
			include_once ('../code/code_project_budget.php');
            $select_budget_item = new ProjectBudget();
            $select_budget_item -> selected_project_id = $_GET["ProjectID"];
            $select_budget_item -> budget_item_id = $_GET["BudgetID"];

            $select_budget_item -> SelectBudgetItem();

            $GLOBALS['budget_item_id'] = $select_budget_item -> budget_item_id;
            //$GLOBALS['budget_item_catg'] = $select_budget_item -> budget_item_catg;
            //$GLOBALS['budget_item_sub_catg'] = $select_budget_item -> budget_item_sub_catg;
            $GLOBALS['budget_grand_total'] = $select_budget_item -> budget_grand_total;
            $GLOBALS['budget_item_amount'] = $select_budget_item -> budget_item_amount;
            $GLOBALS['budget_item_pct'] = $select_budget_item -> budget_item_pct;
            $GLOBALS['budget_other_dtl'] = $select_budget_item -> budget_other_dtl;
			
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
		}
		
		function InsertPapAddr(){
			// echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_budget.php');
            $insert_budget_item = new ProjectBudget();
            //$client_name = $project_client -> client_name;
            $insert_budget_item -> selected_project_id = $_POST['ProjectID'];
            $insert_budget_item -> selected_project_code = $_POST['ProjectCode'];
            $insert_budget_item -> budget_item_sub_catg = $_POST['SubCategories'];
            $insert_budget_item -> budget_item_amount = $_POST['BudgetValue'];
            $insert_budget_item -> budget_other_dtl = $_POST['BudgetDetails'];

            $insert_budget_item -> InsertBudgetItem();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $insert_budget_item -> selected_project_id . '&ProjectCode=' . $insert_budget_item -> selected_project_code . '#Budget');
            exit();
		}
		
		function UpdatePapAddr(){
			// echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_budget.php');
            $update_budget_item = new ProjectBudget();
            //$client_name = $project_client -> client_name;
            $update_budget_item -> budget_item_id = $_POST['BudgetID'];
            $update_budget_item -> selected_project_id = $_POST['ProjectID'];
            $update_budget_item -> selected_project_code = $_POST['ProjectCode'];
            $update_budget_item -> budget_item_sub_catg = $_POST['SubCategories'];
            $update_budget_item -> budget_item_amount = $_POST['BudgetValue'];
            $update_budget_item -> budget_other_dtl = $_POST['BudgetDetails'];

            $update_budget_item -> UpdateBudgetItem();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $update_budget_item -> selected_project_id . '&ProjectCode=' . $update_budget_item -> selected_project_code . '#Budget');
            exit();
		}
		
		function DeletePapAddr(){
			include_once ('../code/code_project_budget.php');
            $delete_budget_item = new ProjectBudget();
            $delete_budget_item -> budget_item_id = $_GET['BudgetID'];

            $delete_budget_item -> selected_project_id = $_GET['ProjectID'];
            $delete_budget_item -> selected_project_code = $_GET['ProjectCode'];

            $delete_budget_item -> DeleteBudgetItem();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $delete_budget_item -> selected_project_id . '&ProjectCode=' . $delete_budget_item -> selected_project_code . '#Budget');
            exit();
		}
		
		function BindDistricts(){
			include_once ('../code/code_pap_address.php');
            $bind_districts = new PapAddress();
            # $bind_districts -> selected_project_id = $_GET["ProjectID"];
            $bind_districts -> BindDistrict();
			
		}
		
		function BindCounties(){
			include_once ('../code/code_pap_address.php');
            $bind_districts = new PapAddress();
            # $bind_districts -> selected_project_id = $_GET["ProjectID"];
            $bind_districts -> BindDistrict();
			
		}
		
		function BindSubCounties(){
			include_once ('../code/code_pap_address.php');
            $bind_districts = new PapAddress();
            # $bind_districts -> selected_project_id = $_GET["ProjectID"];
            $bind_districts -> BindDistrict();
			
		}
		
		function BindVillages(){
			include_once ('../code/code_pap_address.php');
            $bind_districts = new PapAddress();
            # $bind_districts -> selected_project_id = $_GET["ProjectID"];
            $bind_districts -> BindDistrict();
			
		}
		
		?>
		
		<script type="text/javascript" >
		
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
                        var myarray = JSON.parse(httpxml.responseText);
                        // Remove the options from 2nd dropdown list
                        for ( j = document.Address.Counties.options.length - 1; j >= 0; j--) {
                            document.Address.Counties.remove(j);
                        }
                        
                        for ( j = document.Address.SubCounties.options.length - 1; j >= 0; j--) {
                            document.Address.SubCounties.remove(j);
                        }
                        
                        for ( j = document.Address.Villages.options.length - 1; j >= 0; j--) {
                            document.Address.Villages.remove(j);
                        }
                        
                        var default_cty = document.createElement("option");
                        default_cty.text = "-- Select County --";
                        document.Address.Counties.options.add(default_cty);
                        
                        var default_scty = document.createElement("option");
                        default_scty.text = "-- Select Sub County --";
                        document.Address.SubCounties.options.add(default_scty);
                        
                        var default_vill = document.createElement("option");
                        default_vill.text = "-- Select Village --";
                        document.Address.Villages.options.add(default_vill);
						
                        for ( i = 0; i < myarray.data.length; i++) {
	                            var optn = document.createElement("option");
	                            optn.text = myarray.data[i].COUNTY;
	                            optn.value = myarray.data[i].ID;
	                            document.Address.Counties.options.add(optn);
                        }
                    }
                }// end of function stateck
				
				var iMode = arguments[1];
                var url = "../code/code_drop_county.php";
                var element_id = document.getElementById('SelectDistrict').value;
                url = url + "?Mode=County&Element=" + element_id;
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
                        for ( j = document.Address.SubCounties.options.length - 1; j >= 0; j--) {
                            document.Address.SubCounties.remove(j);
                        }
                        
                        for ( j = document.Address.Villages.options.length - 1; j >= 0; j--) {
                            document.Address.Villages.remove(j);
                        }
                        
                        var default_scty = document.createElement("option");
                        default_scty.text = "-- Select Sub County --";
                        document.Address.SubCounties.options.add(default_scty);
                        
                        var default_vill = document.createElement("option");
                        default_vill.text = "-- Select Village --";
                        document.Address.Villages.options.add(default_vill);
						
                        for ( i = 0; i < myarray.data.length; i++) {
	                            var optn = document.createElement("option");
	                            optn.text = myarray.data[i].SUBCOUNTY;
	                            optn.value = myarray.data[i].ID;
	                            document.Address.SubCounties.options.add(optn);
                        }
                    }
                }// end of function stateck
				
				var iMode = arguments[1];
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
                        for ( j = document.Address.Villages.options.length - 1; j >= 0; j--) {
                            document.Address.Villages.remove(j);
                        }
                        
                        var default_vill = document.createElement("option");
                        default_vill.text = "-- Select Village --";
                        document.Address.Villages.options.add(default_vill);
						
                        for ( i = 0; i < myarray.data.length; i++) {
	                            var optn = document.createElement("option");
	                            optn.text = myarray.data[i].VILLAGE;
	                            optn.value = myarray.data[i].ID;
	                            document.Address.Villages.options.add(optn);
                        }
                    }
                }// end of function stateck
				
				var iMode = arguments[1];
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
					Social Economic, Bio Data
				</div>
				<br>
				<div class="container">
					<ul class="nav nav-tabs">
						<li class="inactive">
							<a >&nbsp;</a>
						</li>
						<li class="active">
							<a data-toggle="tab" href="#BasicInfo">Basic Info</a>
						</li>
						<li>
							<a data-toggle="tab" href="#PapAddress">Addresses</a>
						</li>
						<li>
							<a data-toggle="tab" href="#Family">Family Members</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
						<li class="inactive">
							<a  href="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						</li>
					</ul>
					<div class="tab-content">

						<!-- This is the Basic Info Screen -->
						<div id="BasicInfo" class="tab-pane active">
							<p>
								This is the Basic Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateBasicInfo&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#BasicInfo'; ?>" method="post" autocomplete="off">
								<table class="formTable">
									<input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                    <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
									<tr>
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
									</tr>
									<tr>
										<td class="formLabel">PAP Name:</td>
									</tr>
									<tr>
										<td colspan="2" ><span class="formSingleLineBox" style="width:610px;">
										    <input type="text" value="<?php if (isset($GLOBALS['pap_name'])) { echo $GLOBALS['pap_name']; } ?>" name="PapName" style="width: 580px;" /> 
										</span></td>
									</tr>
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
										<td><span class="formDropDownBox">
											<select name="PapSex" >
                                                <option value="">-- Select Sex --</option>
                                                <option value="Female" <?php if (isset($GLOBALS['pap_sex']) && $GLOBALS['pap_sex'] == 'Female') { echo 'selected'; }  ?> >Female</option>
                                                <option value="Male" <?php if (isset($GLOBALS['pap_sex']) && $GLOBALS['pap_sex'] == 'Male') { echo 'selected'; }  ?> >Male</option> 
                                        	</select>
										</span></td>
										<td><span class="formDropDownBox">
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
										<td><span class="formDropDownBox">
										    <select name="PapTribe" id="SelectTribe" >
                                               <option value="">-- Select Tribe --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['TribeID'])) { BindTribe(); } ?>
                                            </select><a class="LinkInBox" href="#">New</a>
										</span></td>
										<td><span class="formDropDownBox">
										    <select name="PapReligion" id="SelectReligion" >
                                                <option value="">-- Select Religion --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['ReligionID'])) { BindReligion(); } ?>
                                            </select><a class="LinkInBox" href="#">New</a>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Select Occupation:</td>
										<td class="formLabel">Phone Number:</td>
									</tr>
									<tr>
										<td><span class="formDropDownBox">
										    <select name="PapOccupation" id="SelectOccupation" >
                                                <option value="">-- Select Occupation --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['OccupnID'])) { BindOccupation(); } ?>
                                            </select><a class="LinkInBox" href="#">New</a>
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
										<td class="formLabel">Email Address:</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">
											<input type="text" value="<?php if (isset($GLOBALS['email'])) { echo $GLOBALS['email']; } ?>" name="PapEmail" style="width: 580px;" placeholder="Enter Contact Email" />
										</span></td>
									</tr>
									<tr>
										<td><span class="saveButtonArea"> <input type="submit" value="Update" name="UpdateBasicInfo"/></span></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
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
											<img src="images/20150912_161516.png" width="250" height="250" alt=""/>
										</div></td>
									</tr>
									<tr>
										<td><span class="formLinks SideBar" ><a href="#">Upload Photo</a></span><span class="formLinks" ><a href="#">Delete Photo</a></span></td>
									</tr>
								</table>
								
							</div>
						</div>

						<!-- This is the PAP Address Screen -->
						<div id="PapAddress" class="tab-pane">
							<p>
								This is the PAP Address Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<form name="Address" >
								<table>
									<tr>
										<td class="formLabel">Plot No, Road:</td>
										<td class="formLabel">
										<input type="checkbox">
										&nbsp;&nbsp;Is Main Residence?</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Address Details</span></td>
									</tr>
									<tr>
										<td class="formLabel">Select District:</td>
										<td class="formLabel">Select County:</td>
									</tr>
									<tr>
										<td><span class="formDropDownBox" >
											<select name="Districts" id="SelectDistrict" onchange="BindCounties()" >
                                                <option value="" >-- Select District --</option>
                                                <?php if (isset($_GET['ProjectID']) ) { BindDistricts(); } ?>
                                            </select><a class="LinkInBox" href="#" >New</a>
                                            </span>
                                        </td>
										<td><span class="formDropDownBox">
											<select name="Counties" id="SelectCounty" onchange="BindSubCounties()" >
                                                <option value="" >-- Select County --</option>
                                                <?php # if (isset($_GET['ProjectID']) ) { BindCounties(); } ?>
                                            </select><a class="LinkInBox" href="#" >New</a>
                                            </span>
                                        </td>
									</tr>
									<tr>
										<td class="formLabel">Select SubCounty:</td>
										<td class="formLabel">Select Village:</td>
									</tr>
									<tr>
										<td><span class="formDropDownBox" >
											<select name="SubCounties" id="SelectSubCty" onchange="BindVillages()" >
                                                <option value="" >-- Select Sub County --</option>
                                                <?php # if (isset($_GET['ProjectID']) ) { BindSubCounties(); } ?>
                                            </select><a class="LinkInBox" href="#" >New</a>
                                            </span>
                                        </td>
										<td><span class="formDropDownBox" >
											<select name="Villages" id="SelectVillage" >
                                                <option value="" >-- Select Village --</option>
                                                <?php # if (isset($_GET['ProjectID']) ) { BindVillages(); } ?>
                                            </select><a class="LinkInBox" href="#" >New</a>
                                            </span>
                                        </td>
									</tr>
									<tr>
										<td><a class="saveButtonArea" href="#">Save / Finish </a></td>
									</tr>
								</table>
								</form>
								
								
								<table class="detailGrid" style="width:560px; margin-top:25px;">
									<tr>
										<td class = "detailGridHead">#</td>
										<td  class = "detailGridHead">Address Details:</td>
										<td  class = "detailGridHead">Modify:</td>
									</tr>
									<?php if (isset($_GET['ProjectID'])) { LoadPapAddr(); } ?>
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
                                    <a href="<?php if (isset($_GET['AddrID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewAddress&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_addr_next_page'] . '#PapAddress'; } 
                                    else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_addr_next_page'] . '#PapAddress'; } ?>" >Next</a>
                            	</span>
								
							</div>
						</div>

						<!-- This is the family info screen -->
						<div id="Family" class="tab-pane">
							<p>
								This is the Family Info Screen
							</p>
							<div style="width:600px; float:left; margin-top:10px; margin-right:20px;">
								<table>
									<tr>
										<td class="formLabel">Family Member Name:</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width:610px;">Enter Name Of</span></td>
									</tr>
									<tr>
										<td class="formLabel">Sex:</td>
										<td class="formLabel">Relation Type:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select Sex</span></td>
										<td><span class="formSingleLineBox">Select Relation Type</span></td>
									</tr>
									<tr>
										<td class="formLabel">Date Of Birth:</td>
										<td class="formLabel">Place Of Birth:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select DOB</span></td>
										<td><span class="formSingleLineBox">Enter POB</span></td>
									</tr>
									<tr>
										<td class="formLabel">Tribe:</td>
										<td class="formLabel">Religion:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Select Tribe</span></td>
										<td><span class="formSingleLineBox">Select Religion</span></td>
									</tr>
									<tr>
										<td><a class="saveButtonArea" href="#">Save / Finish</a></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>

								<table class="detailGrid" style="width:560px; margin-top:25px;">
									<tr>
										<td class = "detailGridHead">#</td>
										<td  class = "detailGridHead">Address Details:</td>
										<td  class = "detailGridHead">Age:</td>
										<td  class = "detailGridHead">Relation:</td>
										<td  class = "detailGridHead">Modify:</td>
									</tr>
									<tr>
										<td>1</td>
										<td>Karuma, Project</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>2</td>
										<td>Standard, Gauge, Railway</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>3</td>
										<td>Uganda, Kenya Oil, Pipeline</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>4</td>
										<td>Kasese, Kinshasa, Super Highway</td>
										<td>30</td>
										<td>Son, Daughter</td>
										<td><a href="#"><img src="UI/images/Edit.png" alt="" class="EditDeleteButtons"/></a><a href="#"><img src="UI/images/delete.png" alt="" class="EditDeleteButtons"/></a></td>
									</tr>
									<tr>
										<td>5</td>
										<td>Entebbe, Express, Project</td>
										<td>30</td>
										<td>Son, Daughter</td>
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

							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php include ('ui_footer.php'); ?>
		
		<script src="js/date_picker/pikaday.js"></script>
		<script>
			var picker = new Pikaday({
				field : document.getElementById('birth_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1980, 0, 1),
				maxDate : new Date(2050, 12, 31),
				yearRange : [1980, 2050]
			});
		</script>

		</body>
</html>