<?php echo file_get_contents("header.html"); ?>
    
<h2>Meet Our Pups!</h2>
    
<?php
	// Connect to MySQL.
	require ('../../rentapup_sql_connect.php');

	if(!$dbc)
	{
		die('Oh no! We can\'t find the puppies!');
	}

	$q = "SELECT * FROM puppies";
	$r = @mysqli_query ($dbc, $q); // Run the query.

	if($r)
	{
		$makeRight = True;
		
		while ($row = mysqli_fetch_array($r))
		{
			$id = $row['puppy_id'];
			$name = $row['name'];
			$bio = $row['bio'];
			
			if($makeRight)
				echo makeRightTable($id, $name, $bio);
			else
				echo makeLeftTable($id, $name, $bio);
				
			$makeRight = !$makeRight;
		}
	}
	
	function makeRightTable($id, $name, $bio)
	{
		$table = file_get_contents("rimg.html");
		
		return makeTable($table, $id, $name, $bio);
	}
	
	function makeLeftTable($id, $name, $bio)
	{
		$table = file_get_contents("limg.html");
		
		return makeTable($table, $id, $name, $bio);
	}
	
	function makeTable($table, $id, $name, $bio)
	{
		$img = "img/pup".$id.".png";
		
		$table = str_replace("!!!TITLE!!!", $name, $table);
		$table = str_replace("!!!IMGSRC!!!", $img, $table);
		$table = str_replace("!!!CAPTION!!!", $bio, $table);
		
		return $table;
	}
?>
      
<?php echo file_get_contents("footer.html"); ?>