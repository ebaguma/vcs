    <?php
        
        function LoadBioOccupation() {
            include_once ('../code/code_masters_Bio.php');
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
                echo '<script>alert("Page Is Out Of Range");</script>';
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
                echo '<script>alert("Page Is Out Of Range");</script>';
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
