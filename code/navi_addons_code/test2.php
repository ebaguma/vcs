<?php

class project
{	
 public function LoadProjects() {

        include ('conn_class2.php');
        $sql = "CALL USP_GET_PROJECTS_ALL()";
        $result = $mysqli -> query($sql);

        //iterate through the result set
        while ($row = $result -> fetch_object()) {
           echo $ID = $row -> ID;
            $PROJECTNAME = $row -> PROJ_NAME;
            $PROJECTCODE = $row -> PROJ_CODE;
            $DISPLAYNAME = $row -> DISP_NAME;
            $PROJECTSTART = $row -> START_DATE;
            $PROJECTEND = $row -> END_DATE;

            #$ACTION = '../ui/ui_project_detail.php?Mode=Read&ProjectID=' . $ID . '&ProjectCode=' . $PROJECTCODE . '#Details';
           # $this -> record_num = $this -> record_num + 1;
           # printf("<tr><td>%s</td><td><a href='%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $this -> record_num, $ACTION, $PROJECTNAME, $PROJECTCODE, $DISPLAYNAME, $PROJECTSTART, $PROJECTEND);
        }

        //recuperate resources
        $result -> free();
    }
}

var_dump(LoadProjects());
?>