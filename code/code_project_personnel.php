<?php

Class ProjectStaff {

    //General parameters
    public $selected_project_id;
    public $selected_project_code;

    //read Expense Page Parameters
    public $personnel_page_rows = 5;
    public $personnel_last_page;
    public $personnel_data_offset;
    public $personnel_record_num;
    public $personnel_load_page;

    public $personnel_num_rows;

    //Expense Details
    public $personnel_item_id;
    public $personnel_item_name;
    public $personnel_item_catg;
    public $personnel_item_sub_catg;
    public $personnel_item_amount;
    public $personnel_grand_total;
    public $personnel_item_pct;
    public $personnel_date;
    public $personnel_other_dtl;

    // Sub Category Selection
    public $personnel_category_id;

    function LoadProjectStaff() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_STAFF_LIMIT (@PROJ_ID_, @OFFSET_, @LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        // $mysqli -> query("SET @SUB_CAT_ID_ = " . $this -> expense_item_sub_catg);
        $mysqli -> query("SET @OFFSET_ = " . $this -> personnel_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> personnel_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $USER_ID = $row -> USER_ID;
            $PROJ_ID = $row -> PROJ_ID;
            $USER_NAME = $row -> USER_NAME;
            $DISP_NAME = $row -> DISP_NAME;
            $ROLE_ID = $row -> ROLE_ID;
            $ROLE = $row -> ROLE;
            // $TOTAL_AMT = $row -> TOTAL_AMT;
            $OTHER_DTL = $row -> OTHER_DTL;
            $personnel_page = 1;

            $ACTION = '../ui/ui_project_personnel.php?Mode=ViewExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&StaffID=' . $ID . '#Personnel';
            /* $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $ID . '#Expenses';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>'; */

            $this -> personnel_record_num = $this -> personnel_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td></tr>", $this -> personnel_record_num, $ACTION, $USER_NAME, $DISP_NAME, $ROLE);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectProjectStaff() {

    }

    function BindAllUsers() {

    }

    function BindAllRoles() {

    }

    function ProjStaffPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJ_STAFF_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> personnel_num_rows = $result -> num_rows;
        $this -> personnel_last_page = ceil($this -> personnel_num_rows / $this -> personnel_page_rows);
    }

}
?>