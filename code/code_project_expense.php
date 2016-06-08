<?php

class ProjectExpense {

    //General parameters
    public $selected_project_id;
    public $selected_project_code;

    //read Expense Page Parameters
    public $expense_page_rows = 5;
    public $expense_last_page;
    public $expense_data_offset;
    public $expense_record_num;
    public $expense_load_page;

    public $expense_num_rows;

    //Expense Details
    public $expense_item_id;
    public $expense_item_name;
    public $expense_item_catg;
    public $expense_item_sub_catg;
    public $expense_item_amount;
    public $budget_grand_total;
    public $expense_item_pct;
    public $expense_date;
    public $expense_other_dtl;

    // Sub Category Selection
    public $expense_category_id;

    function LoadExpenses() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_EXPENSE_LIMIT (@PROJ_ID_,@OFFSET_,@LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        // $mysqli -> query("SET @SUB_CAT_ID_ = " . $this -> expense_item_sub_catg);
        $mysqli -> query("SET @OFFSET_ = " . $this -> expense_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> expense_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJ_ID = $row -> PROJ_ID;
            $EXP_NAME = $row -> EXP_NAME;
            $SUB_CATG_ID = $row -> SUB_CATG_ID;
            $SUB_CATG = $row -> SUB_CATG;
            $CATG_ID = $row -> CATG_ID;
            $CATG = $row -> CATG;
            // $TOTAL_AMT = $row -> TOTAL_AMT;
            $EXP_AMT = $row -> EXP_AMT;
            // $ITEM_PCTG = $row -> ITEM_PCTG;
            $EXP_DATE = $row -> EXP_DATE;
            $OTHER_DTL = $row -> OTHER_DTL;
            $expense_page = 1;

            $ACTION = '../ui/ui_project_detail.php?Mode=ViewExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $ID . '&ExpCat=' . $CATG_ID . '&ExpSubCat=' . $SUB_CATG_ID . '#Expenses';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteExpense&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&ExpenseID=' . $ID . '#Expenses';
            $DEL_ACTION = '<a href="' . $DEL_URL . '"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> expense_record_num = $this -> expense_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> expense_record_num, $ACTION, $EXP_NAME, $SUB_CATG, $CATG, $EXP_AMT, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectExpense() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_EXPENSE (@ID_, @PROJ_ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> expense_item_id);
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> expense_item_id = $row -> ID;
            $this -> selected_project_id = $row -> PROJ_ID;
            $this -> expense_item_catg = $row -> CATG_ID;
            $this -> expense_item_sub_catg = $row -> SUB_CATG_ID;
            $this -> expense_item_name = $row -> EXP_NAME;
            // $this -> expense_grand_total = $row -> TOTAL_AMT;
            $this -> expense_item_amount = $row -> EXP_AMT;
            $this -> expense_date = $row -> EXP_DATE;
            // $this -> expense_item_pct = $row -> ITEM_PCTG;
            $this -> expense_other_dtl = $row -> OTHER_DTL;
        }
    }

    function UpdateXpense() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PROJ_EXPENSE(@ID_, @EXP_NAME_, @SUB_CATG_ID_, @EXP_AMT_, @EXP_DATE_, @OTHER_DTL_)";

        $mysqli -> query("SET @ID_ = " . $this -> expense_item_id);
        $mysqli -> query("SET @EXP_NAME_ = " . "'" . $this -> expense_item_name . "'");
        $mysqli -> query("SET @SUB_CATG_ID_ = " . $this -> expense_item_sub_catg);
        $expense_amount = (int)str_replace(',', '', $this -> expense_item_amount);
        $mysqli -> query("SET @EXP_AMT_ = " . $expense_amount);
        $mysqli -> query("SET @EXP_DATE_ = '" . DateTime::createFromFormat('d/m/Y', $this -> expense_date) -> format('Y-m-d') . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> expense_other_dtl . "'");

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Unsuccessful !");</script>';
        }
    }

    function InsertExpense() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_PROJ_EXPENSE(@PROJ_ID_, @EXP_NAME_, @SUB_CATG_ID_, @EXP_AMT_, @EXP_DATE_, @OTHER_DTL_)";

        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @EXP_NAME_ = " . "'" . $this -> expense_item_name . "'");
        $mysqli -> query("SET @SUB_CATG_ID_ = " . $this -> expense_item_sub_catg);
        $expense_amount = (int)str_replace(',', '', $this -> expense_item_amount);
        $mysqli -> query("SET @EXP_AMT_ = " . $expense_amount);
        $mysqli -> query("SET @EXP_DATE_ = '" . DateTime::createFromFormat('d/m/Y', $this -> expense_date) -> format('Y-m-d') . "'");
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> expense_other_dtl . "'");

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Insert Unsuccessful !");</script>';
        }
    }

    function DeleteXpense() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_PROJ_EXPENSE(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> expense_item_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Budget Still In Use )");</script>';
        }
    }

    function ExpensePageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJ_EXPENSE_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> expense_num_rows = $result -> num_rows;
        $this -> expense_last_page = ceil($this -> expense_num_rows / $this -> expense_page_rows);
    }

}
?>