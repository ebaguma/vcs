<! doctype html >
    
    <?php
    ob_start(NULL, 0, PHP_OUTPUT_HANDLER_CLEANABLE);
    //global $load_client_page;

    if (isset($_GET['LogOut'])) {
        include_once ('../code/code_index.php');

        $logout = new LogInOut();
        // session_start();
        if (isset($_SESSION['session_user_id'])) {
            $logout -> user_id = $_SESSION['session_user_id'];
        } else {
            $logout -> user_id = $_COOKIE["last_user"];
        }
        $logout -> LogOff();
    }

    if (isset($_POST['Mode']) && $_POST['Mode'] == 'EditDetails') {
        UpdateProjectDetails();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'Read') {
        LoadProjDetails();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'EditClients') {
        UpdateProjectClients();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewClients') {
        SelectProjectClient();
        LoadProjDetails();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertClients') {
        InsertProjectClients();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteClients') {
        DeleteProjectClient();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertStaff') {
        InsertClientStaff();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateStaff') {
        UpdateClientStaff();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteStaff') {
        DeleteClientStaff();
    }

    if (isset($_GET['StaffID'])) {
        SelectClientStaff();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewBudget') {
        LoadProjDetails();
        SelectBudgetItem();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertBudget') {
        InsertBudgetItem();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateBudget') {
        UpdateBudgetItem();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteBudget') {
        DeleteBudgetItem();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewExpense') {
        LoadProjDetails();
        SelectExpense();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdateExpense') {
        UpdateExpense();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertExpense') {
        InsertExpense();
    }

    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeleteExpense') {
        DeleteExpense();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewPersonnel') {
        LoadProjDetails();
        SelectProjectStaff();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdatePersonnel') {
        UpdateProjectStaff();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertPersonnel') {
        InsertProjectStaff();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeletePersonnel') {
        DeleteProjectStaff();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewPap') {
        LoadProjDetails();
        SelectProjectPap();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'SearchPap') {
        LoadProjDetails();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'UpdatePap') {
        UpdateProjectPap();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'InsertPap') {
        InsertProjectPap();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'DeletePap') {
        DeleteProjectPap();
    }
    
    if (isset($_GET['Mode']) && $_GET['Mode'] == 'ViewDispute') {
        LoadProjDetails();
        SelectProjectDispute();
    }
    
    ?>

<html>
	<head>
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="Me" content="Projects">
		<title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Projects</title>
		
		<?php
        include ('ui_header.php');

        // <!-- meta charset="utf-8" -->

        // <!--#include virtual="ui_header.php"-->

        // Project Details
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

        function LogOut() {
            include_once ('../code/code_index.php');

            $logout = new LogInOut();
            // session_start();
            if (isset($_SESSION['session_user_id'])) {
                $logout -> user_id = $_SESSION['session_user_id'];
            } else {
                $logout -> user_id = $_COOKIE["last_user"];
            }
            $logout -> LogOff();
        }

        function LoadProjDetails() {
            include_once ('../code/code_project_detail.php');

            $project_id = $_GET['ProjectID'];

            $project_details = new ProjectDetail();
            $project_details -> project_id = $project_id;
            $project_details -> LoadProjectDetails();

            global $project_name;
            $project_name = $project_details -> project_name;
            global $project_code;
            $project_code = $project_details -> project_code;

            $selected_project_code = $project_code;

            SelectProject();

            global $start_date;
            $start_date = $project_details -> start_date;
            global $end_date;
            $end_date = $project_details -> end_date;
            global $project_obj;
            $project_obj = $project_details -> project_obj;
            global $project_desc;
            $project_desc = $project_details -> project_desc;
        }

        function UpdateProjectDetails() {
            include_once ('../code/code_project_detail.php');
            // set update parameters
            $update_details = new ProjectDetail();
            $update_details -> project_id = $_POST['ProjectID'];
            $_SESSION['ProjectID'] = $_POST['ProjectID'];
            $update_details -> project_name = $_POST['ProjectName'];
            $update_details -> project_code = $_POST['ProjectCode'];
            $_SESSION['ProjectCode'] = $_POST['ProjectCode'];
            $update_details -> start_date = $_POST['StartDate'];
            $update_details -> end_date = $_POST['EndDate'];
            $update_details -> project_obj = $_POST['ProjectObj'];
            $update_details -> project_desc = $_POST['ProjectDesc'];
            $update_details -> UpdateProjectDetails();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $_SESSION['ProjectID'] . '&ProjectCode=' . $_SESSION['ProjectCode'] . '#Details');
            exit();
        }
		?>

        <?php
        
        function LoadProjectClients() {
            include_once ('../code/code_project_client.php');
            $project_clients = new ProjectClient();
            $project_clients -> select_project_id = $_GET["ProjectID"];

            if (isset($_GET['ClientPage'])) {
                $GLOBALS['load_page'] = $_GET['ClientPage'];
            } else {
                $GLOBALS['load_page'] = 1;
            }

            //set pagination parameters
            $project_clients -> ReadPageParams();
            $GLOBALS['num_pages'] = $project_clients -> client_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['load_page'] == 1) {
                $project_clients -> client_record_num = 0;
                $project_clients -> client_data_offset = 0;
            } else if ($GLOBALS['load_page'] <= $project_clients -> client_last_page) {
                $project_clients -> client_data_offset = ($GLOBALS['load_page'] - 1) * $project_clients -> client_page_rows;
                $project_clients -> client_record_num = ($GLOBALS['load_page'] - 1) * $project_clients -> client_page_rows; ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['load_page'] = 1;
                $project_clients -> client_record_num = 0;
                $project_clients -> client_data_offset = 0;
            }

            if (($GLOBALS['load_page'] + 1) <= $project_clients -> client_last_page) {
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
            $project_clients -> LoadProjectClients();
        }

        function SelectProjectClient() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_client.php');
            $project_client = new ProjectClient();
            $project_client -> client_id = $_GET["ClientID"];
            $project_client -> SelectProjectClient();
            //$client_name = $project_client -> client_name;
            $GLOBALS['client_name'] = $project_client -> client_name;
            $GLOBALS['client_number'] = $project_client -> client_number;
            $GLOBALS['client_email'] = $project_client -> client_email;
            $GLOBALS['client_website'] = $project_client -> client_website;
            $GLOBALS['client_contact_person'] = $project_client -> client_contact_person;
            $GLOBALS['client_addr'] = $project_client -> client_addr;
        }

        function UpdateProjectClients() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_client.php');
            $update_client = new ProjectClient();
            //$client_name = $project_client -> client_name;
            $update_client -> client_id = $_POST['ClientID'];
            $update_client -> client_name = $_POST['ClientName'];
            $update_client -> client_number = $_POST['ClientNumber'];
            $update_client -> client_email = $_POST['ClientEmail'];
            $update_client -> client_website = $_POST['ClientWebsite'];
            $update_client -> client_contact_person = $_POST['PrincipalContact'];
            $update_client -> client_addr = $_POST['ClientAddr'];

            $update_client -> select_project_id = $_POST['ProjectID'];
            $update_client -> select_project_code = $_POST['ProjectCode'];

            $update_client -> UpdateClient();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=ViewClients&ProjectID=' . $update_client -> select_project_id . '&ProjectCode=' . $update_client -> select_project_code . '&ClientID=' . $update_client -> client_id . '#Clients');
            exit();
        }

        function DeleteProjectClient() {
            include_once ('../code/code_project_client.php');
            $delete_client = new ProjectClient();
            $delete_client -> client_id = $_GET['ClientID'];

            $delete_client -> select_project_id = $_GET['ProjectID'];
            $delete_client -> select_project_code = $_GET['ProjectCode'];

            $delete_client -> DeleteClient();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $delete_client -> select_project_id . '&ProjectCode=' . $delete_client -> select_project_code . '#Clients');
            exit();
        }

        function InsertProjectClients() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_client.php');
            $insert_client = new ProjectClient();
            //$client_name = $project_client -> client_name;
            $insert_client -> client_name = $_POST['ClientName'];
            $insert_client -> client_number = $_POST['ClientNumber'];
            $insert_client -> client_email = $_POST['ClientEmail'];
            $insert_client -> client_website = $_POST['ClientWebsite'];
            $insert_client -> client_contact_person = $_POST['PrincipalContact'];
            $insert_client -> client_addr = $_POST['ClientAddr'];

            $insert_client -> select_project_id = $_POST['ProjectID'];
            $insert_client -> select_project_code = $_POST['ProjectCode'];

            $insert_client -> InsertClient();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $insert_client -> select_project_id . '&ProjectCode=' . $insert_client -> select_project_code . '#Clients');
            exit();
        }
        ?>

        <?php
        
        function LoadClientStaff() {
            include_once ('../code/code_project_client.php');
            $load_client_staff = new ProjectClient();
            $load_client_staff -> client_id = $_GET["ClientID"];

            if (isset($_GET['StaffPage'])) {
                $GLOBALS['staff_load_page'] = $_GET['StaffPage'];
            } else {
                $GLOBALS['staff_load_page'] = 1;
            }

            //set pagination parameters
            $load_client_staff -> StaffPageParams();
            $GLOBALS['staff_num_pages'] = $load_client_staff -> staff_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['staff_load_page'] == 1) {
                $load_client_staff -> staff_record_num = 0;
                $load_client_staff -> staff_data_offset = 0;
            } else if ($GLOBALS['staff_load_page'] <= $load_client_staff -> staff_last_page) {
                $load_client_staff -> staff_data_offset = ($GLOBALS['staff_load_page'] - 1) * $load_client_staff -> staff_page_rows;
                $load_client_staff -> staff_record_num = ($GLOBALS['staff_load_page'] - 1) * $load_client_staff -> staff_page_rows; ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['staff_load_page'] = 1;
                $load_client_staff -> staff_record_num = 0;
                $load_client_staff -> staff_data_offset = 0;
            }

            if (($GLOBALS['staff_load_page'] + 1) <= $load_client_staff -> staff_last_page) {
                $GLOBALS['staff_next_page'] = $GLOBALS['staff_load_page'] + 1;
            } else {
                $GLOBALS['staff_next_page'] = 1;
            }

            if (($GLOBALS['staff_load_page'] - 1) >= 1) {
                $GLOBALS['staff_prev_page'] = $GLOBALS['staff_load_page'] - 1;
            } else {
                $GLOBALS['staff_prev_page'] = 1;
            }

            //Loading Projects
            $load_client_staff -> LoadClientStaff();
        }

        function SelectClientStaff() {
            include_once ('../code/code_project_client.php');
            $select_client_staff = new ProjectClient();
            $select_client_staff -> staff_id = $_GET["StaffID"];
            $select_client_staff -> SelectClientStaff();
            //$client_name = $project_client -> client_name;
            $GLOBALS['staff_name'] = $select_client_staff -> staff_name;
            $GLOBALS['staff_number'] = $select_client_staff -> staff_number;
            $GLOBALS['staff_email'] = $select_client_staff -> staff_email;
            $GLOBALS['staff_role'] = $select_client_staff -> staff_role;
        }

        function UpdateClientStaff() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_client.php');
            $update_staff = new ProjectClient();
            $update_staff -> staff_id = $_POST['StaffID'];
            $update_staff -> staff_name = $_POST['StaffName'];
            $update_staff -> staff_number = $_POST['StaffNumber'];
            $update_staff -> staff_email = $_POST['StaffEmail'];
            $update_staff -> staff_role = $_POST['StaffRole'];

            $project_id = $_POST['ProjectID'];
            $project_code = $_POST['ProjectCode'];
            $client_id = $_POST['ClientID'];

            $update_staff -> UpdateClientStaff();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=ViewClients&ProjectID=' . $project_id . '&ProjectCode=' . $project_code . '&ClientID=' . $client_id . '&StaffID=' . $update_staff -> staff_id . '#Clients');
            exit();
        }

        function DeleteClientStaff() {
            include_once ('../code/code_project_client.php');
            $delete_staff = new ProjectClient();
            $delete_staff -> staff_id = $_GET['StaffID'];

            $project_id = $_GET['ProjectID'];
            $project_code = $_GET['ProjectCode'];
            $client_id = $_GET['ClientID'];

            $delete_staff -> DeleteClientStaff();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=ViewClients&ProjectID=' . $project_id . '&ProjectCode=' . $project_code . '&ClientID=' . $client_id . '#Clients');
            exit();
        }

        function InsertClientStaff() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_client.php');
            $insert_staff = new ProjectClient();
            $insert_staff -> client_id = $_POST['ClientID'];
            $insert_staff -> staff_name = $_POST['StaffName'];
            $insert_staff -> staff_number = $_POST['StaffNumber'];
            $insert_staff -> staff_email = $_POST['StaffEmail'];
            $insert_staff -> staff_role = $_POST['StaffRole'];

            $project_id = $_POST['ProjectID'];
            $project_code = $_POST['ProjectCode'];

            $insert_staff -> InsertClientStaff();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=ViewClients&ProjectID=' . $project_id . '&ProjectCode=' . $project_code . '&ClientID=' . $insert_staff -> client_id . '#Clients');
            exit();
        }
        ?>

        <?php

        function LoadBudgetItems() {
            include_once ('../code/code_project_budget.php');
            $load_budget_items = new ProjectBudget();
            $load_budget_items -> selected_project_id = $_GET["ProjectID"];

            if (isset($_GET['BudgetPage'])) {
                $GLOBALS['budget_load_page'] = $_GET['BudgetPage'];
            } else {
                $GLOBALS['budget_load_page'] = 1;
            }

            //set pagination parameters
            $load_budget_items -> BudgetPageParams();
            $GLOBALS['budget_num_pages'] = $load_budget_items -> budget_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['budget_load_page'] == 1) {
                $load_budget_items -> budget_record_num = 0;
                $load_budget_items -> budget_data_offset = 0;
            } else if ($GLOBALS['budget_load_page'] <= $load_budget_items -> budget_last_page) {
                $load_budget_items -> budget_data_offset = ($GLOBALS['budget_load_page'] - 1) * $load_budget_items -> budget_page_rows;
                $load_budget_items -> budget_record_num = ($GLOBALS['budget_load_page'] - 1) * $load_budget_items -> budget_page_rows; ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['budget_load_page'] = 1;
                $load_budget_items -> budget_record_num = 0;
                $load_budget_items -> budget_data_offset = 0;
            }

            if (($GLOBALS['budget_load_page'] + 1) <= $load_budget_items -> budget_last_page) {
                $GLOBALS['budget_next_page'] = $GLOBALS['budget_load_page'] + 1;
            } else {
                $GLOBALS['budget_next_page'] = 1;
            }

            if (($GLOBALS['budget_load_page'] - 1) >= 1) {
                $GLOBALS['budget_prev_page'] = $GLOBALS['budget_load_page'] - 1;
            } else {
                $GLOBALS['budget_prev_page'] = 1;
            }

            //Loading Projects
            $load_budget_items -> LoadBudgetItems();
        }

        // Used to Load Financial Categories for Budget, and Expense Screen
        function BindBudgetCat() {
            include_once ('../code/code_project_budget.php');
            $bind_budget_categories = new ProjectBudget();
            $bind_budget_categories -> selected_project_id = $_GET["ProjectID"];
            $bind_budget_categories -> LoadBudgetCATG();

        }
        
        // Used to Load Financial Sub Categories for Budget, and Expense Screen
        function BindBudgetSubCat() {
            include_once ('../code/code_project_budget.php');
            $bind_budget_sub_cat = new ProjectBudget();
            
            if (isset($_GET["Cat"])){
            $bind_budget_sub_cat -> budget_category_id = $_GET["Cat"];
            }else if (isset($_GET["ExpCat"])){
                $bind_budget_sub_cat -> budget_category_id = $_GET["ExpCat"];
            }

            $budget_category_id = $bind_budget_sub_cat -> budget_category_id;
            $bind_budget_sub_cat -> LoadBudgetSubCATG($budget_category_id);
        }

        function SelectBudgetItem() {
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
        }

        function InsertBudgetItem() {
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
        
        function UpdateBudgetItem(){
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
        
        function DeleteBudgetItem(){
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
        
		?>
		
		<?php
		
		function LoadExpenses(){
		    include_once ('../code/code_project_expense.php');
            $load_expense_items = new ProjectExpense();
            $load_expense_items -> selected_project_id = $_GET["ProjectID"];

            if (isset($_GET['ExpensePage'])) {
                $GLOBALS['expense_load_page'] = $_GET['ExpensePage'];
            } else {
                $GLOBALS['expense_load_page'] = 1;
            }

            //set pagination parameters
            $load_expense_items -> ExpensePageParams();
            $GLOBALS['expense_num_pages'] = $load_expense_items -> expense_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['expense_load_page'] == 1) {
                $load_expense_items -> expense_record_num = 0;
                $load_expense_items -> expense_data_offset = 0;
            } else if ($GLOBALS['expense_load_page'] <= $load_expense_items -> expense_last_page) {
                $load_expense_items -> expense_data_offset = ($GLOBALS['expense_load_page'] - 1) * $load_expense_items -> expense_page_rows;
                $load_expense_items -> expense_record_num = ($GLOBALS['expense_load_page'] - 1) * $load_expense_items -> expense_page_rows; ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['expense_load_page'] = 1;
                $load_expense_items -> expense_record_num = 0;
                $load_expense_items -> expense_data_offset = 0;
            }

            if (($GLOBALS['expense_load_page'] + 1) <= $load_expense_items -> expense_last_page) {
                $GLOBALS['expense_next_page'] = $GLOBALS['expense_load_page'] + 1;
            } else {
                $GLOBALS['expense_next_page'] = 1;
            }

            if (($GLOBALS['expense_load_page'] - 1) >= 1) {
                $GLOBALS['expense_prev_page'] = $GLOBALS['expense_load_page'] - 1;
            } else {
                $GLOBALS['expense_prev_page'] = 1;
            }

            //Loading Projects
            $load_expense_items -> LoadExpenses();
		}
        
        function SelectExpense(){
            include_once ('../code/code_project_expense.php');
            $select_expense_item = new ProjectExpense();
            $select_expense_item -> selected_project_id = $_GET["ProjectID"];
            $select_expense_item -> expense_item_id = $_GET["ExpenseID"];
            
            $select_expense_item -> SelectExpense();

            $GLOBALS['expense_item_id'] = $select_expense_item -> expense_item_id;
            $GLOBALS['expense_item_catg'] = $select_expense_item -> expense_item_catg;
            $GLOBALS['expense_item_sub_catg'] = $select_expense_item -> expense_item_sub_catg;
            $GLOBALS['expense_item_name'] = $select_expense_item -> expense_item_name;
            $GLOBALS['expense_item_amount'] = $select_expense_item -> expense_item_amount;
            $GLOBALS['expense_item_pct'] = $select_expense_item -> expense_item_pct;
            $GLOBALS['expense_other_dtl'] = $select_expense_item -> expense_other_dtl;
            $GLOBALS['expense_date'] = $select_expense_item -> expense_date;
        }
        
        function UpdateExpense(){
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_expense.php');
            $update_expense_item = new ProjectExpense();
            //$client_name = $project_client -> client_name;
            $update_expense_item -> expense_item_id = $_POST['ExpenseID'];
            $update_expense_item->expense_item_name = $_POST['ExpenseName'];
            $update_expense_item -> expense_item_sub_catg = $_POST['ExpSubCategories'];
            $update_expense_item -> expense_item_amount = $_POST['ExpenseAmount'];
            $update_expense_item -> expense_date = $_POST['ExpenseDate'];
            $update_expense_item -> expense_other_dtl = $_POST['ExpenseDetails'];
            
            $update_expense_item -> selected_project_id = $_POST['ProjectID'];
            $update_expense_item -> selected_project_code = $_POST['ProjectCode'];

            $update_expense_item -> UpdateXpense();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $update_expense_item -> selected_project_id . '&ProjectCode=' . $update_expense_item -> selected_project_code . '#Expenses');
            exit();
        }
        
        function InsertExpense(){
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_expense.php');
            $insert_expense_item = new ProjectExpense();
            //$client_name = $project_client -> client_name;
            $insert_expense_item->expense_item_name = $_POST['ExpenseName'];
            $insert_expense_item -> expense_item_sub_catg = $_POST['ExpSubCategories'];
            $insert_expense_item -> expense_item_amount = $_POST['ExpenseAmount'];
            $insert_expense_item -> expense_date = $_POST['ExpenseDate'];
            $insert_expense_item -> expense_other_dtl = $_POST['ExpenseDetails'];
            
            $insert_expense_item -> selected_project_id = $_POST['ProjectID'];
            $insert_expense_item -> selected_project_code = $_POST['ProjectCode'];

            $insert_expense_item -> InsertExpense();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $insert_expense_item -> selected_project_id . '&ProjectCode=' . $insert_expense_item -> selected_project_code . '#Expenses');
            exit();
        }
        
        function DeleteExpense(){
            include_once ('../code/code_project_expense.php');
            $delete_expense_item = new ProjectExpense();
            $delete_expense_item -> expense_item_id = $_GET['ExpenseID'];

            $delete_expense_item -> selected_project_id = $_GET['ProjectID'];
            $delete_expense_item -> selected_project_code = $_GET['ProjectCode'];

            $delete_expense_item -> DeleteXpense();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $delete_expense_item -> selected_project_id . '&ProjectCode=' . $delete_expense_item -> selected_project_code . '#Expenses');
            exit();
        }
		
		?>

        <?php

        function LoadProjectStaff() {
            include_once ('../code/code_project_personnel.php');
            $load_personnel_items = new ProjectStaff();
            $load_personnel_items -> selected_project_id = $_GET["ProjectID"];

            if (isset($_GET['StaffPage'])) {
                $GLOBALS['personnel_load_page'] = $_GET['StaffPage'];
            } else {
                $GLOBALS['personnel_load_page'] = 1;
            }

            //set pagination parameters
            $load_personnel_items -> ProjStaffPageParams();
            $GLOBALS['personnel_num_pages'] = $load_personnel_items -> personnel_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['personnel_load_page'] == 1) {
                $load_personnel_items -> personnel_record_num = 0;
                $load_personnel_items -> personnel_data_offset = 0;
            } else if ($GLOBALS['personnel_load_page'] <= $load_personnel_items -> personnel_last_page) {
                $load_personnel_items -> personnel_data_offset = ($GLOBALS['personnel_load_page'] - 1) * $load_personnel_items -> personnel_page_rows;
                $load_personnel_items -> personnel_record_num = ($GLOBALS['personnel_load_page'] - 1) * $load_personnel_items -> personnel_page_rows;
                ;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['personnel_load_page'] = 1;
                $load_personnel_items -> personnel_record_num = 0;
                $load_personnel_items -> personnel_data_offset = 0;
            }

            if (($GLOBALS['personnel_load_page'] + 1) <= $load_personnel_items -> personnel_last_page) {
                $GLOBALS['personnel_next_page'] = $GLOBALS['personnel_load_page'] + 1;
            } else {
                $GLOBALS['personnel_next_page'] = 1;
            }

            if (($GLOBALS['personnel_load_page'] - 1) >= 1) {
                $GLOBALS['personnel_prev_page'] = $GLOBALS['personnel_load_page'] - 1;
            } else {
                $GLOBALS['personnel_prev_page'] = 1;
            }

            //Loading Projects
            $load_personnel_items -> LoadProjectStaff();
        }

        function SelectProjectStaff(){
            include_once ('../code/code_project_personnel.php');
            $select_project_staff = new ProjectStaff();
            $select_project_staff -> selected_project_id = $_GET["ProjectID"];
            $select_project_staff -> personnel_user_id = $_GET["UserID"];
            $select_project_staff -> personnel_role_id = $_GET["RoleID"];
            
            $select_project_staff -> SelectProjectStaff();

            $GLOBALS['personnel_id'] = $select_project_staff -> personnel_id;
            $GLOBALS['personnel_user_id'] = $select_project_staff -> personnel_user_id;
            $GLOBALS['personnel_role_id'] = $select_project_staff -> personnel_role_id;
            $GLOBALS['personnel_other_dtl'] = $select_project_staff -> personnel_other_dtl;
        }

        function BindAllUsers() {
            include_once ('../code/code_project_personnel.php');
            $bind_all_users = new ProjectStaff();
            $bind_all_users -> BindAllUsers();
        }
        
        function BindAllRoles() {
            include_once ('../code/code_project_personnel.php');
            $bind_all_roles = new ProjectStaff();
            $bind_all_roles -> BindAllRoles();
        }
        
        function UpdateProjectStaff(){
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_personnel.php');
            $update_project_staff = new ProjectStaff();
            //$client_name = $project_client -> client_name;
            $update_project_staff -> personnel_id = $_POST['ID'];
            $update_project_staff->personnel_user_id = $_POST['UserID'];
            $update_project_staff -> personnel_role_id = $_POST['RoleID'];
            $update_project_staff -> personnel_other_dtl = $_POST['OtherDetail'];
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $update_project_staff -> session_user_id = $_SESSION['session_user_id'];
            }else if (session_status() == PHP_SESSION_ACTIVE){
                $update_project_staff -> session_user_id = $_SESSION['session_user_id'];
            }
            
            $update_project_staff -> selected_project_id = $_POST['ProjectID'];
            $update_project_staff -> selected_project_code = $_POST['ProjectCode'];

            $update_project_staff -> UpdateProjectStaff();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $update_project_staff -> selected_project_id . '&ProjectCode=' . $update_project_staff -> selected_project_code . '#Personnel');
            exit();    
        }

        function InsertProjectStaff(){
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_personnel.php');
            $insert_project_staff = new ProjectStaff();
            $insert_project_staff->personnel_user_id = $_POST['UserID'];
            $insert_project_staff -> personnel_role_id = $_POST['RoleID'];
            $insert_project_staff -> personnel_other_dtl = $_POST['OtherDetail'];
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $insert_project_staff -> session_user_id = $_SESSION['session_user_id'];
            }else if (session_status() == PHP_SESSION_ACTIVE){
                $update_project_staff -> session_user_id = $_SESSION['session_user_id'];
            }
            
            $insert_project_staff -> selected_project_id = $_POST['ProjectID'];
            $insert_project_staff -> selected_project_code = $_POST['ProjectCode'];

            $insert_project_staff -> InsertProjectStaff();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $insert_project_staff -> selected_project_id . '&ProjectCode=' . $insert_project_staff -> selected_project_code . '#Personnel');
            exit();    
        }

        function DeleteProjectStaff(){
            include_once ('../code/code_project_personnel.php');
            $delete_project_staff = new ProjectStaff;
            $delete_project_staff -> personnel_id = $_GET['ID'];

            $delete_project_staff -> selected_project_id = $_GET['ProjectID'];
            $delete_project_staff -> selected_project_code = $_GET['ProjectCode'];

            $delete_project_staff ->DeleteProjectStaff();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $delete_project_staff -> selected_project_id . '&ProjectCode=' . $delete_project_staff -> selected_project_code . '#Personnel');
            exit();
        }
        
		?>
		
		<?php

        function LoadProjectPaps() {
            include_once ('../code/code_project_pap.php');
            $load_project_paps = new ProjectPap();
            $load_project_paps -> selected_project_id = $_GET["ProjectID"];

            if (isset($_GET['GridPage'])) {
                $GLOBALS['pap_load_page'] = $_GET['GridPage'];
            } else {
                $GLOBALS['pap_load_page'] = 1;
            }

            //set pagination parameters
            $load_project_paps -> ProjectPapGridParams();
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
            $load_project_paps -> LoadProjectPaps();
        }

        function SearchProjectPaps() {
            include_once ('../code/code_project_pap.php');
            $search_project_paps = new ProjectPap();
            $search_project_paps -> selected_project_id = $_GET["ProjectID"];
            $search_project_paps -> pap_search = $_GET["KeyWord"];

            if (isset($_GET['GridPage'])) {
                $GLOBALS['pap_load_page'] = $_GET['GridPage'];
            } else {
                $GLOBALS['pap_load_page'] = 1;
            }

            //set pagination parameters
            $search_project_paps -> ProjectPapSearchParams();
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
            $search_project_paps -> SearchProjectPaps();
        }

        function SelectProjectPap() {
            include_once ('../code/code_project_pap.php');
            $select_project_pap = new ProjectPap();
            $select_project_pap -> pap_hhid = $_GET["HHID"];

            $select_project_pap -> SelectProjectPap();

            $GLOBALS['pap_name'] = $select_project_pap -> pap_name;
            $GLOBALS['pap_plot_ref'] = $select_project_pap -> pap_plot_ref;
            $GLOBALS['pap_design'] = $select_project_pap -> pap_design;
            $GLOBALS['pap_type'] = $select_project_pap -> pap_type;
        }

        function InsertProjectPap() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_pap.php');
            $insert_project_pap = new ProjectPap();
            $insert_project_pap -> pap_name = $_POST['PapName'];
            $insert_project_pap -> pap_plot_ref = $_POST['PlotRef'];
            $insert_project_pap -> pap_design = $_POST['PapDesign'];
            $insert_project_pap -> pap_type = $_POST['PapType'];

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $insert_project_pap -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $insert_project_pap -> session_user_id = $_SESSION['session_user_id'];
            }

            $insert_project_pap -> selected_project_id = $_POST['ProjectID'];
            $insert_project_pap -> selected_project_code = $_POST['ProjectCode'];

            $insert_project_pap -> InsertProjectPap();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $insert_project_pap -> selected_project_id . '&ProjectCode=' . $insert_project_pap -> selected_project_code . '#PAP');
            exit();
        }

        function UpdateProjectPap() {
            // echo '<script>alert(' . $_GET['ClientID'] . ');</script>';
            include_once ('../code/code_project_pap.php');
            $update_project_pap = new ProjectPap();
            //$client_name = $project_client -> client_name;
            $update_project_pap -> pap_hhid = $_POST['HHID'];
            $update_project_pap -> pap_name = $_POST['PapName'];
            $update_project_pap -> pap_plot_ref = $_POST['PlotRef'];
            $update_project_pap -> pap_design = $_POST['PapDesign'];
            $update_project_pap -> pap_type = $_POST['PapType'];

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $update_project_pap -> session_user_id = $_SESSION['session_user_id'];
            } else if (session_status() == PHP_SESSION_ACTIVE) {
                $update_project_pap -> session_user_id = $_SESSION['session_user_id'];
            }

            $update_project_pap -> selected_project_id = $_POST['ProjectID'];
            $update_project_pap -> selected_project_code = $_POST['ProjectCode'];

            $update_project_pap -> UpdateProjectPap();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=ViewPap&ProjectID=' . $update_project_pap -> selected_project_id . '&ProjectCode=' . $update_project_pap -> selected_project_code . '&HHID=' . $update_project_pap -> pap_hhid . '#PAP');
            exit();
        }

        function DeleteProjectPap() {
            include_once ('../code/code_project_pap.php');
            $delete_project_pap = new ProjectPap();
            $delete_project_pap -> pap_hhid = $_GET['HHID'];

            $delete_project_pap -> selected_project_id = $_GET['ProjectID'];
            $delete_project_pap -> selected_project_code = $_GET['ProjectCode'];

            $delete_project_pap -> DeleteProjectPap();
            unset($_POST);
            header('Refresh:0; url=ui_project_detail.php?Mode=Read&ProjectID=' . $delete_project_pap -> selected_project_id . '&ProjectCode=' . $delete_project_pap -> selected_project_code . '#PAP');
            exit();
        }
		?>
		
		<?php

        function BindDisputeCategory() {
            include_once ('../code/code_project_dispute.php');
            $bind_dispute_category = new ProjectDispute();
            $bind_dispute_category -> BindProjectDisputes();
        }

        function LoadProjectDisputes() {
            include_once ('../code/code_project_dispute.php');
            $load_proj_disp = new ProjectDispute();
            $load_proj_disp -> selected_project_id = $_GET["ProjectID"];

            if (isset($_GET['GridPage'])) {
                $GLOBALS['proj_disp_load_page'] = $_GET['GridPage'];
            } else {
                $GLOBALS['proj_disp_load_page'] = 1;
            }

            //set pagination parameters
            $load_proj_disp -> DisputePageParams();
            $GLOBALS['proj_disp_num_pages'] = $load_proj_disp -> proj_disp_last_page;

            //Handling grid pages and navigation
            if ($GLOBALS['proj_disp_load_page'] == 1) {
                $load_proj_disp -> proj_disp_record_num = 0;
                $load_proj_disp -> proj_disp_data_offset = 0;
            } else if ($GLOBALS['proj_disp_load_page'] <= $load_proj_disp -> proj_disp_last_page) {
                $load_proj_disp -> proj_disp_data_offset = ($GLOBALS['proj_disp_load_page'] - 1) * $load_proj_disp -> proj_disp_page_rows;
                $load_proj_disp -> proj_disp_record_num = ($GLOBALS['proj_disp_load_page'] - 1) * $load_proj_disp -> proj_disp_page_rows;
            } else {
                // echo '<script>alert("Page Is Out Of Range");</script>';
                $GLOBALS['proj_disp_load_page'] = 1;
                $load_proj_disp -> proj_disp_record_num = 0;
                $load_proj_disp -> proj_disp_data_offset = 0;
            }

            if (($GLOBALS['proj_disp_load_page'] + 1) <= $load_proj_disp -> proj_disp_last_page) {
                $GLOBALS['proj_disp_next_page'] = $GLOBALS['proj_disp_load_page'] + 1;
            } else {
                $GLOBALS['proj_disp_next_page'] = 1;
            }

            if (($GLOBALS['proj_disp_load_page'] - 1) >= 1) {
                $GLOBALS['proj_disp_prev_page'] = $GLOBALS['proj_disp_load_page'] - 1;
            } else {
                $GLOBALS['proj_disp_prev_page'] = 1;
            }

            //Loading Projects
            $load_proj_disp -> LoadProjectDisputes();
        }

        function SearchDisputePaps() {
            include_once ('../code/code_project_dispute.php');
            $search_project_paps = new ProjectDispute();
            $search_project_paps -> selected_project_id = $_GET["ProjectID"];
            $search_project_paps -> pap_search = $_GET["KeyWord"];
            $search_project_paps -> BindProjectPaps();
        }

        function BindAllProjectPaps() {
            include_once ('../code/code_project_dispute.php');
            $bind_all_project_paps = new ProjectDispute();
            $bind_all_project_paps -> selected_project_id = $_GET["ProjectID"];
            $bind_all_project_paps -> BindAllProjectPaps();
        }

        function SelectProjectDispute() {
            include_once ('../code/code_project_dispute.php');
            $select_project_dispute = new ProjectDispute();
            $select_project_dispute -> proj_disp_id = $_GET["DispID"];

            $select_project_dispute -> SelectProjectDispute();

            $GLOBALS['proj_disp_pap_id'] = $select_project_dispute -> proj_disp_pap_id;
            $GLOBALS['proj_disp_catg_id'] = $select_project_dispute -> proj_disp_catg_id;
            $GLOBALS['proj_disp_status'] = $select_project_dispute -> proj_disp_status;
            $GLOBALS['proj_disp_date_log'] = $select_project_dispute -> proj_disp_date_log;
            $GLOBALS['proj_disp_log_by'] = $select_project_dispute -> proj_disp_log_by;
            $GLOBALS['proj_disp_other_dtl'] = $select_project_dispute -> proj_disp_other_dtl;
        }
		?>

        <?php
        
        function createPassword($length) {
            $chars = "abcdefghijkmnopqrstuvwxyz023456789";
            srand((double) microtime() * 1000000);
            $i = 0;
            $pass = '';

            while ($i <= ($length - 1)) {
                $num = rand() % 33;
                $tmp = substr($chars, $num, 1);
                $pass = $pass . $tmp;
                $i++;
            }
            return $pass;
        }

        function SelectProject() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['project_code'] = $_GET['ProjectCode'];
        }
        
		?>

		<script type="text/javascript">
		    $(function(){
		       $(document).on('Click','input[type=text]',function(){this.select(); }); 
		    });
		</script>
		
		<script type="text/javascript">
            $(function(){
             $(".auto").autocomplete({
                 source: "code_project_autocomplete.php",
                 minLength: 1
             });   
            });
        </script>
		
		<script type="text/javascript">
			function SelectSubCat() {
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
						//alert(httpxml.responseText);
						var myarray = JSON.parse(httpxml.responseText);
						// Remove the options from 2nd dropdown list
						for ( j = document.SaveBudget.SubCategories.options.length - 1; j >= 0; j--) {
							document.SaveBudget.SubCategories.remove(j);
						}

						for ( i = 0; i < myarray.data.length; i++) {
							var optn = document.createElement("option");
							optn.text = myarray.data[i].SUB_CATG;
							optn.value = myarray.data[i].ID;
							// You can change this to subcategory
							document.SaveBudget.SubCategories.options.add(optn);

						}
					}
				}// end of function stateck

				var url = "../code/code_project_drop_down.php";
				var cat_id = document.getElementById('SelectBudgetCat').value;
				url = url + "?cat_id=" + cat_id;
				// url = url + "&sid=" + Math.random();
				httpxml.onreadystatechange = stateck;
				//alert(url);
				httpxml.open("GET", url, true);
				httpxml.send(null);
			}
		</script>
		
		<script type="text/javascript">
		function SelectExpSubCat() {
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
                        //alert(httpxml.responseText);
                        var myarray = JSON.parse(httpxml.responseText);
                        // Remove the options from 2nd dropdown list
                        for ( j = document.SaveExpense.ExpSubCategories.options.length - 1; j >= 0; j--) {
                            document.SaveExpense.ExpSubCategories.remove(j);
                        }

                        for ( i = 0; i < myarray.data.length; i++) {
                            var optn = document.createElement("option");
                            optn.text = myarray.data[i].SUB_CATG;
                            optn.value = myarray.data[i].ID;
                            // You can change this to subcategory
                            document.SaveExpense.ExpSubCategories.options.add(optn);

                        }
                    }
                }// end of function stateck

                var url = "../code/code_project_drop_down.php";
                var cat_id = document.getElementById('SelectExpenseCat').value;
                url = url + "?cat_id=" + cat_id;
                // url = url + "&sid=" + Math.random();
                httpxml.onreadystatechange = stateck;
                //alert(url);
                httpxml.open("GET", url, true);
                httpxml.send(null);
            }
		</script>

		<!-- @formatter:off -->
    <div class="ContentParent">
        <div class="Content">
            <div class="ContentTitle2"> Details, Related Information </div>
            <br>
            <br>
            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="inactive"> <a>&nbsp;</a> </li>
                    <li class="active"> <a data-toggle="tab" href="#Details">Details</a> </li>
                    <li> <a data-toggle="tab" href="#Clients">Clients</a> </li>
                    <li> <a data-toggle="tab" href="#Budget">Budget</a> </li>
                    <li> <a data-toggle="tab" href="#Expenses">Expenses</a> </li>
                    <li> <a data-toggle="tab" href="#Personnel">Personnel</a> </li>
                    <li> <a data-toggle="tab" href="#PAP">PAP Definition</a> </li>
                    <!-- li> <a data-toggle="tab" href="#Disputes">Disputes</a> </li -->
                    <li> <a data-toggle="tab" href="#Sections">Sections</a> </li>
                    <li class="inactive"> <a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> </li>
                    <li class="inactive"> <a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> </li>
                    <li class="inactive"> <a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> </li>
                </ul>
                <div class="tab-content"> 

                    <!-- @formatter:off this is the project details screen -->
                    <div id="Details" class="tab-pane fade in active">
                        <div id="FormArea" class="left-form">
                            <p> This is the Project Details Screen </p>
                            <!-- @formatter:off -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Details'; ?>" method="post" autocomplete="off">
                                <table class="formTable" cellpadding="10px">
                                    <tr>
                                        <td class="formLabel">Project Name</td>
                                        <td class="formLabel">Project Code</td>
                                    </tr>
                                    <tr>
                                        <td><span class="formSingleLineBox"> <input type="text" value="<?php
                                        if (isset($project_name)) {
                                            echo $project_name;
                                        }
                                                ?>" name="ProjectName"/> </span></td>
                                        <td><span class="formSingleLineBox"> <input type="text" value="<?php
                                        if (isset($project_name)) {
                                            echo $project_code;
                                        }
                                                ?>" name="ProjectCode"/> <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID'] ?>" /> <input type='hidden' name='post_id' value='".createPassword(64)."'> <input type="hidden" value="EditDetails" name="Mode"/> </span></td>
                                    </tr>
                                    <tr>
                                        <td class="formLabel">Start Date</td>
                                        <td class="formLabel">End Date</td>
                                    </tr>
                                    <tr>
                                        <td><span class="formSingleLineBox"> <input title="DD/MM/YYYY" type="text" id="start_date" 
                                            value="<?php if (isset($project_name)) { echo $start_date; } ?>" 
                                            placeholder="DD/MM/YYYY" name="StartDate" readonly /> </span></td>
                                        <td><span class="formSingleLineBox"> <input type="text" id="end_date" 
                                            value="<?php if (isset($project_name)) { echo $end_date; } ?>" 
                                            placeholder="DD/MM/YYYY" name="EndDate" readonly /> </span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="formLabel">Project Objective</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><span class="formMultiLineBox">
                                                <textarea type="text" id="end_date" placeholder="Enter Project Objective" name="ProjectObj"><?php
                                                if (isset($project_obj)) {
                                                    echo $project_obj;
                                                }
                                                ?></textarea>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="formLabel">Project Description / Summary</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><span class="formMultiLineBox">
                                                <textarea type="text" id="end_date"	placeholder="Project Description, Summary" name="ProjectDesc"><?php
                                                if (isset($project_name)) { echo $project_desc;
                                                }?></textarea>
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="saveButtonArea"> <input type="submit" value="Save" name="SaveDetails"/> 
                                                <!-- input type="submit" value="Delete" style="background-color: #FF0616;" / --> 
                                            </span></td>
                                        <td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
                                                class="formLinks"><a href="#">Photos</a></span></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <div id="SideSet" style="margin-top: 30px">
                            <form>
                                <fieldset class="fieldset" style="height: 525px; width: 300px;">
                                    <legend class="legend" style="width: 125px;"> <span class="legendText">Project Staff</span> </legend>
                                    This is the region with managers
                                </fieldset>
                            </form>
                        </div>
                    </div>

                    <!-- Client Area Tab Starts here -->
                    <div id="Clients" class="tab-pane fade">
                        <p> This is the Project's Clients and Stakeholders Screen </p>

                        <!-- Main Client Form -->
                        <div class="left-form" style="width: 650px;">
                            <!-- @formatter:off -->
                            <form action="<?php
                            if ($_GET['Mode'] == 'ViewClients') {
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
									
									<!-- table class="detailNavigation">
										<tr>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients';
                                            }
											?>" >Previous</a></td>
											<td class="PageJump" style="width: 70px;"> <?php echo $load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo $num_pages; ?></td>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients';
                                            }
											?>" >Next</a></td>
										</tr>
									</table -->
									
									<span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['prev_page'] . '#Clients'; } ?>" >Previous</a>
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['ClientPage'])) { echo $load_page . ' / ' . $num_pages ; } else {echo '1 / ' . $num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientPage=' . $GLOBALS['next_page'] . '#Clients'; } ?>" >Next</a>
                                    </span>
									
								</div>
							</div>

							<!-- Side Form for Client Staff -->
							<div
							style="width: 325px; float: left; margin-top: 10px; margin-left: 10px;">
								<!-- form -->
								<fieldset class="fieldset" style="padding: 20px; width: 350px;">
									<legend class="legend" style="width: 120px;">
										<span class="legendText">Client Staff:</span>
									</legend>

									<form action="<?php if (!isset($_GET['StaffID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertStaff&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '#Clients';
                                    } else if (isset($_GET['StaffID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateStaff&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '#Clients'; } ?>" method="POST" autocomplete="off" >
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

									<!-- table class="detailNavigation">
										<tr>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $_GET['ClientPage'] . '&StaffPage=' . $GLOBALS['staff_prev_page'] . '#Clients';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients'; }
											?>" >Previous</a></td>
											<td class="PageJump" style="width: 70px;"> <?php
                                            if (isset($GLOBALS['staff_load_page'])) {echo $GLOBALS['staff_load_page']; } else { echo 1; }
											?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php
                                            if (isset($GLOBALS['staff_num_pages'])) { echo $GLOBALS['staff_num_pages']; } else { echo 0; }
											?></td>
											<td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $_GET['ClientPage'] . '&StaffPage=' . $GLOBALS['staff_next_page'] . '#Clients';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients'; }
											?>" >Next</a></td>
										</tr>
									</table -->
									
									<span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $_GET['ClientPage'] . '&StaffPage=' . $GLOBALS['staff_prev_page'] . '#Clients'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients'; } ?>" >Previous</a>
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['ClientID'])) { echo $GLOBALS['staff_load_page'] . ' / ' . $GLOBALS['staff_num_pages'] ; } else { echo '1 / 0'; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewClients&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ClientID=' . $_GET['ClientID'] . '&ClientPage=' . $_GET['ClientPage'] . '&StaffPage=' . $GLOBALS['staff_next_page'] . '#Clients'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Clients'; } ?>" >Next</a>
                                    </span>
									
								</fieldset>
								<!-- /form -->
							</div>
						</div>

						<!-- Projects Budget Tab Starts here -->
						<div id="Budget" class="tab-pane fade">
							<p>
								This is the Project Budgets, and Financials Screen
							</p>
							<div class="left-form">
							    <form name="SaveBudget" action="<?php 
							    if($_GET['Mode'] == 'Read'){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Budget'; }
                                else if ($_GET['Mode'] == 'ViewBudget') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Budget'; } ?>" method="post" autocomplete="off">
								<table class="formTable" >
									<tr>
										<td class="formLabel">Category Line Total</td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width: 610px;">
										<input type="text" name="TotalBudget" 
										value="<?php if (isset($GLOBALS['budget_grand_total'])) {echo $GLOBALS['budget_grand_total']; } ?>"  
										placeholder="Auto Budget Total" readonly />
										<input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
										<input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
										<input type="hidden" name="BudgetID" value="<?php echo $_GET['BudgetID']; ?>" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Budget Category</td>
										<td class="formLabel">Item Sub Category</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
											<select name="Categories" id="SelectBudgetCat" onchange="SelectSubCat()">
											    <option value="">-- Select Category --</option>
												<?php if (isset($_GET['ProjectID']) || isset($_GET['Cat'])) { BindBudgetCat(); } ?>
											</select>
											</span></td>
										<td><span class="formSingleLineBox">
											<select name="SubCategories" id="SelectBudgetSubCat">
												<option value="">-- Select Sub Category --</option>
												<?php if(isset($_GET['Cat']) && isset($_GET['SubCat'])){ BindBudgetSubCat(); } ?>
											</select>
											</span></td>
									</tr>
									<tr>
										<td class="formLabel">Item Value</td>
										<td class="formLabel">Item (%) Of Category</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										<input type="text" name="BudgetValue" 
                                        value="<?php if (isset($GLOBALS['budget_item_amount'])) {echo $GLOBALS['budget_item_amount']; } ?>"  
                                        placeholder="Budget Value" />
										</span></td>
										<td><span class="formSingleLineBox">
										<input type="text" name="BudgetPercent" 
                                        value="<?php if (isset($GLOBALS['budget_item_pct'])) {echo $GLOBALS['budget_item_pct'] . ' %' ; } ?>"  
                                        placeholder="Budget Percentage" readonly />
										</span></td>
									</tr>
									<tr>
									    <td class="formLabel">Other Item Details</td>
									</tr>
									<tr>
									    <td colspan="2">
									   <span class="formMultiLineBox" style="width: 610px;">
									       <textarea type="text" name = "BudgetDetails" placeholder="Any Other Details"><?php 
									           if(isset($GLOBALS['budget_other_dtl'])){ echo $GLOBALS['budget_other_dtl']; } ?></textarea>
									       </span></td> 
									</tr>
									<tr>
										<td><span class="saveButtonArea">
										    <input type="submit" value="<?php if ($_GET['Mode'] == 'ViewBudget') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;"/>
                                            <?php $new_budget = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Budget';
                                                if ($_GET['Mode'] == 'ViewBudget') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_budget . '>New Budget</a></span>'; } ?>
										</span></td>
										<td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
										class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>
								</form>
							</div>
							
							<!-- Side Form for budget charts etc -->
							<div id="SideSet" style="margin-top: 10px">
								<form>
									<fieldset class="fieldset" style="height: 425px; width: 300px;">
										<legend class="legend" style="width: 170px;">
											<span class="legendText">Budget Snapshot</span>
										</legend>
										Other Budget Details
									</fieldset>
								</form>
							</div>
							
							<!-- detailed grid showing project expenses -->
							<div class="GridArea" style="width: 750px;">
								<table class="detailGrid" style="width:725px;">
									<tr>
										<td class="detailGridHead">#</td>
										<td class="detailGridHead">Budget Item</td>
										<td class="detailGridHead">Item Category</td>
										<td class="detailGridHead">Item Value</td>
										<td class="detailGridHead">Delete</td>
										<!-- td class="detailGridHead">Edit:</td -->
									</tr>
									<?php if (isset($_GET['ProjectID'])) { LoadBudgetItems(); } ?>
								</table>
								
								<!-- table class="detailNavigation">
                                        <tr>
                                            <td><a href="<?php
                                            if (isset($_GET['BudgetID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetID=' . $_GET['BudgetID'] . '&Cat=' . $_GET['Cat'] . '&SubCat=' . $_GET['SubCat'] . '&BudgetPage=' . $GLOBALS['budget_prev_page'] . '#Budget';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetPage=' . $GLOBALS['budget_prev_page'] . '#Budget'; }
                                            ?>" >Previous</a></td>
                                            <td class="PageJump" style="width: 70px;"> <?php echo $budget_load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo $budget_num_pages; ?></td>
                                            <td><a href="<?php
                                            if (isset($_GET['BudgetID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetID=' . $_GET['BudgetID'] . '&Cat=' . $_GET['Cat'] . '&SubCat=' . $_GET['SubCat'] . '&BudgetPage=' . $GLOBALS['budget_next_page'] . '#Budget';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetPage=' . $GLOBALS['budget_next_page'] . '#Budget'; }
                                            ?>" >Next</a></td>
                                        </tr>
                                </table -->
                                
                                <span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['BudgetID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetID=' . $_GET['BudgetID'] . '&Cat=' . $_GET['Cat'] . '&SubCat=' . $_GET['SubCat'] . '&BudgetPage=' . $GLOBALS['budget_prev_page'] . '#Budget'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetPage=' . $GLOBALS['budget_prev_page'] . '#Budget'; } ?>" >Previous</a>
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['BudgetPage'])) { echo $budget_load_page . ' / ' . $budget_num_pages ; } else {echo '1 / ' . $budget_num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['BudgetID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetID=' . $_GET['BudgetID'] . '&Cat=' . $_GET['Cat'] . '&SubCat=' . $_GET['SubCat'] . '&BudgetPage=' . $GLOBALS['budget_next_page'] . '#Budget'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetPage=' . $GLOBALS['budget_next_page'] . '#Budget'; } ?>" >Next</a>
                                </span>
                                
							</div>
						</div>

                        <!-- Projects Expenses Tab Starts here -->
						<div id="Expenses" class="tab-pane fade">
							<p>
								This is the projects expenses screen
							</p>
							<div class="left-form">
							    <form name="SaveExpense" action="<?php 
                                if($_GET['Mode'] == 'Read'){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Expenses'; }
                                else if ($_GET['Mode'] == 'ViewExpense') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdateExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Expenses'; } ?>" method="POST" autocomplete="off">
								<table class="formTable" >
								    <tr>
								        <td class="formLabel">Expense Name</td>
								    </tr>
								    <tr>
								        <td colspan="2">
								        <span class="formSingleLineBox" style="width: 610px;">
                                        <input type="text" name="ExpenseName" 
                                        value="<?php if (isset($GLOBALS['expense_item_name'])) {echo $GLOBALS['expense_item_name']; } ?>"
                                        placeholder="Enter Expense Name" style="width: 580px;" />
                                        <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                        <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                        <input type="hidden" name="ExpenseID" value="<?php echo $_GET['ExpenseID']; ?>" />
                                        </span></td> 
								    </tr>
									<tr>
										<td class="formLabel">Expense Category</td>
										<td class="formLabel">Expense Sub Category</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <select name="ExpCategories" id="SelectExpenseCat" onchange="SelectExpSubCat()">
                                                <option value="">-- Select Category --</option>
                                                <?php if (isset($_GET['ProjectID']) || isset($_GET['ExpCat'])) { BindBudgetCat(); } ?>
                                            </select>
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="ExpSubCategories" id="SelectExpenseSubCat">
                                                <option value="">-- Select Sub Category --</option>
                                                <?php if(isset($_GET['ExpCat']) && isset($_GET['ExpSubCat'])){ BindBudgetSubCat(); } ?>
                                                </select>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Value Of Expense</td>
										<td class="formLabel">Expense Date</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <input type="text" name="ExpenseAmount" 
										    value="<?php if (isset($GLOBALS['expense_item_amount'])) {echo $GLOBALS['expense_item_amount']; } ?>" 
										    placeholder="Enter Expense Amount" />
										</span></td>
										<td><span class="formSingleLineBox">
										    <input title="DD/MM/YYYY" type="text" id="expense_date" 
                                            value="<?php if (isset($GLOBALS['expense_date'])) {echo $GLOBALS['expense_date']; } ?>" 
                                            placeholder="DD/MM/YYYY" name="ExpenseDate" readonly />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Other Details</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formMultiLineBox" style="width: 610px;">
										    <textarea type="text" name = "ExpenseDetails" placeholder="Any Other Details"><?php
										     if (isset($GLOBALS['expense_other_dtl'])) {echo $GLOBALS['expense_other_dtl']; } ?></textarea>
										    </span></td>
									</tr>
									<tr>
                                        <td><span class="saveButtonArea">
                                            <input type="submit" value="<?php if ($_GET['Mode'] == 'ViewExpense') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;"/>
                                            <?php $new_expense = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Expenses';
                                                if ($_GET['Mode'] == 'ViewExpense') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_expense . '>New Expense</a></span>'; } ?>
                                        </span></td>
                                        <td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
                                        class="formLinks"><a href="#">Photos</a></span></td>
                                    </tr>
								</table>
								</form>
							</div>
							
							<!-- Side Form for budget charts etc -->
                            <div id="SideSet" style="margin-top: 10px">
                                <form>
                                    <fieldset class="fieldset" style="height: 425px; width: 300px;">
                                        <legend class="legend" style="width: 170px;">
                                            <span class="legendText">Budget Snapshot</span>
                                        </legend>
                                        Other Budget Details
                                    </fieldset>
                                </form>
                            </div>
							
							<div class="GridArea" style="width: 750px;">
								<table class="detailGrid" style="width: 725px;">
									<tr>
										<td class="detailGridHead">#</td>
										<td class="detailGridHead">Expense</td>
										<td class="detailGridHead">Budget Sub Category</td>
										<td class="detailGridHead">Budget Category</td>
										<td class="detailGridHead">Expense Amount</td>
										<td class="detailGridHead">Delete</td>
										<!-- td class="detailGridHead">Expense (%)</td -->
									</tr>
									<?php if (isset($_GET['ProjectID'])) { LoadExpenses(); } ?>
								</table>
								
								<!-- table class="detailNavigation">
									<tr>
                                            <td><a href="<?php
                                            if (isset($_GET['ExpenseID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $_GET['ExpenseID'] . '&ExpCat=' . $_GET['ExpCat'] . '&ExpSubCat=' . $_GET['ExpSubCat'] . '&ExpensePage=' . $GLOBALS['expense_prev_page'] . '#Expenses';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpensePage=' . $GLOBALS['expense_prev_page'] . '#Expenses'; }
                                            ?>" >Previous</a></td>
                                            <td class="PageJump" style="width: 70px;"> <?php echo $expense_load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo $expense_num_pages; ?></td>
                                            <td><a href="<?php
                                            if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $_GET['ExpenseID'] . '&ExpCat=' . $_GET['ExpCat'] . '&ExpSubCat=' . $_GET['ExpSubCat'] . '&ExpensePage=' . $GLOBALS['expense_next_page'] . '#Expenses';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpensePage=' . $GLOBALS['expense_next_page'] . '#Expenses'; }
                                            ?>" >Next</a></td>
                                    </tr>
								</table -->
								
								<span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['ExpenseID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $_GET['ExpenseID'] . '&ExpCat=' . $_GET['ExpCat'] . '&ExpSubCat=' . $_GET['ExpSubCat'] . '&ExpensePage=' . $GLOBALS['expense_prev_page'] . '#Expenses'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpensePage=' . $GLOBALS['expense_prev_page'] . '#Expenses'; } ?>" >Previous</a>
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['ExpensePage'])) { echo $expense_load_page . ' / ' . $expense_num_pages ; } else {echo '1 / ' . $expense_num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['ClientID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $_GET['ExpenseID'] . '&ExpCat=' . $_GET['ExpCat'] . '&ExpSubCat=' . $_GET['ExpSubCat'] . '&ExpensePage=' . $GLOBALS['expense_next_page'] . '#Expenses'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpensePage=' . $GLOBALS['expense_next_page'] . '#Expenses'; } ?>" >Next</a>
                                </span>
								
							</div>
						</div>

						<!-- Projects Personnel Tab Starts here -->
						<div id="Personnel" class="tab-pane fade">
							<p>
								This is the Project Personnel Screen
							</p>
							<div class="left-form">
							    <form name="SavePersonnel" action="<?php 
                                if($_GET['Mode'] == 'Read'){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertPersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Personnel'; }
                                else if ($_GET['Mode'] == 'ViewPersonnel') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdatePersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Personnel'; } ?>" method="POST" autocomplete="off">
								<table class="formTable">
								    <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                        <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
                                        <input type="hidden" name="ID" value="<?php echo $_GET['ID']; ?>" />
									<tr>
										<td class="formLabel">Select User</td>
										<td class="formLabel">User Role</td>
									</tr>
									<tr>
										<td>
										    <span class="formSingleLineBox">
										        <select name="UserID" onchange="">
                                                    <option value="">-- Select User --</option>
                                                        <?php if (isset($_GET['ProjectID']) ) { BindAllUsers(); } ?>
                                                </select>
										    </span></td>
										<td>
										    <span class="formSingleLineBox">
										        <select name="RoleID" id="SelectRole" onchange="">
                                                    <option value="">-- Select Role --</option>
                                                        <?php if (isset($_GET['ProjectID']) ) { BindAllRoles(); } ?>
                                                </select>
											</span></td>
									</tr>
									<tr>
									    <td class="formLabel">Other User Details</td>
									</tr>
									<tr>
									    <td colspan="2"><span class="formMultiLineBox" style="width: 610px;">
                                            <textarea type="text" name = "OtherDetail" placeholder="Any Other Details"><?php if(isset($_GET['UserID'])){ echo $GLOBALS['personnel_other_dtl']; } ?></textarea>
                                            </span></td>
									</tr>
									<tr>
										<td><span class="saveButtonArea">
                                            <input type="submit" value="<?php if ($_GET['Mode'] == 'ViewPersonnel') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;"/>
                                            <?php $new_expense = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Personnel';
                                                if ($_GET['Mode'] == 'ViewPersonnel') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_expense . '>New Staff</a></span>'; } ?>
                                        </span></td>
                                        <td align="right"><span class="formLinks SideBar"><a href="#">Documents</a></span><span
                                        class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>
								</form>
							</div>
							
							<div class="GridArea">
								<table class="detailGrid" style="width: 700px;">
									<tr>
										<td class="detailGridHead">#</td>
										<td class="detailGridHead">Username</td>
										<td class="detailGridHead">Display Name</td>
										<!-- td class="detailGridHead">Job Title</td -->
										<td class="detailGridHead">System Role</td>
										<td class="detailGridHead">Delete</td>
									</tr>
									<?php if (isset($_GET['ProjectID'])) { LoadProjectStaff(); } ?>
								</table>
								
								<!-- table class="detailNavigation">
									<tr>
										<td><a href="<?php
                                            if (isset($_GET['UserID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewPersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&UserID=' . $_GET['UserID'] . '&RoleID=' . $_GET['RoleID'] . '&UserPage=' . $GLOBALS['personnel_prev_page'] . '#Personnel';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&StaffPage=' . $GLOBALS['personnel_prev_page'] . '#Personnel'; }
                                            ?>" >Previous</a></td>
                                            <td class="PageJump" style="width: 70px;"> <?php echo $personnel_load_page; ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo $personnel_num_pages; ?></td>
                                            <td><a href="<?php
                                            if (isset($_GET['UserID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewPersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&UserID=' . $_GET['UserID'] . '&RoleID=' . $_GET['RoleID'] . '&UserPage=' . $GLOBALS['personnel_next_page'] . '#Personnel';
                                            } else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&StaffPage=' . $GLOBALS['personnel_next_page'] . '#Personnel'; }
                                            ?>" >Next</a></td>
									</tr>
								</table -->
								
								<span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['UserID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewPersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&UserID=' . $_GET['UserID'] . '&RoleID=' . $_GET['RoleID'] . '&StaffPage=' . $GLOBALS['personnel_prev_page'] . '#Personnel'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&StaffPage=' . $GLOBALS['personnel_prev_page'] . '#Personnel'; } ?>" >Previous</a>
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['StaffPage'])) { echo $personnel_load_page . ' / ' . $personnel_num_pages ; } else {echo '1 / ' . $personnel_num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['UserID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewPersonnel&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&UserID=' . $_GET['UserID'] . '&RoleID=' . $_GET['RoleID'] . '&StaffPage=' . $GLOBALS['personnel_next_page'] . '#Personnel'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&StaffPage=' . $GLOBALS['personnel_next_page'] . '#Personnel'; } ?>" >Next</a>
                                </span>
								
							</div>
						</div>

						<!-- Projects PAP Definition Starts here -->
						<div id="PAP" class="tab-pane fade">
							<p>
								This is the PAP Definition Screen
							</p>
							<div class="left-form">
							    <form name="SavePersonnel" action="<?php 
                                if($_GET['Mode'] == 'Read'){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP'; }
                                else if ($_GET['Mode'] == 'ViewPap') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdatePap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP'; } ?>" method="POST" autocomplete="off">
								<table class="formTable">
						             <input type="hidden" name="ProjectID" value="<?php echo $_GET['ProjectID']; ?>" />
                                     <input type="hidden" name="ProjectCode" value="<?php echo $_GET['ProjectCode']; ?>" />
									<tr>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td colspan="2" class="formLabel">PAP Name</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox" style="width: 610px;" >
										    <input type="text" name="PapName" value="<?php if (isset($GLOBALS['pap_name'])){ echo $GLOBALS['pap_name']; } ?>" placeholder="Enter PAP Name" style="width: 580px;" />
										    </span></td>
									</tr>
									<tr>
										<td class="formLabel">PAP HHID</td>
										<td class="formLabel">Land Plot Reference</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <input type="text" name="HHID" value="<?php if (isset($_GET['HHID'])){ echo $_GET['HHID']; } ?>" placeholder="PAP HHID" readonly="true" />
										</span></td>
										<td><span class="formSingleLineBox">
										    <input type="text" name="PlotRef" value="<?php if (isset($GLOBALS['pap_plot_ref'])){ echo $GLOBALS['pap_plot_ref']; } ?>" placeholder="Enter PAP Plot Ref" />
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">PAP Designation</td>
										<td class="formLabel">PAP Type</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <input type="text" name="PapDesign" value="<?php if (isset($GLOBALS['pap_design'])){ echo $GLOBALS['pap_design']; } ?>" placeholder="Enter PAP Designation" />
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="PapType" onchange="">
                                                    <option value="">-- Select PAP Type --</option>
                                                    <option value="IND" <?php if (isset($GLOBALS['pap_type']) && $GLOBALS['pap_type'] == 'IND') { echo 'selected'; }  ?> >Individual</option>
                                                    <option value="INS" <?php if (isset($GLOBALS['pap_type']) && $GLOBALS['pap_type'] == 'INS') { echo 'selected'; }  ?>>Institution</option> 
                                            </select>
										</span></td>
									</tr>
									<tr>
										<td><span class="saveButtonArea">
                                            <input type="submit" value="<?php if ($_GET['Mode'] == 'ViewPap') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;"/>
                                            <?php $new_pap_defn = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP';
                                                if ($_GET['Mode'] == 'ViewPap') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_pap_defn . '>New PAP</a></span>'; } ?>
                                        </span></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span
										class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>
								</form>
							</div>
							
							<div class="GridArea">
								<table class="detailGrid" style="width: 800px;">
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
								
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '#PAP'; ?>" method="GET" autocomplete="off" >
                                    <input type="hidden" name="Mode" value="<?php if ($_GET['Mode'] == 'SearchPap') { echo 'SearchPap'; } else { echo 'Read'; } ?>" />
                                    <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                                    <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
                                    <input type="hidden" name="KeyWord" value="<?php if (isset($_GET['KeyWord'])) {echo $_GET['KeyWord']; } else {echo ''; } ?>" />
								    <span style="white-space: nowrap; float:left;">
								        <a href="<?php if (isset($_GET['PapID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PapID=' . $_GET['HHID'] . '&GridPage=' . $GLOBALS['pap_prev_page'] . '#PAP'; }
                                        else if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_prev_page'] . '#PAP'; } 
								        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_prev_page'] . '#PAP'; } ?>" >Previous</a>
								        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $_GET['GridPage'] . ' / ' . $GLOBALS['pap_num_pages'] ; } else {echo '1 / ' . $GLOBALS['pap_num_pages'] ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
							            <a href="<?php if (isset($_GET['PapID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PapID=' . $_GET['HHID'] . '&GridPage=' . $GLOBALS['pap_next_page'] . '#PAP'; }
                                        else if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_next_page'] . '#PAP'; } 
							            else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_next_page'] . '#PAP'; } ?>" >Next</a>
                                    </span>
								    <input type="submit" style="position: absolute; left: -99999px;" />
                                </form>
                                
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '#PAP'; ?>" method="GET" autocomplete="off" >
                                    <input type="hidden" name="Mode" value="<?php echo 'SearchPap'; ?>" />
                                    <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                                    <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
                                    <span style="white-space: nowrap; margin-left: 50px; font-weight: bold; color: #ff6600;">
                                        Search:&nbsp;&nbsp;<input name="KeyWord" type="text" value="<?php if (isset($_GET['KeyWord'])) { echo $_GET['KeyWord'];  } ?>" style="width: 200px; margin-right: 0px; border: 1px solid #337ab7;" placeholder="Search For PAP" />&nbsp;&nbsp;
                                        <a href="<?php if ($_GET['Mode'] == 'SearchPap') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP'; } else { echo '#'; } ?>" >Reset</a>    
                                    </span>
                                    <input type="submit" style="position: absolute; left: -99999px;" />
                                </form>
                                
							</div>
						</div>

						<!-- Projects Disputes Starts here -->
						<div id="Disputes" class="tab-pane fade">
							<p>
								This is the Project Disputes Screen
							</p>
							<div class="left-form">
							    
								<table class="formTable">
								    <tr>
                                        <td class="formLabel" style="font-weight: bold; color: #ff6600;" >
                                            Search PAP:&nbsp;&nbsp;&nbsp;  
                                        </td>
                                        <td class="formLabel">PAP Name:</td>
                                    </tr>
                                    <tr>
                                       <td>
                                            <span class="formSingleLineBox" style="color: #ff6600;" >
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '#Disputes'; ?>" method="GET" autocomplete="off" >
                                                    <input type="hidden" name="Mode" value="<?php echo 'SearchPap'; ?>" />
                                                    <input type="hidden" name="ProjectID" value="<?php if (isset($_GET['ProjectID'])) {echo $_GET['ProjectID']; } else {echo ''; } ?>" />
                                                    <input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
                                                    <input name="KeyWord" type="text" value="<?php if (isset($_GET['KeyWord'])) { echo $_GET['KeyWord'];  } ?>" placeholder="Search For PAP By Name" style="width:200px;" />
                                                        <!-- a href="<?php if ($_GET['Mode'] == 'SearchPap') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP'; } else { echo '#'; } ?>" >Reset</a -->    
                                                    <input type="submit" style="position: absolute; left: -99999px;" />
                                                    <a class="LinkInBox" href="<?php if ($_GET['Mode'] == 'SearchPap') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Disputes'; } else { echo '#'; } ?>" >Clear</a>
                                                </form> 
                                            </span>
                                        </td>
                                        
                                        <!-- form name="SavePersonnel" action="<?php 
                                        if($_GET['Mode'] == 'Read'){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=InsertPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP'; }
                                        else if ($_GET['Mode'] == 'ViewPap') { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=UpdatePap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#PAP'; } ?>" method="POST" autocomplete="off" -->
                                        
                                        <td>
                                            <span class="formSingleLineBox" >
                                                <select name="HHID" onchange="" >
                                                    <option value="">-- Select PAP --</option>
                                                        <?php if ($_GET['Mode'] == 'SearchPap') { SearchDisputePaps(); } else { BindAllProjectPaps(); } ?>
                                                </select>
                                                
                                                <!-- form action='' method='post'>
                                                <input type="text" name="country" value="<?php // echo 'SearchPap'; ?>" class="auto" placeholder="Enter PAP Name" />
                                                </form -->
                                            </span>
                                        </td>
                                        
                                    </tr>
									<tr>
										<td class="formLabel">Dispute Type</td>
										<td class="formLabel">Dispute Status</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <select name="DispID" onchange="">
                                                    <option value="">-- Select Dispute Type --</option>
                                                        <?php if (isset($_GET['ProjectID']) ) { BindDisputeCategory(); } ?>
                                            </select>
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="DispStatus" onchange="">
                                                    <option value="">-- Select Dispute Status --</option>
                                                    <option value='Pending' <?php if (isset($GLOBALS['proj_disp_status']) && $GLOBALS['proj_disp_status'] == 'Pending' ) { echo 'selected'; }  ?> >Pending</option>
                                                    <option value='Resolved' <?php if (isset($GLOBALS['proj_disp_status']) && $GLOBALS['proj_disp_status'] == 'Resolved' ) { echo 'selected'; }  ?> >Resolved</option> 
                                            </select>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Date Logged</td>
										<td class="formLabel">Logged By</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">
										    <input title="DD/MM/YYYY" type="text" id="dispute_date" 
                                            value="<?php if (isset($GLOBALS['proj_disp_date_log'])) { echo $GLOBALS['proj_disp_date_log']; } ?>" 
                                            placeholder="DD/MM/YYYY" name="DisputeDate" readonly />
										</span></td>
										<td><span class="formSingleLineBox">
										    <select name="UserID" onchange="">
                                                    <option value="">-- Select User --</option>
                                                        <?php if (isset($_GET['ProjectID']) ) { BindAllUsers(); } ?>
                                            </select>
										</span></td>
									</tr>
									<tr>
										<td class="formLabel">Dispute Details</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formMultiLineBox">
										    <textarea type="text" name = "DisputeDetail" placeholder="Any Other Details"><?php if(isset($GLOBALS['proj_disp_other_dtl'])){ echo $GLOBALS['proj_disp_other_dtl']; } ?></textarea>
										</span></td>
									</tr>
									<tr>
										<td><span class="saveButtonArea" href="#">
										    <input type="submit" value="<?php if ($_GET['Mode'] == 'ViewDispute') {echo 'Update'; } else {echo 'Save'; } ?>" name="UpdateMode" style="float:left;"/>
                                            <?php $new_dispute = htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Disputes';
                                                if ($_GET['Mode'] == 'ViewDispute') { echo '<span class="formLinks" style="margin-top:0px;"><a href=' . $new_dispute . '>New Dispute</a></span>'; } ?>
										</span></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span
										class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>
								<!-- /form -->
							</div>
							<div class="GridArea">
								<table class="detailGrid" style="width: 800px;">
									<tr>
										<td class="detailGridHead">#</td>
										<td class="detailGridHead">PAP Name</td>
										<td class="detailGridHead">Dispute Category</td>
										<td class="detailGridHead">Date Log</td>
										<td class="detailGridHead">Edit:</td>
									</tr>
									<?php if (isset($_GET['ProjectID'])) { LoadProjectDisputes(); } ?>
								</table>
								
								
								<span style="white-space: nowrap;">
                                        <a href="<?php if (isset($_GET['DispID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewDispute&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&DispID=' . $_GET['DispID'] . '&UserID=' . $_GET['UserID'] . '&DispStatus=' . $_GET['DispStatus'] . '&GridPage=' . $GLOBALS['proj_disp_prev_page'] . '#Disputes'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['proj_disp_prev_page'] . '#Disputes'; } ?>" >Previous</a>
                                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $proj_disp_load_page . ' / ' . $proj_disp_num_pages ; } else {echo '1 / ' . $proj_disp_num_pages ; } ?>" style="width: 60px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                                        <a href="<?php if (isset($_GET['DispID'])) { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=ViewDispute&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&DispID=' . $_GET['DispID'] . '&UserID=' . $_GET['UserID'] . '&DispStatus=' . $_GET['DispStatus'] . '&GridPage=' . $GLOBALS['proj_disp_next_page'] . '#Disputes'; } 
                                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['proj_disp_prev_page'] . '#Disputes'; } ?>" >Next</a>
                                </span>
                                
							</div>
						</div>

						<!-- Projects Sections Starts here -->
						<div id="Sections" class="tab-pane fade">
							<p>
								This is the Project Sections Screen
							</p>
							<div class="left-form">
								<table>
									<tr>
										<td class="formLabel">Section Name:</td>
									</tr>
									<tr>
										<td colspan="2"><span class="formSingleLineBox"
										style="width: 610px;">Enter Section Name</span></td>
									</tr>
									<tr>
										<td class="formLabel">Estimated Budget:</td>
										<td class="formLabel">Section Length:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Estimated Budget</span></td>
										<td><span class="formSingleLineBox">Section Length</span></td>
									</tr>
									<tr>
										<td class="formLabel">Est Start Date:</td>
										<td class="formLabel">Est End Date:</td>
									</tr>
									<tr>
										<td><span class="formSingleLineBox">Start Date</span></td>
										<td><span class="formSingleLineBox">End Date</span></td>
									</tr>
									<tr>
										<td class="formLabel">Other Details:</td>
									</tr>
									<tr>
										<td colspan="2" style="width: 610px;"><span
										class="formMultiLineBox">Enter any other details</span></td>
									</tr>
									<tr>
										<td><span class="saveButtonArea">Save</span></td>
										<td><span class="formLinks SideBar"><a href="#">Documents</a></span><span
										class="formLinks"><a href="#">Photos</a></span></td>
									</tr>
								</table>
								<div class="GridArea">
									<table class="detailGrid" style="width: 800px;">
										<tr>
											<td class="detailGridHead">#</td>
											<td class="detailGridHead">Dispute Type</td>
											<td class="detailGridHead">Date Logged</td>
											<td class="detailGridHead">Logged By</td>
											<td class="detailGridHead">Status</td>
											<td class="detailGridHead">Nullify</td>
											<td class="detailGridHead">Edit:</td>
										</tr>
										<tr>
											<td>1</td>
											<td>Legal</td>
											<td>23/11/2015</td>
											<td>Edwin Baguma</td>
											<td>Pending</td>
											<td>
											<input type="checkbox">
											</td>
											<td><a href="#"><img src="images/Edit.png" alt=""
											class="EditDeleteButtons" /></a><a href="#"><img
											src="images/delete.png" alt="" class="EditDeleteButtons" /></a></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Valuation</td>
											<td>20/10/2015</td>
											<td>Joyce Mbangu</td>
											<td>Pending</td>
											<td>
											<input type="checkbox">
											</td>
											<td><a href="#"><img src="images/Edit.png" alt=""
											class="EditDeleteButtons" /></a><a href="#"><img
											src="images/delete.png" alt="" class="EditDeleteButtons" /></a></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Compensation</td>
											<td>25/09/2015</td>
											<td>Eric Ssembuusu</td>
											<td>Pending</td>
											<td>
											<input type="checkbox">
											</td>
											<td><a href="#"><img src="images/Edit.png" alt=""
											class="EditDeleteButtons" /></a><a href="#"><img
											src="images/delete.png" alt="" class="EditDeleteButtons" /></a></td>
										</tr>
										<tr>
											<td>4</td>
											<td>IGG Inquest</td>
											<td>19/12/2013</td>
											<td>Ofwono Opondo</td>
											<td>Pending</td>
											<td>
											<input type="checkbox">
											</td>
											<td><a href="#"><img src="images/Edit.png" alt=""
											class="EditDeleteButtons" /></a><a href="#"><img
											src="images/delete.png" alt="" class="EditDeleteButtons" /></a></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Internal Audit</td>
											<td>15/11/2014</td>
											<td>Edwin Baguma</td>
											<td>Pending</td>
											<td>
											<input type="checkbox">
											</td>
											<td><a href="#"><img src="images/Edit.png" alt=""
											class="EditDeleteButtons" /></a><a href="#"><img
											src="images/delete.png" alt="" class="EditDeleteButtons" /></a></td>
										</tr>
									</table>
									<table class="detailNavigation">
										<tr>
											<td><a href="#">Previous</a></td>
											<td class="PageJump">1 / 5</td>
											<td><a href="#">Next</a></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--#include virtual="Footer.html" -->
		<?php
        include ('ui_footer.php');
		?>
		<script src="js/date_picker/pikaday.js"></script>
		<script>
			var picker = new Pikaday({
				field : document.getElementById('start_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1980, 0, 1),
				maxDate : new Date(2050, 12, 31),
				yearRange : [1980, 2050]
			});

			var picker = new Pikaday({
				field : document.getElementById('end_date'),
				format : 'DD/MM/YYYY',
				firstDay : 1,
				minDate : new Date(1980, 0, 1),
				maxDate : new Date(2050, 12, 31),
				yearRange : [1980, 2050]
			});
			
			var picker = new Pikaday({
                field : document.getElementById('expense_date'),
                format : 'DD/MM/YYYY',
                firstDay : 1,
                minDate : new Date(1980, 0, 1),
                maxDate : new Date(2050, 12, 31),
                yearRange : [1980, 2050]
            });
            
            var picker = new Pikaday({
                field : document.getElementById('dispute_date'),
                format : 'DD/MM/YYYY',
                firstDay : 1,
                minDate : new Date(1980, 0, 1),
                maxDate : new Date(2050, 12, 31),
                yearRange : [1980, 2050]
            });

		</script>
		</body>
</html>