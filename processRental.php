<?php
// Clean up the input values
foreach($_POST as $key => $value) {
  if(ini_get('magic_quotes_gpc'))
    $_POST[$key] = stripslashes($_POST[$key]);
 
  $_POST[$key] = htmlspecialchars(strip_tags($_POST[$key]));
}
 
// Assign the input values to variables for easy reference
$name = $_POST["name"];
$email = $_POST["email"];
$phone = $_POST["phone"];
$puppy = $_POST["pupSelect"];
$date = $_POST["datepicker"];
$startTime = $_POST["startTimeValue"];
$stopTime = $_POST["stopTimeValue"];
 
// Test input values for errors
$errors = array();
if(strlen($name) < 2) {
  if(!$name) {
    $errors[] = "You must enter a name.";
  } else {
    $errors[] = "Name must be at least 2 characters.";
  }
}
if(!$email) {
  $errors[] = "You must enter an email.";
} else if(!validEmail($email)) {
  $errors[] = "You must enter a valid email.";
}
if(!$phone) {
	$errors[] = "You must enter a phone number.";
} else if(!validPhone($phone)) {
	$errors[] = "Phone number must be 10 or 11 digits.";
}
if(!$puppy) {
    $errors[] = "You must select a puppy.";
} else if(!validPuppy($puppy)) {
    $errors[] = "You must select an available puppy.";
}
if(!$date) {
    $errors[] = "You must select a date.";
} else if(!validDate($date)) {
    $errors[] = "You must select a valid date.";
}
if(!validTime($startTime, $stopTime)) {
    $errors[] = "You must select a valid start and end time.";
}
if(!validReservation($startTime, $stopTime, $puppy, $date)){
	$errors[] = "You must select an available rental time.";
}
$duration = ($stopTime / 60.0) - ($startTime / 60.0);
$price = calcPrice($duration);
 
if($errors) {
  // Output errors and die with a failure message
  $errortext = "";
  foreach($errors as $error) {
    $errortext .= "<li>".$error."</li>";
  }
  die("<span class='failure'>The following errors occured:<ul>". $errortext ."</ul></span>");
}

$client = saveUser($name, $email, $phone);
$reservation = saveReservation($client, $puppy, $date, $startTime, $stopTime, $price);

$puppyName = getPuppyName($puppy);
 
sendReservation($client, $name, $email, $phone, $reservation, $puppyName, $date, $startTime, $stopTime, $duration, $price);
sendConfirmation($client, $name, $email, $phone, $reservation, $puppyName, $date, $startTime, $stopTime, $duration, $price);
 
// Die with a success message
$table = formatReservation($client, $name, $email, $phone, $reservation, $puppyName, $date, $startTime, $stopTime, $duration, $price);
die("<span class='success'>Success! Your rental has been sent. We will contact you shortly to confirm.</span>" . $table);
 
// A function that checks to see if
// an email is valid
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

function validPhone($phone)
{
	$digits = strlen(preg_replace("/[^0-9]/","",$phone));
	return $digits >= 10 && $digits <= 11;
}

function validPuppy($puppy)
{
	return $puppy > 0;
}

function validDate($date)
{
	if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
        return checkdate($matches[2], $matches[3], $matches[1]);
    }

    return false;
}

function validTime($startTime, $endTime)
{
	return $startTime >= 0 && $startTime <= 1440 && $endTime >= 0 && $$endTime <= 1440 && $startTime < $endTime;
}

function minutesToClock($time)
{
		$time = $time + 0;
		
		if($time == 1440)
			$time = 1439;
		
		$hours = floor($time / 60);
        $minutes = $time - ($hours * 60);

        if ($minutes < 10) $minutes = '0' . $minutes;
        if ($minutes == 0) $minutes = '00';
        if ($hours >= 12) {
            if ($hours > 12) $hours = $hours - 12;
			$minutes = $minutes . " PM";
        } else {
            $minutes = $minutes . " AM";
        }
        if ($hours == 0) $hours = 12;
		
		return $hours . ':' . $minutes;
}

function validReservation($startTime, $stopTime, $puppy, $date)
{
	// Connect to MySQL.
	require ('../../rentapup_sql_connect.php');

	if(!$dbc)
	{
		return false;
	}

	$q = "SELECT * FROM reservations WHERE date = '".$date."' AND puppy_id = '".$puppy."'";
	$r = @mysqli_query ($dbc, $q); // Run the query.

	if($r)
	{
		while ($row = mysqli_fetch_array($r))
		{
			$blackoutStart = $row['start_time'] + 0;
			$blackoutEnd = $row['end_time'] + 0;
			
			if(($blackoutStart >= $startTime && $blackoutStart <= $stopTime) || 
			   ($blackoutEnd >= $startTime && $blackoutEnd <= $stopTime))
			   {
				   return false;
			   }
		}
	}
	
	return true;
}

function getPuppyName($puppy)
{
	// Connect to MySQL.
	require ('../../rentapup_sql_connect.php');

	if(!$dbc)
	{
		return "Puppy #".$puppy;
	}

	$q = "SELECT * FROM puppies WHERE puppy_id = '".$puppy."'";
	$r = @mysqli_query ($dbc, $q); // Run the query.

	if($r)
	{
		while ($row = mysqli_fetch_array($r))
		{
			$puppyName = $row['name'];
			
			if($puppyName)
			{
				return $puppyName;
			}
		}
	}
	
	return "Puppy #".$puppy;
}

function calcPrice($duration)
{
	$price = number_format($duration * 5.0, 2, '.', '');	
	return $price;
}

function sendEmail($to, $subject, $message)
{
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: Rent-A-Pup@ltacosta.com";
	mail($to, $subject, $message, $headers);
}

function sendReservation($client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$to = "Rent-A-Pup@ltacosta.com";
	$title = "New Reservation";
	$desc = "A new reservation has been received! Reservation information is below.";
	
	sendNewRental($to, $title, $desc, $client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price);
}

function sendConfirmation($client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$title = "Reservation Confirmation";
	$desc = "We've received your reservation! We will contact you soon to arrange pick up. Please review the information below and contact us to make any changes.";
	
	sendNewRental($email, $title, $desc, $client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price);
}

function sendNewRental($to, $title, $desc, $client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$subject = "Rent-A-Pup: " . $title;
	$table = formatReservation($client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price);
	
	$message = file_get_contents("email.html");
	$message = str_replace("!!!TITLE!!!", $title, $message);
	$message = str_replace("!!!CONTENT!!!", $desc . $table, $message);
	
	sendEmail($to, $subject, $message);
}

function formatReservation($client, $name, $email, $phone, $reservation, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$startTime = minutesToClock($startTime);
	$stopTime = minutesToClock($stopTime);
	
	$time = $startTime . " - " . $stopTime . " (" . $duration . " hours)";
	
	$table = file_get_contents("reservationFormat.html");
	$table = str_replace("!!!CLIENT!!!", $client, $table);
	$table = str_replace("!!!NAME!!!", $name, $table);
	$table = str_replace("!!!EMAIL!!!", $email, $table);
	$table = str_replace("!!!PHONE!!!", $phone, $table);
	$table = str_replace("!!!RESERVATION!!!", $reservation, $table);
	$table = str_replace("!!!PUPPY!!!", $puppy, $table);
	$table = str_replace("!!!DATE!!!", $date, $table);
	$table = str_replace("!!!TIME!!!", $time, $table);
	$table = str_replace("!!!PRICE!!!", '$' . $price, $table);
	
	return $table;
}

function saveUser($name, $email, $phone)
{
	// Connect to MySQL.
	require ('../../rentapup_sql_connect.php');

	if(!$dbc)
	{
		return false;
	}

	$q = "SELECT * FROM clients WHERE email = '".$email."'";
	$r = @mysqli_query ($dbc, $q); // Run the query.
	$client;

	if($r)
	{
		while ($row = mysqli_fetch_array($r))
		{
			$client = $row['client_id'];
			break;
		}
	}
	
	if($client)
	{
		$u = "UPDATE clients SET name='".$name."', phone='".$phone."' WHERE email='".$email."'";
		@mysqli_query ($dbc, $u); // Run the query
		return $client;
	}
	
	$i = "INSERT INTO clients(name, email, phone) VALUES('".$name."','".$email."','".$phone."')";
	@mysqli_query ($dbc, $i); // Run the query
	
	$r = @mysqli_query ($dbc, $q); // Run the query.

	if($r)
	{
		while ($row = mysqli_fetch_array($r))
		{
			$client = $row['client_id'];
			break;
		}
	}
	
	return $client;
}

function saveReservation($client, $puppy, $date, $startTime, $stopTime, $price)
{
	// Connect to MySQL.
	require ('../../rentapup_sql_connect.php');

	if(!$dbc)
	{
		return false;
	}
	
	$i = "INSERT INTO reservations(client_id, puppy_id, date, start_time, end_time, price, placed_date) "
	     ."VALUES('".$client."','".$puppy."','".$date."','".$startTime."','".$stopTime."','".$price."',NOW())";
	@mysqli_query ($dbc, $i); // Run the query

	$q = "SELECT * FROM reservations WHERE client_id = '".$client."' "
	                                 ."AND puppy_id = '".$puppy."' "
									 ."AND date = '".$date."' "
									 ."AND start_time = '".$startTime."'";
	$r = @mysqli_query ($dbc, $q); // Run the query.
	$reservation;

	if($r)
	{
		while ($row = mysqli_fetch_array($r))
		{
			$reservation = $row['reservation_id'];
			break;
		}
	}
	
	return $reservation;
}
?>