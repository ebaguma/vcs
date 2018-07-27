<!-- 
#Global Changes
1.Stripped out gaps btn array accessors and their indexes ie $_SERVER[" PHP_SELF "] to $_SERVER["PHP_SELF"]
2.In uiPapList.php chenged table widths and other widths to percentanges to allow the table fit in its container and Table ie width="1000px" to width="100%"
  

3.Removed From ui_pap_list.php line : 200
-->
<span style="white-space: nowrap; float:left;">
                        <a href="<?php if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_prev_page'] ; } 
                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_prev_page'] ; } ?>" id="qq" >Previous</a>
                        &nbsp;&nbsp;<input name="GridPage" type="text" value="<?php if (isset($_GET['GridPage'])) { echo $_GET['GridPage'] . ' / ' . $GLOBALS['pap_num_pages'] ; } else {echo '1 / ' . $GLOBALS['pap_num_pages'] ; } ?>" style="width: 75px; height: 35px; margin-right: 0px; text-align: center; border: 1px solid #337ab7;"  />&nbsp;&nbsp;
                        <a href="<?php if (isset($_GET['KeyWord'])){ echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=SearchPap&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&KeyWord=' .  $_GET['KeyWord'] . '&GridPage=' . $GLOBALS['pap_next_page'] ; } 
                        else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&GridPage=' . $GLOBALS['pap_next_page'] ; } ?>" id ="mm" >Next</a>
                    </span>
<!-- 
    
4.Removed From ui_project_list.php line : 350
    commented out setActivePage() and showNavigation()

-->
<table id="GridNav" class="detailNavigation" style="margin: 20px 10px;">
							<tr>
								<td><a href="<?php if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectName=' . $_GET['ProjectName'] . '&PageNumber=' . $prev_page; } 
								else if (isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PageNumber=' . $prev_page; } 
								else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $prev_page; } ?>" id="tt" >Previous</a></td>
								<td class="PageJump" style="width:70px;">
								<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" autocomplete="false" >
									<input type="hidden" name="Mode" value="<?php if (isset($_GET['Mode'])) {echo $_GET['Mode']; } else {echo 'Read'; } ?>" />
									<input type="hidden" name="ProjectName" value="<?php if (isset($_GET['ProjectName'])) {echo $_GET['ProjectName']; } else {echo ''; } ?>" />
									<input type="hidden" name="ProjectCode" value="<?php if (isset($_GET['ProjectCode'])) {echo $_GET['ProjectCode']; } else {echo ''; } ?>" />
									<input name="PageNumber" type="text" value="<?php if (isset($_GET['PageNumber'])) {echo $_GET['PageNumber']; } else {echo 1; } ?>" class="NavBoxes" />
									/&nbsp;&nbsp;<?php echo $num_pages; ?>
									<input type="submit" style="position: absolute; left: -99999px;" />
								</form></td>
								<td><a href="<?php if (isset($_GET['ProjectName']) && $_GET['ProjectName'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectName=' . $_GET['ProjectName'] . '&PageNumber=' . $next_page; } 
								else if (isset($_GET['ProjectCode']) && $_GET['ProjectCode'] != "") { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&PageNumber=' . $next_page; } 
                                                                else { echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=' . $_GET['Mode'] . '&PageNumber=' . $next_page; } ?>" id="xx" >Next</a></td>
                                                       
							</tr>
                                                         
						</table>



