<?php

function ReadBioOccupation() {
        include_once ('../code/code_masters_Bio.php');

        echo '<script>ShowNavigation();</script>';

        $Masters_Bio = new MastersBio();

        //Check if Page Number is set
        if (isset($_GET['PageNumber'])) {
            $Masters_Bio -> load_page = $_GET['PageNumber'];
        } else {
            $_GET['PageNumber'] = 1;
        }

        //set pagination parameters
        $Masters_Bio -> ReadPageParams();
        global $num_pages;
        $num_pages = $Masters_Bio -> read_last_page;

        //Handling grid pages and navigation
        if ($_GET['PageNumber'] == 1) {
            $Masters_Bio -> record_num = 0;
            $Masters_Bio -> data_offset = 0;
        } else if ($_GET['PageNumber'] <= $Masters_Bio -> read_last_page) {
            $Masters_Bio -> data_offset = ($_GET['PageNumber'] - 1) * $Masters_Bio -> page_rows;
            $Masters_Bio -> record_num = ($_GET['PageNumber'] - 1) * $Masters_Bio -> page_rows; ;
        } else {
            echo '<script>alert("Page Is Out Of Range");</script>';
            $_GET['PageNumber'] = 1;
            $Masters_Bio -> record_num = 0;
            $Masters_Bio -> data_offset = 0;
        }

        if (($_GET['PageNumber'] + 1) <= $Masters_Bio -> read_last_page) {
            global $next_page;
            $next_page = $_GET['PageNumber'] + 1;
        } else {
            $next_page = 1;
        }

        if (($_GET['PageNumber'] - 1) >= 1) {
            global $prev_page;
            $prev_page = $_GET['PageNumber'] - 1;
        } else {
            $prev_page = 1;
        }

        //Loading Projects
        $Masters_Bio -> LoadBioOccupation();
    }
	
	?>