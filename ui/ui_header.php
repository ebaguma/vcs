<link rel='shortcut icon' type='image/x-icon' href='images/favicon.png' />
<link rel="stylesheet" type="text/css" href="css/site-master.css">
<link rel="stylesheet" type="text/css" href="css/content-master.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<script src="js/date_picker/moment.min.js"></script>
<link rel="stylesheet" href="css/date_picker/pikaday.css">
<link rel="stylesheet" href="css/date_picker/site.css">
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/bootstrap.js"></script>
</head>
<body onLoad="<?php CheckReturnUser(); ?>">
	<div  class="Header">
		<div class="centreHeader">
			<div class="logo">
				Logo Area
			</div>
			<div id="MainHeader" class="Navigation">
				<ul class="nav nav-pills">
					<li  id="Index">
						<a href="<?php echo 'ui_home.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode']; ?>" >VCS HOME</a>
					</li>
					<li id="Projects">
						<a href="<?php echo 'ui_project_detail.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Details'; ?>">PROJECTS</a>
					</li>
					<li id="Masters">
                    <a href="<?php echo 'ui_masters.php?Mode=Read'; ?>">MASTERS</a>
						<!--<a href="<php echo 'ui_masters_detail.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#BioID'; ?>">MASTERS</a>-->
					</li>
					<li id="Bio">
						<a href="<?php echo 'ui_bio.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#BasicInfo'; ?>">BIO DATA</a>
					</li>
					<li id="Valuation">
						<a href="<?php echo 'ui_valuation.php?Mode=Read&ProjectID=' . $_GET['ProjectID'] . '&ProjectCode=' . $_GET['ProjectCode'] . '#Land'; ?>">VALUATION</a>
					</li>
					<li id="Reports">
						<a href="Reports.html">DOCUMENTATION</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<!-- ?php
    $selected_project_code;
	? -->

	<script type="text/javascript">
		function SetActivePage() {

			var element = document.querySelector('meta[name="Me"]');
			var content = element && element.getAttribute("content");
			document.getElementById(content).className = "active";

			var selected = document.getElementById(content);
			selected.getElementsByTagName("a")[0].removeAttribute("href");

			if (content == "Projects") {
				document.getElementById('SelectedPAP').style.display = "none";
			}

			if (content == "Index") {
				// document.getElementById('SelectedProject').style.display = "none";
				document.getElementById('SelectedPAP').style.display = "none";
			}

			if (content == "Masters") {
				document.getElementById('SelectedProject').style.display = "none";
				document.getElementById('SelectedPAP').style.display = "none";
				// document.getElementById('ContentP').style.height = "1300px";
				// document.getElementById('FooterParent').style.top = "1400px";
			}

		}

	</script>

	<!-- script>
	function OpenProjectList(){
	var hide = 'resizable=no,status=no,location=no,toolbar=no,menubar=no'
	var show = 'status=1,scrollbars=1,width=1100px,height=500px,left=50px,top=50px'
	var stringURL = 'Project_List.html?originator=';
	var currentURL = window.location.pathname;
	var page = currentURL.split("/").pop();
	window.open(stringURL+page, "Project_List", show, hide);
	}
	</script -->
	
    <!-- @formatter:off -->
	<div class="BreadCrumbParent">
		<div class="BreadCrumbContent">
			<span class="spanBreadCrumbs"><a href="#">Valuation, Compensation System:</a></span>
			<span class="spanCurrentProject">
			<a id="SelectedProject" href="ui_project_list.php?Mode=Read&PageNumber=1">
			<span style="color:#003366">Project:</span>
			<?php if (isset($_GET['ProjectCode'])) { echo $_GET['ProjectCode']; } ?> 
			</a> </span>

			<span class="spanUserStatus">
				<ul >
					<li class="dropdown">
						<a  class="dropdown-toggle"  data-toggle="dropdown"  href="#"><?php echo $_SESSION['display_name']; ?>&nbsp;<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="#">My Profile</a>
							</li>
							<li>
								<a href="#">My Tasks (35)</a>
							</li>
							<li>
								<a href="ui_project_list.php?logout=true">Sign Out</a>
							</li>
						</ul>
					</li>
				</ul> </span>
		</div>
	</div>