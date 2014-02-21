<?php

// Connect to MySQL.
require ('../../rentapup_sql_connect.php');
//Connect to the Database

//$q = "USE mysite";	
//$r = @mysqli_query ($dbc, $q); // Run the query.

// Make the query:
//$q = "INSERT INTO users (first_name, last_name, email, pass, registration_date)
//VALUES ('$fn', '$ln', '$e', SHA1('$p'), NOW() )";


$q = "SELECT * FROM puppies";
$r = @mysqli_query ($dbc, $q); // Run the query.

echo 'Puppies: <br>';

if($r)
{
	while ($row = mysqli_fetch_array($r))
	{
		echo $row['name'].'<br>';
	}
}
?>