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
  ispublic VARCHAR(3) DEFAULT 'NO' NOT NULL,
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
  room VARCHAR(7),
  roommate text,
  payment VARCHAR(10) DEFAULT 'NoPayment',
  vercode VARCHAR(20) DEFAULT '-none-',
  UNIQUE KEY id (id)
);";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
require_once('header.php');

if (!empty($_POST) || isset($_GET['payfor'])) {
    if(!empty($_POST['regid']))
    {
        $regid = $_POST['regid'];
        $email = $_POST['email'];
        // This is a current registration that wants to pay manually
        // Just generate a new verification code of 20 characters. No need to be cryptographically secure
        $newvercode = substr(md5(microtime()),rand(0,26),20);
        $updated = $wpdb->update($table_name,
                                 array('vercode' => $newvercode),
                                 array('id' => $regid,
                                       'payment' => 'Pending',
                                       'email' => $email));

        if($updated != 1)
        {
            ?>
            <div>Something went wrong...</div>
            <?php
        }
        else
        {

            $headers = "From: GUADEC 2014 Registration Script <contact@guadec.org>\n";
            $body = "Please verify your intent to pay at the GUADEC event by opening the following URL in your webbrowser: \n";
            $body .= "https://www.guadec.org/confirm-payment?regid=" . $regid . "&vercode=" . $newvercode;
            mail($email, 'GUADEC-2014 Registration Payment: Confirm intent to pay on-site', $body, $headers);

            ?>
            <div>
            An email has been sent to your email address requesting you to verify your intent to pay at the event.
            </div>
            <?php
        }
    }
    else
    {
        if(isset($_GET['payfor']))
        {
            $newreg = false;

            $payfor = $_GET['payfor'];
            $regid = $payfor;

            $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE id=%d",
                                                     $regid),
                                      ARRAY_A);


            $name = $results['name'];
            $email = $results['email'];
            $accom = $results['accom'];
            $room_type = $results['room'];
            $roommate = $results['roommate'];
            $arrive = $results['arrive'];
            $depart = $results['depart'];
            $sponsor_check = $results['sponsored'];
            $lunch_days = $results['lunchdays'];
            $diet = $results['dietrestrict'];
            $entry = $results['entryfee'];
            $lamount = $results['lunchfee'];
            $aamount = $results['accomfee'];
            $tamount = $results['totalfee'];
            $irc = $results['irc'];
            $gender = $results['gender'];
            $country = $results['country'];
            $payment = $results['payment'];

            if($payment != 'Pending')
            {
                print('Your payment is currently completed or marked as payment onsite.');
                require_once('footer.php');
                die();
            }
        }
        else
        {
            // This is a new registration
            $newreg = true;
            require_once('pricing.php');
            $application_submitted = true;
            $errors = false;

            $name = trim(sanitize_text_field($_POST['contact_name']));
            $email = trim(sanitize_text_field($_POST['contact_email']));
            $irc = (isset($_POST['irc']))?(trim(sanitize_text_field($_POST['irc']))) : 'NA';
            $gender = (isset($_POST['contact_gender']))?(trim(sanitize_text_field($_POST['contact_gender']))) : 'NA';
            $country = (isset($_POST['contact_country']))?(trim(sanitize_text_field($_POST['contact_country']))) : 'NA';
            $diet = (isset($_POST['diet']))?(trim(sanitize_text_field($_POST['diet']))) : 'NA';
            
            $entry = (isset($_POST['entry-fee']))?(intval($_POST['entry-fee'])):0;

            $public = isset($_POST['public'])?'YES':'NO';

            $obfuscated_email = str_replace("@", " AT ", $email);
            //check if the email already registered
            //TODO: Add the payment condition, once ipn works
            $repeat = $wpdb->get_var($wpdb->prepare(
                "select id from wp_guadec2014_registrations
                where email=%s and payment=%s",
                $email, 'Completed')
            );
            if (empty($name) || empty($email)) {
                $errors = true;
            }
            if(!empty($repeat)){
                $errors = true;
            }
            if(!isset($_POST['accommodation'])){
                $arrive = "NA";
                $depart = "NA";
            }
            else{
                if(!isset($_POST['room_type'])){
                    $errors = true;
                } else {
                    $room_type = $_POST['room_type'];
                    if ($room_type != 'single' && $room_type != 'double') {
                        $errors = true;
                    }
                                $booked = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_guadec2014_registrations WHERE accom = 'YES' AND room=%s", $room_type));
                        $total_beds = $room_type == 'single' ? 14 : 36;
                    if ($total_beds <= $booked) {
                        $errors = true;
                    }
                }
                $roommate = sanitize_text_field($_POST['roommate']);
                $arrive = sanitize_text_field($_POST['arrival']);
                $depart = sanitize_text_field($_POST['departure']);
            }

            $nights = dayParser($arrive, $depart);
            $aamount = accomPrice($nights, $room_type);

            $lunch_days = "";
            $x = 0;
            if(isset($_POST['lunch'])){
                foreach($_POST['lentry-fee'] as $value){
                    $lunch_days = $lunch_days." ".$value;
                    $x = $x + 1;
                }
            }
            $lamount = lunchPrice($x);
            $tamount = $aamount + $lamount + $entry;

            $sponsor_check = ($_POST['sponsored'] == true)?"YES":"NO";
            $payment = ($tamount > 0)?"Pending":"NoPayment";
            $accom = ($_POST['accommodation'] == true)?"YES":"NO";
            $headers = "From: GUADEC 2014 Registration Script <contact@guadec.org>\n";
                
            if ($errors == false) {
                $wpdb->insert($table_name, array('timeofregistration' => date("Y-m-d H:i:s"),
                         'name' => $name,
                         'email' => $email,
                         'accom' => $accom,
                         'room' => $room_type,
                         'roommate' => $roommate,
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
                         'ispublic' => $public));
            $regid = $wpdb->insert_id;
            $mailContent .= $registerInfo;
            $subject = "GUADEC 2014 Registration";
        }
    }
    /* This variable not be changed: goes to a restricted field to Paypal API */
    $registerInfo = 
    "regid=" . $regid . "&" .
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
	
?>

<div>
<?php if(!($application_submitted == true)): ?>
	<div> "Invalid Submission. Please go through registration page first."</div>
<?php else: ?>
	<?php if ($errors == true): ?>
	<div> "Invalid/Already used email or invalid room type. Please make sure you are not already registered. If you are, and want to make certain adjustments to your record, contact contact AT guadec.org OR "<a href="https://www.guadec.org/registration-form/"> Go back to Registration </a>
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
		<div class="col span_1_of_2">Room Type</div>
		<div class="col span_1_of_2"><?php echo $room_type;?></div>
		</div>
		<div class="section group">
		<div class="col span_1_of_2">Roommate</div>
		<div class="col span_1_of_2"><?php echo $roommate;?></div>
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
			<div>Your details have been stored. Proceed to pay €<?php echo $tamount;?>.</div>
				
			<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		    <input type="hidden" name="cmd" value="_xclick">
		    <input type="hidden" name="business" value="guadec@gnome.org">
		    <input type="hidden" name="currency_code" value="EUR">
		    <input type="hidden" name="item_name" value="GUADEC 2014">
		    <input type="hidden" name="amount" value="<?php echo $tamount; ?>">
		    <!-- Redirect to thank you after successful payment -->
		    <input type="hidden" name="return" value="https://www.guadec.org/thank-you/">
		    <input type="hidden" name="custom" value="<?php echo $registerInfo; ?>">

			<!-- <Address of notification url. Can not be localhost	     -->
		    <input type="hidden" name="notify_url" value="https://www.guadec.org/wp-content/themes/guadec/ipn.php">

		    <!-- Redirect to thank you after cancelled payment -->
		    <input type="hidden" name="cancel_return" value="https://www.guadec.org/cancel-registration/">
		    
		    <input type="image" src="http://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
			</form>

            <br>

            <form action="https://www.guadec.org/confirm-payment" method="post">
            <input type="hidden" name="regid" value="<?php print($wpdb->insert_id); ?>">
            <input type="hidden" name="email" value="<?php print($email); ?>">
            <input type="submit" value="Pay at event">
            </form>
		<?php else: ?>
			<div>Your details have been stored. An email confirming your registration will be sent to you shortly. Thank you.</div>
			<!-- Send a confirm registration mail to the registered -->

			$mail = mail($email, $subject, $mailContent, $headers);
			<?php if($mail): ?>
 				 <div>"Mail sent"</div>
			<?php else: ?>
  				 <div>"Mail sending failed."</div> 
  			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>	
</div>
<?php
    }
}
else // Not post
{
    if(isset($_GET['regid']))
    {
        // Verification email link
        $regid = $_GET['regid'];
        $vercode = $_GET['vercode'];

        $updated = $wpdb->update($table_name,
                                 array('payment' => 'OnSite'),
                                 array('payment' => 'Pending',
                                       'id' => $regid,
                                       'vercode' => $vercode));

        if($updated == false)
        {
            ?>
            <div>There was an error updating the database. Please try again later.</div>
            <?php
        }
        else if($updated == 0)
        {
            ?>
            <div>There was no such registration waiting for verification. Please make sure to copy the entire link.</div>
            <?php
        }
        else if($updated > 1)
        {
            // This should be impossible, but let's catch it anyway
            ?>
            <div>Something went wrong here....</div>
            <?php
        }
        else
        {
            $email = $wpdb->get_var($wpdb->prepare("SELECT email FROM " .  $table_name . " WHERE id=%d",
                                                   $regid));

            // We updated their registration. Lets send a verification email
            $headers = "From: GUADEC 2014 Registration Script <contact@guadec.org>\n";
            $body = "You have verified your intent to pay on-site at GUADEC. Please look in your earlier email for the exact amount to pay.";
            mail($email, 'GUADEC-2014 Registration Payment: On-site payment verified', $body, $headers);

            ?>
            <div>Your registration has been marked as payment on-site.</div>
            <?php
        }
    }
    else
    {
        ?>
        <div> Invalid Submission. Please go through registration page first.</div>
        <?php
    }
}

require_once("footer.php");
?>
