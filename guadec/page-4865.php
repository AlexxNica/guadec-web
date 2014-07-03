<?php

$application_submitted = false;

if (!empty($_POST)) {

	$application_submitted = true;
	$errors = false;

	$name = trim(stripslashes($_POST['contact_name']));
	$email = trim(stripslashes($_POST['contact_email']));
	$irc = trim(stripslashes($_POST['irc']));
	$gender = trim(stripslashes($_POST['contact_gender']));
	$entry = trim(stripslashes($_POST['entry-fee']));
	$lamount = ($_POST['lfee']);
	$aamount = ($_POST['afee']);
	$tamount = ($_POST['tfee']);

	$obfuscated_email = str_replace("@", " AT ", $email);
	
	if (empty($name) || empty($email)) {
		$errors = true;
	}
	
	if(!isset($_POST['accommodation'])){
		$arrive = "NA";
		$depart = "NA";
	}
	else{
		$arrive = $_POST['arrival'];
		$depart = $_POST['departure'];
	}
	$lunch_days = " ";
	$x = 0;
	if(isset($_POST['lunch'])){
		foreach($_POST['lentry-fee'] as $value){
			$lunch_days = $lunch_days . " " . $value . "--\n";
			$x = $x + 1;
		}
	}
	$sponsor_check = ($_POST['sponsored'] == true)?"YES":"NA";
	if ($errors == false) {

		$registerInfo = "Contact Information\n" .
		"-------------------\r\n\r\n" .

		" Name: " . $name . "\n".
		" Email:     " . $obfuscated_email . "\n\n" .
		" IRC nick " . $irc . "\n".
		" Gender     " . $gender . "\n\n" .
		" [Registration received at " . date("D M j G:i:s Y") . " (Eastern time)]" . "\n\n".
		" Accomodation\n" ."\r\n".
		" Arrival     :  ". $arrive . "\r\n".
		" Departure     :  ". $depart . "\r\n".
		" Sponsorship Status   : ". $sponsor_check . "\r\n".
		" Lunch    : ".$x." days  [". $lunch_days ."]". "\r\n".
		" Entry Fees    : ". $entry ."\r\n".
		" Lunch Amount   : ".$lamount."\r\n".
		" Accomodation Amount   : ".$aamount."\r\n".
		" Total Amount   : ".$tamount."\r\n"
		;
	}

}

?>
<?php require_once("header.php"); ?>

<div>
<?php if(!($application_submitted == true)): ?>
	<div> "Invalid Submission. Please go through registration page first."</div>
<?php else: ?>
	<?php if ($errors == true): ?>
	<div> "Invalid name or email. Please check."<a href=""> Go back to Registration </a>
	<?php else: ?>	
		<form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
	    <input type="hidden" name="cmd" value="_xclick">
	    <input type="hidden" name="business" value="saumya.zero-facilitator@gmail.com">
	    <input type="hidden" name="currency_code" value="GBP">
	    <input type="hidden" name="item_name" value="Digital Download">
	    <input type="hidden" name="amount" value="<?php echo $tamount; ?>">
	    <input type="hidden" name="return" value="https://www.guadec.org/?page_id=1323&preview=true">
	    <input type="hidden" name="custom" value="<?php echo $registerInfo; ?>">
	    <input type="hidden" name="notify_url" value="http://web.iiit.ac.in/~saumya.dwivedi/test/ipn.php">
	    <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
		</form>
<?php $_POST['contact_name']; ?>

<?php echo $registerInfo; ?>
	<?php endif; ?>
<?php endif; ?>	
</div>
<?php require_once("footer.php"); ?>
