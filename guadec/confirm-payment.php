<?php
/*
Template Name: Confirm Payment
*/
?>
<?php
 $application_submitted = false;
 $mailContent = '';
 $table_name = $wpdb->prefix .'guadec2014_registrations';

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

	$sponsor_check = ($_POST['sponsored'] == true)?"YES":"NO";
	$payment = ($tamount > 0)?"Pending":"NoPayment";
	$accom = ($_POST['accommodation'] == true)?"YES":"NO";

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
	if ($errors == false) {
		/* This variable not be changed: goes to a restricted field to Paypal API */
		$registerInfo = 
		"name=" . $name . "&".
		"email=" . $email . "&" .
		"time=" . date("Y-m-d H:i:s"). "&".
		"arrive=". $arrive . "&".
		"depart=". $depart . "&".
		"entryfee=". $entry ."&".
		"lunchfee=".$lamount."&".
		"accomfee=".$aamount."&".
		"totalfee=".$tamount
				;
		$mailContent .= $registerInfo;
		$subject = "GUADEC 2014 Registration";
		$headers = "From: GUADEC 2014 Registration Script <some-address@gnome.org>";

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
	<div class="section group"> "Invalid Submission. Please go through registration page first."</div>
<?php else: ?>
	<?php if ($errors == true): ?>
	<div class="section group"> "Invalid name or email. Please check."<a href="https://www.guadec.org/registration-form/"> Go back to Registration page</a></div>
	<?php else: ?>
		<?php //echo $registerInfo; ?>
		<div class="section group">
		<div class="col span_1_of_2">Name</div>
		<div class="col span_1_of_2"><?php echo $name;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Email</div>
		<div class="col span_1_of_2"><?php echo $email;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Sponsored</div>
		<div class="col span_1_of_2"><?php echo $sponsor_check;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Accommodation</div>
		<div class="col span_1_of_2"><?php echo $accom;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Arrival</div>
		<div class="col span_1_of_2"><?php echo $arrive;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Departure</div>
		<div class="col span_1_of_2"><?php echo $depart;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Lunch-Days</div>
		<div class="col span_1_of_2"><?php echo $lunch_days;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Dietary-Restrictions</div>
		<div class="col span_1_of_2"><?php echo $diet;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Student</div>
		<div class="col span_1_of_2"><?php echo $student;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Accommodation-Fee</div>
		<div class="col span_1_of_2">€<?php echo $aamount;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Lunch-Fee</div>
		<div class="col span_1_of_2">€<?php echo $lamount;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Entry-Fee</div>
		<div class="col span_1_of_2">€<?php echo $entry;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Total</div>
		<div class="col span_1_of_2">€<?php echo $tamount;?></div>
		</div>
		
		<?php if ($tamount > 0): ?>
			<div class="section_group>">Your details have been stored. Proceed to pay €<?php echo $tamount;?>.</div>
			<div class="col span_1_of_2">	
				<form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			    <input type="hidden" name="cmd" value="_xclick">
			    <input type="hidden" name="business" value="saumya.zero-facilitator@gmail.com">
			    <input type="hidden" name="currency_code" value="EUR">
			    <input type="hidden" name="item_name" value="Digital Download">
			    <input type="hidden" name="amount" value="<?php echo $tamount; ?>">
			    <!-- Redirect to thank you after successful payment -->
			    <input type="hidden" name="return" value=" https://www.guadec.org/thank-you">
			    <input type="hidden" name="custom" value="<?php echo $registerInfo; ?>">

				<!-- <Address of notification url. Can not be localhost	     -->
			    <input type="hidden" name="notify_url" value="http://www.guadec.org/wp-content/themes/guadec/ipn.php">

			    <!-- Redirect to thank you after cancelled payment -->
			    <input type="hidden" name="cancel_return" value="https://www.guadec.org/cancel-registration/">
			    
			    <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
				</form>
			</div>
		<?php else: ?>
			<div>Your details have been stored. An email confirming your registration will be sent to you shortly. Thank you.</div>
			<!-- Send a confirm registration mail to the registered -->
			<?php $mail = mail($email, $subject, $mailContent, $headers); ?>
			<?php if($mail): ?>
 				 <div>"Mail sent."</div>
			<?php else: ?>
  				 <div>"Mail sending failed."</div> 
  			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>	
</div>
<?php require_once("footer.php"); ?>
