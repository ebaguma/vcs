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
	
	function UploadFile(){
		$image = $_FILES['image']['tmp_name'];
		# $image_name = addslashes($_FILES['image']['name']);
		$image_name = 4312;
		
		include_once('../code/code_pap_basic_info.php');
		$upload_image = new PapBasicInfo();
		$upload_image->pap_photo = $image;
		$upload_image->pap_photo_name = $image_name;
		$upload_image->pap_hhid = 4312;
		
		$upload_image->UpdatePhoto();
	}
	
	?>
	
    <body >
    	<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?Mode=Upload'; ?>" method="POST" >
    		<input type="file" name="image" style="border: 1px solid; padding: 5px; width: 300px; " value="" >&nbsp;&nbsp;<input type="submit" value="Upload">
    	</form>
    </body>
</html>