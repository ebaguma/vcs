<?php
class ProjectBudget {
    //general parameters
    public $selected_project_id;
    public $selected_project_code;

    //read Budget Page Parameters
    public $budget_page_rows = 5;
    public $budget_last_page;
    public $budget_data_offset;
    public $budget_record_num;
    public $budget_load_page;

    public $budget_num_rows;

    //Budget Details
    public $budget_item_id;
    public $budget_item_catg;
    public $budget_item_sub_catg;
    public $budget_item_amount;
    public $budget_grand_total;
    public $budget_item_pct;
    public $budget_other_dtl;

    // Sub Category Selection
    public $budget_category_id;

    function LoadBudgetItems() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_BUDGET_LIMIT (@PROJ_ID_,@OFFSET_,@LIMIT_)";
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @OFFSET_ = " . $this -> budget_data_offset);
        $mysqli -> query("SET @LIMIT_ = " . $this -> budget_page_rows);

        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $PROJ_ID = $row -> PROJ_ID;
            $CATG_ID = $row -> CATG_ID;
            $CATG = $row -> CATG;
            $SUB_CATG_ID = $row -> SUB_CATG_ID;
            $SUB_CATG = $row -> SUB_CATG;
            $ITEM_AMT = $row -> ITEM_AMT;
            $OTHER_DTL = $row -> OTHER_DTL;
            $budget_page = 1;

            $ACTION = '../ui/ui_project_detail.php?Mode=ViewBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetID=' . $ID . '&Cat=' . $CATG_ID . '&SubCat=' . $SUB_CATG_ID . '#Budget';
            $DEL_URL = '../ui/ui_project_detail.php?Mode=DeleteBudget&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&BudgetID=' . $ID . '#Budget';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Budget Item?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';

            $this -> budget_record_num = $this -> budget_record_num + 1;
            printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> budget_record_num, $ACTION, $SUB_CATG, $CATG, $ITEM_AMT, $DEL_ACTION);
        }
        //recuperate resources
        $result -> free();
    }

    function SelectBudgetItem() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_BUDGET (@ID_, @PROJ_ID_)";
        $mysqli -> query("SET @ID_ = " . $this -> budget_item_id);
        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $results = $mysqli -> query($sql);

        //iterate through the result set
        if ($results) {
            $row = $results -> fetch_object();

            $this -> budget_item_id = $row -> ID;
            //$this -> budget_item_catg = $row -> CATG_ID;
            //$this -> budget_item_sub_catg = $row -> SUB_CATG_ID;
            $this -> budget_grand_total = $row -> TOTAL_AMT;
            $this -> budget_item_amount = $row -> ITEM_AMT;
            $this -> budget_item_pct = $row -> ITEM_PCTG;
            $this -> budget_other_dtl = $row -> OTHER_DTL;
        }

    }

    function InsertBudgetItem() {
        include ('code_connect.php');
        $sql = "CALL USP_INS_PROJ_BUDGET(@PROJ_ID_, @SUB_CATG_ID_, @ITEM_AMT_, @OTHER_DTL_, @IS_DELETED_)";

        $mysqli -> query("SET @PROJ_ID_ = " . $this -> selected_project_id);
        $mysqli -> query("SET @SUB_CATG_ID_ = " . $this -> budget_item_sub_catg);
        $mysqli -> query("SET @ITEM_AMT_ = " . $this -> budget_item_amount);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> budget_other_dtl . "'");
        $mysqli -> query("SET @IS_DELETED_ = " . "'false'");
        // $mysqli -> query("SET @CREATED_BY_ = " . $_SESSION['session_user_id'] );
        // $mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id'] );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Inserted Successfully");</script>';
        } else {
            echo '<script>alert("Insert Unsuccessful !");</script>';
        }
    }

    function UpdateBudgetItem() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_PROJ_BUDGET(@ID_, @SUB_CATG_ID_, @ITEM_AMT_, @OTHER_DTL_)";

        $mysqli -> query("SET @ID_ = " . $this -> budget_item_id);
        $mysqli -> query("SET @SUB_CATG_ID_ = " . $this -> budget_item_sub_catg);
        $item_amount = (int)str_replace(',', '', $this -> budget_item_amount);
        $mysqli -> query("SET @ITEM_AMT_ = " . $item_amount);
        $mysqli -> query("SET @OTHER_DTL_ = " . "'" . $this -> budget_other_dtl . "'");
        // $mysqli -> query("SET @IS_DELETED_ = " . "'false'");
        // $mysqli -> query("SET @CREATED_BY_ = " . $_SESSION['session_user_id'] );
        // $mysqli -> query("SET @USER_ID_ = " . $_SESSION['session_user_id'] );

        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Unsuccessful !");</script>';
        }
    }

    function DeleteBudgetItem() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_PROJ_BUDGET(@ID_)";

        $mysqli -> query("SET @ID_ = " . $this -> budget_item_id);
        $results = $mysqli -> query($sql);
        if ($results) {
            $row = $results -> fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Budget Still In Use )");</script>';
        }
    }

    function CalculateTotalBudget() {

    }

    function CalculateBudgetPct($Item) {

    }

    function LoadBudgetCATG() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_FIN_CATG()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            $CATG = $row -> CATG;
            $OTHER_DTL = $row -> OTHER_DTL;

            if ((isset($_GET['Cat']) && $_GET['Cat'] == $ID) || (isset($_GET['ExpCat']) && $_GET['ExpCat'] == $ID)){
                printf("<option value='%s' selected >%s</option>", $ID, $CATG);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $CATG);
            }

        }
        //recuperate resources
        $result -> free();
    }

    function LoadBudgetSubCATG() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_PROJ_FIN_SUBCATG (@CATG_ID_)";
        $mysqli -> query("SET @CATG_ID_ = " . $this -> budget_category_id);
        $result = $mysqli -> query($sql);
        //iterate through the result set
        while ($row = $result -> fetch_object()) {
            $ID = $row -> ID;
            // $CATG_ID = $row -> CATG_ID;
            $SUB_CATG = $row -> SUB_CATG;
            // $OTHER_DTL = $row -> OTHER_DTL;

            // $budget_page = 1;
            if ((isset($_GET['SubCat']) && $_GET['SubCat'] == $ID)||(isset($_GET['ExpSubCat']) && $_GET['ExpSubCat'] == $ID)) {
                printf("<option value='%s' selected >%s</option>", $ID, $SUB_CATG);
            } else {
                printf("<option value='%s'>%s</option>", $ID, $SUB_CATG);
            }
        }
        //recuperate resources
        $result -> free();
    }

    function BudgetPageParams() {
        include 'code_connect.php';
        $sql = "CALL USP_GET_PROJ_BUDGET_ALL (" . $this -> selected_project_id . ")";
        $result = $mysqli -> query($sql);
        $this -> budget_num_rows = $result -> num_rows;
        $this -> budget_last_page = ceil($this -> budget_num_rows / $this -> budget_page_rows);
    }

    function LoadProjectCurrencyInfo() {

    }

}
?>