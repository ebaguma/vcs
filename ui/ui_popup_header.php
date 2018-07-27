<link rel="stylesheet" type="text/css" href="css/site-master.css">
<link rel='shortcut icon' type='image/x-icon' href='images/favicon.png' />
<link rel="stylesheet" type="text/css" href="css/content-master.css" >
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/pap_list_projects_popup.css" >
<link rel="stylesheet" type="text/css" href="bkicons/bakerFont.css" >
<script src="js/jquery-2.1.4.min.js" ></script>
<script src="js/bootstrap.js" ></script>

<script type="text/javascript" src="trial/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" href="trial/jquery-ui-themes/themes/hot-sneaks/jquery-ui.css">
<link rel="stylesheet" href="trial/jquery-ui-themes/themes/hot-sneaks/theme.css">
<link rel="stylesheet" href="trial/datatables/css/dataTables.jqueryui.min.css">
 <script src="trial/datatables/js/jquery.dataTables.min.js"></script>
    <script src="trial/datatables/js/dataTables.jqueryui.min.js"></script>
</head>
<body onLoad="<?php CheckReturnUser(); ?>">

	

	<script type="text/javascript">
		function CheckThis() {

			var element = document.querySelector('meta[name="Me"]');
			var content = element && element.getAttribute("content");

			if (content == "PapList") {
				document.getElementById('ContentP').style.height = "900px";
				document.getElementById('FooterP').style.top = "950px";
			}

			if (content == "ProjectList") {
				document.getElementById('ContentP').style.height = "800px";
				document.getElementById('FooterP').style.top = "850px";
				document.getElementById('SelectedProject').style.display = "none";
			}
		}
	</script>

	<script type="text/javascript">
		function getParamValuesByName(querystring) {
			var qstring = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for (var i = 0; i < qstring.length; i++) {
				var urlparam = qstring[i].split('=');
				if (urlparam[0] == querystring) {
					return urlparam[1];
				}
			}
		}

		function redirectHome() {

			var element = document.querySelector('meta[name="Me"]');
			var content = element && element.getAttribute("content");

			//	var redirectURL = getParameterByName('originator');
			if (content == "ProjectList") {
				var redirectURL = "Projects.html"
			} else {
				var redirectURL = getParamValuesByName('originator');
			}

			window.opener.location.href = redirectURL;
			window.close();
		}
	</script>
        
	<div class="BreadCrumbParent" style="margin-top: 0;">
		<div class="BreadCrumbContent">
			<span class="spanBreadCrumbs"><a href="JavaScript:void(0);" onclick="redirectHome();">Valuation, Compensation System:</a></span>
			<span class="spanCurrentProject">
            <a id="SelectedProject" href="ui_project_list.php?Mode=Read&PageNumber=1">
                <span style="color:#003366">Project:</span>
                 <?php if (isset($_GET['ProjectCode'])) { echo $_GET['ProjectCode']; } ?> 
            </a>    
		    </span>
			<span class="spanUserStatus">
				<ul >
					<li class="dropdown">
						<a  class="dropdown-toggle"  data-toggle="dropdown"  href="#"><?php echo $_SESSION['display_name']; ?>
						&nbsp;<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="#">My Profile</a>
							</li>
							<li>
								<a href="#">My Tasks (35)</a>
							</li>
							<li>
							    <!-- a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '&Logout=true' ; ?>" >Sign Out</a -->
                                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?LogOut=true' ; ?>" >Sign Out</a>
						    </li>
						</ul>
					</li>
				</ul> </span>
		</div>
	</div>
