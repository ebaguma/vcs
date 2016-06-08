<?php


         include ('conn_class2.php');
		 
		/* */if(!$mysqli->multi_query("call USP_GET_MST_OCCUPATION_ALL()")){
			echo "CALL failed:(" . $mysqli->errno . ") " . $mysqli->error; 
		 }
		 
		 do {
			if($res = $mysqli->store_result()){
			printf("----\n");
			var_dump($res->fetch_all());
			$res->free();				
			}else{
				if($mysqli->errno) {
					echo "store failed: (".$mysqli->errno.")".$mysqli->error.'<br />';
				}
			
			 
			 
		 }
		 }while ($mysqli->more_result() && $mysqli->next_result());
		 /*$mysqli->multi_query("call USP_GET_PROJECTS_ALL()");
		 $res = $mysqli->store_result();
		 
		#$propartyq 	= mysqli_query(mysqli,"SELECT * FROM proparty") or die("Problem with Query5: " . mysqli_error());
		##$propartyq 	= mysql_query("SELECT * FROM trn_proj_details") or die("Problem with Query5: " . mysql_error());
		#$propartyq 	= mysql_query("SELECT * FROM trn_proj_details") or die(mysql_error());
		
		
		
		
		
		 while($row = mysqli_fetch_assoc($res)) {
			 
			 
			echo '<br />'.$row['ID'].'<br />'; 
			echo '<br />'.$row['PROJ_NAME'].'<br />'; 
			echo '<br />'.$row['PROJ_CODE'].'<br />'; 
			echo '<br />'.$row['PROJ_OBJ'].'<br />'; 
			 
			 
			 
			 
			 
		 }
		 /**/
		 /*while($row = mysqli_fetch_assoc($propartyq)) {
			 
			 
			echo '<br />'.$row['id'].'<br />'; 
			echo '<br />'.$row['title'].'<br />'; 
			
			 
			 
			 
			 
			 
		 }*/

  







?>