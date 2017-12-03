<?php

Class PapLandVal {
    # General parameters

    public $selected_project_id;
    public $selected_project_code;
    public $session_user_id;

    # Land Valuation Parameters:
    # ID,PAP_ID,UNIT_MSR,SHARE_OF_LND,DIMINUTION,LND_TYPE,IS_TITLED,IS_DELETED,UPDATED_BY,UPDATED_DATE,
    # CREATED_BY,CREATED_DATE,ROW_UNITS,WL_UNITS,RATE
    public $pap_id;
    public $unit_msr;
    public $share_of_lnd;
    public $diminution;
    public $lnd_type;
    public $is_titled;
    public $is_deleted;
    public $updated_by;
    public $plot;
    public $block;
    public $updated_date;
    public $created_by;
    public $created_date;
    public $row_units;
    public $row_other_units;
    public $row_value;
    public $wl_units;
    public $wl_other_units;
    public $wl_value;
    public $rate;
    public $pap_status_id;
    public $pap_status;
    public $pap_count;

    function ConvertUnits() {
        
    }

    function BindUnits() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_VAL_LAND_UNIT_MSR()";
        $result = $mysqli->query($sql);

        //iterate through the result set
        while ($row = $result->fetch_object()) {
            # ID,UNIT_MSR

            $ID = $row->ID;
            $UNIT_MSR = $row->UNIT_MSR;

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $unit_msr = $_SESSION['unit_msr'];
            } else {
                $unit_msr = $_SESSION['unit_msr'];
            }

            if (isset($unit_msr) && $unit_msr == $ID && $_GET['Mode'] == 'ViewLand') {
                printf("<option value='%s' selected >%s</option>", $ID, $UNIT_MSR);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $UNIT_MSR);
            }
        }
    }

    function BindTenure() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_VAL_LAND_TYP()";
        $result = $mysqli->query($sql);

        //iterate through the result set
        while ($row = $result->fetch_object()) {
            # ID,LND_TYPE

            $ID = $row->ID;
            $LND_TYPE = $row->LND_TYPE;

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $lnd_type = $_SESSION['lnd_type'];
            } else {
                $lnd_type = $_SESSION['lnd_type'];
            }

            if (isset($lnd_type) && $lnd_type == $ID && $_GET['Mode'] == 'ViewLand') {
                printf("<option value='%s' selected >%s</option>", $ID, $LND_TYPE);
            } else {
                printf("<option value='%s' >%s</option>", $ID, $LND_TYPE);
            }
        }
    }

    function LoadLandVal() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_VAL_LAND (@PAP_ID_)";
        $mysqli->query("SET @PAP_ID_ = " . $this->pap_id);

        $result = $mysqli->query($sql);

        //iterate through the result set
        while ($row = $result->fetch_object()) {
            # PAP_ID,UNIT_MSR,SHARE_OF_LND,DIMINUTION,LND_TYPE,IS_TITLED,IS_DELETED,UPDATED_BY,UPDATED_DATE,
            # CREATED_BY,CREATED_DATE,ROW_UNITS,WL_UNITS,RATE

            $PAP_ID = $row->PAP_ID;
            $UNIT_MSR = $row->UNIT_MSR;
            $SHARE_OF_LND = $row->SHARE_OF_LND;
            $DIMINUTION = $row->DIMINUTION;
            $LND_TYPE = $row->LND_TYPE;
            $IS_TITLED = $row->IS_TITLED;
            $IS_DELETED = $row->IS_DELETED;
            $UPDATED_BY = $row->UPDATED_BY;
            $UPDATED_DATE = $row->UPDATED_DATE;
            $CREATED_BY = $row->CREATED_BY;
            $CREATED_DATE = $row->CREATED_DATE;
            $ROW_UNITS = $row->ROW_UNITS_AC;
            $ROW_VALUE = $row->ROW_VALUE;
            $WL_UNITS = $row->WL_UNITS_AC;
            $WL_VALUE = $row->WL_VALUE;
            $RATE = $row->RATE;
            $PAP_STATUS_ID = $row->PAP_STATUS_ID;
            $PAP_STATUS = $row->PAP_STATUS;



            $proj_sect_page = 1;
            $TOT_TAKE = number_format(($ROW_UNITS + $WL_UNITS), 4, '.', ',');
            $TOT_VALUE = number_format(($ROW_VALUE + $WL_VALUE), 0, '.', ',');

            $confirm = "Are You Sure?";
            $EDIT_URL = '../ui/ui_valuation.php?Mode=ViewLand&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $PAP_ID . '#ValLand';
            $EDIT_ACTION = '<a href="' . $EDIT_URL . '" ><img src="images/Edit.png" alt="" class="EditDeleteButtons" /></a>';
            $DEL_URL = '../ui/ui_valuation.php?Mode=DeleteLand&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&HHID=' . $PAP_ID . '#ValLand';
            $DEL_ACTION = '<a href="' . $DEL_URL . '" onClick="return confirm(\'Are You Sure, Delete Address?\');"><img src="images/delete.png" alt="" class="EditDeleteButtons" /></a>';


            printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s&nbsp;%s</td></tr>", $LND_TYPE, $PAP_STATUS, $TOT_TAKE, number_format($RATE, 0, '.', ','), $TOT_VALUE, $EDIT_ACTION, $DEL_ACTION);
        }


        //recuperate resources
        $result->free();
    }

    function CheckLandExists() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_VAL_LAND (@PAP_ID_)";
        $mysqli->query("SET @PAP_ID_ = " . $this->pap_id);

        $result = $mysqli->query($sql);

        $this->pap_count = $result->num_rows;
    }

    function SelectLandVal() {
        include ('code_connect.php');
        $sql = "CALL USP_GET_VAL_LAND (@PAP_ID_)";
        $mysqli->query("SET @PAP_ID_ = " . $this->pap_id);

        $result = $mysqli->query($sql);

        //iterate through the result set
        if ($result) {
            # ID,PAP_ID,ROAD,IS_RESIDENT,VILL_ID,VILLAGE,SUBCTY_ID,CTY_ID,DIST_ID,DISTRICT

            $row = $result->fetch_object();

            # PAP_ID,UNIT_MSR,SHARE_OF_LND,DIMINUTION,LND_TYPE,IS_TITLED,IS_DELETED,UPDATED_BY,UPDATED_DATE,
            # CREATED_BY,CREATED_DATE,ROW_UNITS,WL_UNITS,RATE

            $this->pap_id = $row->PAP_ID;
            $this->unit_msr = $row->UNIT_MSR;
            $this->share_of_lnd = $row->SHARE_OF_LND;
            $this->diminution = $row->DIMINUTION;
            $this->lnd_type = $row->LND_TYPE_ID;
            $this->is_titled = $row->IS_TITLED;
            $this->is_deleted = $row->IS_DELETED;
            $this->updated_by = $row->UPDATED_BY;
            $this->updated_date = $row->UPDATED_DATE;
            $this->created_by = $row->CREATED_BY;
            $this->plot = $row->PLOT;
            $this->block = $row->BLOCK;
            $this->created_date = $row->CREATED_DATE;
            $this->row_units = $row->ROW_UNITS;
            $this->row_other_units = $row->ROW_OTHER_UNITS;
            $this->row_value = $row->ROW_VALUE;
            $this->wl_units = $row->WL_UNITS;
            $this->wl_other_units = $row->WL_OTHER_UNITS;
            $this->wl_value = $row->WL_VALUE;
            $this->rate = $row->RATE;
            $this->pap_status_id = $row->PAP_STATUS_ID;
            $this->pap_status = $row->PAP_STATUS;
        }
    }

    function UpdateLandVal() {
        include ('code_connect.php');
        $sql = "CALL USP_UPD_VAL_LAND(@PAP_ID_, @ROW_UNITS_, @WL_UNITS_, @UNIT_MSR_, @BLOCK_, @PLOT_, @SHARE_OF_LND_, @DIMINUTION_, @RATE_, @LND_TYPE_, @IS_TITLED_, @UPDATED_BY_)";
        # ID_ INT, PAP_ID_ INT, ROW_UNITS_ INT, WL_UNITS_ INT, UNIT_MSR_ INT, SHARE_OF_LND_ INT, DIMINUTION_ INT, RATE_ INT, LND_TYPE_ INT, IS_TITLED_ VARCHAR(10), UPDATED_BY_ INT
        $mysqli->query("SET @PAP_ID_ = " . $this->pap_id);
        $mysqli->query("SET @ROW_UNITS_ = " . $this->row_units);
        $mysqli->query("SET @WL_UNITS_ = " . $this->wl_units);
        $mysqli->query("SET @UNIT_MSR_ = " . $this->unit_msr);
        $mysqli->query("SET @BLOCK_ = " . $this->block);
        $mysqli->query("SET @PLOT_ = " . $this->plot);
        $mysqli->query("SET @SHARE_OF_LND_ = " . $this->share_of_lnd);
        $mysqli->query("SET @DIMINUTION_ = " . $this->diminution);
        $mysqli->query("SET @RATE_ = " . $this->rate);
        $mysqli->query("SET @LND_TYPE_ = " . $this->lnd_type);
        $mysqli->query("SET @IS_TITLED_ = " . "'" . $this->is_titled . "'");
        $mysqli->query("SET @UPDATED_BY_ = " . $this->session_user_id);

        $results = $mysqli->query($sql);
        if ($results) {
            $row = $results->fetch_object();
            echo '<script>alert("Data Updated Successfully");</script>';
        } else {
            echo '<script>alert("Update Not Successful");</script>';
        }
    }

    function DeleteLandVal() {
        include ('code_connect.php');
        $sql = "CALL USP_DEL_VAL_LAND(@PAP_ID_)";

        $mysqli->query("SET @PAP_ID_ = " . $this->pap_id);
        $results = $mysqli->query($sql);
        if ($results) {
            $row = $results->fetch_object();
            echo '<script>alert("Data Deleted Successfully");</script>';
        } else {
            echo '<script>alert("Delete Unsuccessful ( Staff Still In Use )");</script>';
        }
    }

}

?>
