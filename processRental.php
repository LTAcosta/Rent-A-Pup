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
$duration = $_POST["durationValue"];
 
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
if(!validTime($startTime)) {
    $errors[] = "You must select a valid start time.";
}
if(!validTime($stopTime)) {
    $errors[] = "You must select a valid end time.";
}
if(!validDuration($duration)) {
    $errors[] = "You must select a valid rental time.";
}
$price = calcPrice($duration);
 
if($errors) {
  // Output errors and die with a failure message
  $errortext = "";
  foreach($errors as $error) {
    $errortext .= "<li>".$error."</li>";
  }
  die("<span class='failure'>The following errors occured:<ul>". $errortext ."</ul></span>");
}
 
sendReservation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price);
sendConfirmation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price);
 
// Die with a success message
$table = formatReservation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price);
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
	return $puppy == "Charles" ||
	       $puppy == "Chunk" ||
		   $puppy == "Goose" ||
		   $puppy == "Sheila";
}

function validDate($date)
{
	if (preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $date, $matches)) {
        return checkdate($matches[1], $matches[2], $matches[3]);
    }

    return false;
}

function validTime($time)
{
	$length = strlen($time);
	$digits = strlen(preg_replace("/[^0-9]/","",$time));
	return $length >= 7 && $length <= 8 && $digits >= 3 && $digits <= 4;
}

function validDuration($duration)
{
	$value = floatval(ereg_replace("[^-0-9\.]","",$duration));
	return $value > 0;
}

function calcPrice($duration)
{
	$durationVal = floatval(ereg_replace("[^-0-9\.]","",$duration));
	$price = number_format($durationVal * 5.0, 2, '.', '');	
	return $price;
}

function sendEmail($to, $subject, $message)
{
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: Rent-A-Pup@ltacosta.com";
	mail($to, $subject, $message, $headers);
}

function sendReservation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$to = "Rent-A-Pup@ltacosta.com";
	$title = "New Reservation";
	$desc = "A new reservation has been received! Reservation information is below.";
	
	sendNewRental($to, $title, $desc, $name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price);
}

function sendConfirmation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$title = "Reservation Confirmation";
	$desc = "We've received your reservation! We will contact you soon to arrange pick up. Please review the information below and contact us to make any changes.";
	
	sendNewRental($email, $title, $desc, $name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price);
}

function sendNewRental($to, $title, $desc, $name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$subject = "Rent-A-Pup: " . $title;
	$table = formatReservation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price);
	
	$message = file_get_contents("email.html");
	$message = str_replace("!!!TITLE!!!", $title, $message);
	$message = str_replace("!!!CONTENT!!!", $desc . $table, $message);
	
	sendEmail($to, $subject, $message);
}

function formatReservation($name, $email, $phone, $puppy, $date, $startTime, $stopTime, $duration, $price)
{
	$time = $startTime . " - " . $stopTime . " (" . $duration . " hours)";
	
	$table = file_get_contents("reservationFormat.html");
	$table = str_replace("!!!NAME!!!", $name, $table);
	$table = str_replace("!!!EMAIL!!!", $email, $table);
	$table = str_replace("!!!PHONE!!!", $phone, $table);
	$table = str_replace("!!!PUPPY!!!", $puppy, $table);
	$table = str_replace("!!!DATE!!!", $date, $table);
	$table = str_replace("!!!TIME!!!", $time, $table);
	$table = str_replace("!!!PRICE!!!", '$' . $price, $table);
	
	return $table;
}
?>