<?php
/*
Template Name: Confirm Payment
*/
?>
<?php
 $application_submitted = false;
 $mailContent = '';
 $table_name = $wpdb->prefix .'registration';

global $wpdb;
$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  timeofregistration datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name tinytext NOT NULL,
  email VARCHAR(55) NOT NULL,
  accom VARCHAR(10) DEFAULT 'NO' NOT NULL,
  arrive VARCHAR(20) DEFAULT 'NA' NOT NULL,
  depart VARCHAR(20) DEFAULT 'NA' NOT NULL,
  sponsored VARCHAR(10) DEFAULT 'NO' NOT NULL,
  lunchdays VARCHAR(60) DEFAULT 'NA',
  dietrestrict VARCHAR(60),
  entryfee real DEFAULT 0 NOT NULL,
  lunchfee real DEFAULT 0 NOT NULL,
  accomfee real DEFAULT 0 NOT NULL,
  totalfee real DEFAULT 0 NOT NULL,
  irc text,
  gender text,
  country text,
  student VARCHAR(10) DEFAULT 'NO',
  payment VARCHAR(10) DEFAULT 'NoPayment',
  bday date,
  UNIQUE KEY id (id)
);";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

if (!empty($_POST)) {
	$application_submitted = true;
	$errors = false;

	$name = trim(stripslashes($_POST['contact_name']));
	$email = trim(stripslashes($_POST['contact_email']));
	$irc = (isset($_POST['irc']))?(trim(stripslashes($_POST['irc']))) : 'NA';
	$gender = (isset($_POST['contact_gender']))?(trim(stripslashes($_POST['contact_gender']))) : 'NA';
	$country = (isset($_POST['contact_country']))?(trim(stripslashes($_POST['contact_country']))) : 'NA';
	$diet = (isset($_POST['diet']))?(trim(stripslashes($_POST['diet']))) : 'NA';
	
	$entry = (isset($_POST['entry-fee']))?($_POST['entry-fee']):'0';
	$lamount = (isset($_POST['lfee']))?($_POST['lfee']):'0';
	$aamount = (isset($_POST['lfee']))?($_POST['afee']):'0';
	$tamount = (isset($_POST['lfee']))?($_POST['tfee']):'0';
	$bday = (isset($_POST['bday']))?($_POST['bday']):'NA';
	$student =  ($_POST['student'] == true)?"YES":"NA";

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
	$lunch_days = "";
	$x = 0;
	if(isset($_POST['lunch'])){
		foreach($_POST['lentry-fee'] as $value){
			$lunch_days = $lunch_days." ".$value;
			$x = $x + 1;
		}
	}
	$sponsor_check = ($_POST['sponsored'] == true)?"YES":"NO";
	$payment = ($tamount > 0)?"Pending":"NoPayment";
	$accom = ($_POST['accommodation'] == true)?"YES":"NO";
	if ($errors == false) {
		/* This variable not be changed: goes to a restricted field to Paypal API */
		$registerInfo = 
		"Name: " . $name . "\n".
		"Email: " . $obfuscated_email . "\n" .
		"Arrive :  ". $arrive . "\n".
		"Depart:  ". $depart . "\n".
		"Sponsored: ". $sponsor_check . "\n".
		"Lunch Days: ". $x. "\n".
		"Entry Fee: ". $entry ."\n".
		"Lunch Fee: ".$lamount."\n".
		"Accom Fee: ".$aamount."\n".
		"Total Fee: ".$tamount."\n"
		;
		$mailContent .= $registerInfo;
	
	//	$table_name = "registered";
  		$wpdb->insert($table_name, array('timeofregistration' => date("Y-m-d H:i:s"),
  				 'name' => $name,
  				 'email' => $email,
  				 'accom' => $accom,
  				 'arrive' => $arrive,
  				 'depart' => $depart,
  				 'sponsored' => $sponsor_check,
  				 'lunchdays' => $lunch_days,
  				 'dietrestrict' => $diet,
  				 'entryfee' => $entry,
  				 'lunchfee' => $lamount,
  				 'accomfee' => $aamount,
  				 'totalfee' => $tamount,
  				 'irc' => $irc,
  				 'gender' => $gender,
  				 'country' => $country,
  				 'payment' => $payment,
  				 'student' => $student,
  				 'bday' => $bday)); 	
	}
}
	
?>
<?php require_once("header.php"); ?>

<div>
<?php if(!($application_submitted == true)): ?>
	<div> "Invalid Submission. Please go through registration page first."</div>
<?php else: ?>
	<?php if ($errors == true): ?>
	<div> "Invalid name or email. Please check."<a href="http://localhost/wordpress/?page_id=4787"> Go back to Registration </a>
	<?php else: ?>
		<?php echo $registerInfo; ?>
		<?php if ($tamount > 0): ?>
			<div>Your details have been stored. Proceed to pay.</div>
				
			<form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
		    <input type="hidden" name="cmd" value="_xclick">
		    <input type="hidden" name="business" value="saumya.zero-facilitator@gmail.com">
		    <input type="hidden" name="currency_code" value="EUR">
		    <input type="hidden" name="item_name" value="Digital Download">
		    <input type="hidden" name="amount" value="<?php echo $tamount; ?>">
		    <!-- Redirect to thank you after successful payment -->
		    <input type="hidden" name="return" value="http://localhost/wordpress/?page_id=4823">
		    <input type="hidden" name="custom" value="<?php echo $registerInfo; ?>">

			<!-- <Address of notification url. Can not be localhost	     -->
		    <input type="hidden" name="notify_url" value="http://web.iiit.ac.in/~saumya.dwivedi/test/ipn.php">

		    <!-- Redirect to thank you after cancelled payment -->
		    <input type="hidden" name="cancel_return" value="http://localhost/wordpress/?page_id=4823">
		    
		    <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
			</form>
		<?php else: ?>
			<div>Your details have been stored. An email confirming your registration will be sent to you shortly. Thank you.</div>
			<!-- Send a confirm registration mail to the registered -->
			<?php mail($email, $mailContent); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>	
</div>
<?php require_once("footer.php"); ?>
