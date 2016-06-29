<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

if (isset($_GET['Mode']) && $_GET['Mode']=='Upload'){ UploadFile(); }

?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- link rel='shortcut icon' type='image/x-icon' href='ui/images/favicon.png' / -->
        <meta charset="utf-8">
            <meta name="Me" content="Index" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <title>VCS&nbsp;&nbsp;|&nbsp;&nbsp;Login</title>
            <!-- link href="ui/css/index.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" type="text/css"
                  href="ui/lib/opensans_regular/stylesheet.css" / -->
    </head>
	
	<?php

	function UploadFile() {
		include_once ('../code/code_config.php');
		$image_name = $_FILES['image']['name'];
		$target_dir = $main_doc_dir;
		$image_size = 2097152;
		$acceptable = array('image/jpeg', 'image/JPEG','image/jpg','image/JPG', 'image/png','image/PNG','image/x-png','image/X-PNG');

		# $image_name = addslashes($_FILES['image']['name']);
		$pap_hhid = "4312";
		$pap_project = "KIP";
		$pap_str = "PAP";
		# $file_count = 0;
		
		$ext = findexts($image_name);
		$new_image_name = $pap_hhid . '.' . $ext;
		$check_exists = glob($pap_hhid . ".*");

		if ($_FILES['image']['name'] == "") {
			echo '<script>alert("You haven\'t selected a file");</script>';
			header('Refresh:0; url=/test/image.php');
		} else {
			if (!in_array($_FILES['image']['type'], $acceptable)) {
				echo '<script>alert("Invalid Format, Only JPG, PNG Allowed");</script>';
				header('Refresh:0; url=/test/image.php');
			} else {
				if ($_FILES['image']['size'] > $image_size) {
					echo '<script>alert("Large File, Only 2MB Allowed");</script>';
					header('Refresh:0; url=/test/image.php');
				} else {

					$target_project_dir = $target_dir . $pap_project;
					$target_pap_dir = $target_project_dir . '/' . $pap_str;
					$target_hhid_dir = $target_pap_dir . '/' . $pap_hhid;
					
					if (!is_dir($target_project_dir)) { mkdir($target_project_dir); 					}
					if (!is_dir($target_pap_dir)) { mkdir($target_pap_dir); }
					if (!is_dir($target_hhid_dir)) { mkdir($target_hhid_dir); }
					
					$final_target = $target_hhid_dir . '/';
					$file_count = count(array_diff(scandir($final_target), array('.','..')));
					
					/* $images = glob($target_hhid_dir . '/*.{jpeg,JPEG,jpg,JPG,png,PNG}', GLOB_BRACE);
					if (empty($images)) { $echo = 'Yes'; }
					echo '<script>alert("' . $echo . '");</script>';
					header('Refresh:0; url=/test/image.php'); */
					
					if ($file_count == 0) {
						move_uploaded_file($_FILES['image']['tmp_name'], $target_hhid_dir . '/' . $new_image_name);
						include_once ('../code/code_pap_basic_info.php');
						$upload_image = new PapBasicInfo();
						$upload_image -> pap_hhid = intval($pap_hhid);
						$upload_image -> photo_name = $pap_hhid;
						$upload_image -> photo_path = $pap_project . '/' . $pap_str . '/' . $pap_hhid;
						$upload_image -> photo_ext = $ext;

						$upload_image -> UpdatePhoto(); 
						# echo '<script>alert("Data Uploaded Successfully");</script>';
						header('Refresh:0; url=/test/image.php');
					} else {
						echo '<script>alert("Image File Already Exists");</script>';
						header('Refresh:0; url=/test/image.php');
					}

				}
			}
		}
	}

	function findexts($filename) {
		$filename = strtolower($filename);
		$exts = split("[/\\.]", $filename);
		$n = count($exts) - 1;
		$exts = $exts[$n];
		return $exts;
	}
	?>
	
    <body >
    	<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Upload'; ?>" method="POST" >
    		<input type="file" name="image" style="border: 1px solid; padding: 5px; width: 300px; " value="" >&nbsp;&nbsp;<input type="submit" value="Upload">
    	</form>
    </body>
</html>