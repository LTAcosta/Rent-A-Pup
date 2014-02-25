<?php
// Clean up the input values
foreach($_REQUEST as $key => $value) {
  if(ini_get('magic_quotes_gpc'))
    $_REQUEST[$key] = stripslashes($_REQUEST[$key]);
 
  $_REQUEST[$key] = htmlspecialchars(strip_tags($_REQUEST[$key]));
}
 
// Assign the input value to a variable for easy reference
$date = $_REQUEST["date"];
$puppy = $_REQUEST["puppy"];

if(!$date)
{
	die('NO DATE!');
}

if(!$puppy)
{
	die('NO PUPPY!');
}

// Connect to MySQL.
require ('../../rentapup_sql_connect.php');

if(!$dbc)
{
	die('NO CONNECTION!');
}

$q = "SELECT * FROM reservations WHERE date = '".$date."' AND puppy_id = '".$puppy."'";
$r = @mysqli_query ($dbc, $q); // Run the query.

if($r)
{
	while ($row = mysqli_fetch_array($r))
	{
		echo $row['start_time'].'-'.$row['end_time'].',';
	}
}

?>