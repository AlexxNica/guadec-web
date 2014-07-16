<?php
/*
PayPal Instant Notification Receiver Page
GUADEC 2014 Registration
*/
?>

<?php
// tell PHP to log errors to ipn_errors.log in this directory
ini_set('log_errors', true);

ini_set('error_log', 'ipn_errors.log');

// intantiate the IPN listener
include('ipnlistener.php');
$listener = new IpnListener();

// tell the IPN listener to not the PayPal test sandbox
$listener->use_sandbox = false;

// try to process the IPN POST
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}

// TODO: Handle IPN Response here

if ($verified) {
    // : Implement additional fraud checks and MySQL storage
   
    $errmsg = '';   // stores errors from fraud checks
    
    $customInfo = $_POST['custom']; // access custom information
    $csplit = explode('&',$customInfo);
    $cvar = array(); $i = 0;

    while(($i < 9) && ($csplit[$i] != null)){
    $dsplit =  explode('=',$csplit[$i]);
    $ckey = $dsplit[0];
    $cvalue = ($dsplit[1] == null)?'0': $dsplit[1];
    $cvar[$ckey] = $cvalue;
    $i = $i + 1;
    }    
    $headers = "From: GUADEC 2014 Registration Script <contact@guadec.org>\n";

    // 1. Make sure the payment status is "Completed" 
    if ($_POST['payment_status'] != 'Completed') { 
        $errmsg .= "'Payment status' does not match: ";
        $errmsg .= $_POST['payment_status']."\n";
        $errmsg .= $_POST['receiver_email']."\n";
        // simply ignore any IPN that is not completed
        exit(0); 
    }

    // 2. Make sure seller email matches your primary account email.
    if ($_POST['receiver_email'] !='guadec@gnome.org') {
        $errmsg .= "'receiver_email' does not match: ";
        $errmsg .= $_POST['receiver_email']."\n";
    }
    
    // 3. Make sure the amount(s) paid match
    $total_fee = floatval($cvar['totalfee']);
    if ($_POST['mc_gross'] != $total_fee) {
        $errmsg .= "Total fee does not match (expected $total_fee): ";
        $errmsg .= $_POST['mc_gross']."\n";
    }
    
    // // 4. Make sure the currency code matches
    // if ($_POST['mc_currency'] != 'EUR') {
    //     $errmsg .= "'mc_currency' does not match: ";
    //     $errmsg .= $_POST['mc_currency']."\n";
    // }

    // TODO: Check for duplicate user_id
    $reg_email = $cvar['email'];
    if(empty($reg_email)){
        $errmsg .= "No Valid user email-address found\n";
    }        
    if (!empty($errmsg)) {
        $status = "FraudCheck";
        $upipn = $listener->updateCompleted($reg_email,$status);
        error_log($upipn);

	$body = "Your payment was accepted but was not as expected and your registration will need manual validation.";
        mail($_POST['payer_email'], 'GUADEC-2014 Registration Payment: Waiting for Confirmation', $body, $headers);

        // manually investigate errors from the fraud checking
        $body = "Registration Payment Successful-with fraud warning: \n";
        $body .= "$errmsg\n\n";

        //append with the real payment details
        $body .= $cvar['name'];
        $body .= " with email ";
        $body .= $cvar['email'];
        $body .= $listener->getTextReport();
        error_log($body); // Transcript copy in the error log
        mail('zana@gnome.org, pterjan@gmail.com', 'Guadec Payment Fraud Warning (validation needed)', $body, $headers);
    } else {
        $body = $listener->updateCompleted($reg_email, 'Completed');
        $body .= "Registration Payment Successful for ";
        $body .= $cvar['name'];
        $body .= " with email ";
        $body .= $cvar['email'];
        mail($_POST['payer_email'], 'GUADEC-2014 Registration Payment Successful', $body, $headers);
        $body .= "Complete List of items that you have paid for\n";
        $email_content =
        "Name: " . $cvar['name'] . "\r\n".
        "Email: " . $cvar['email'] . "\r\n" .
        "Time: " . $cvar['time']. "\r\n".
        "Arrival: ". $cvar['arrive'] . "\r\n".
        "Departure: ". $cvar['depart'] . "\r\n".
        "Entry-Fee Paid: ". $cvar['entryfee'] ."\r\n".
        "Lunch-Fee Paid: ".$cvar['lunchfee']."\r\n".
        "Accomodation-Fee Paid: ".$cvar['accomfee']."\r\n".
        "Total Amount Paid: ".$cvar['totalfee']
        ;
        $body .= $email_content;
        mail($cvar['email'], 'GUADEC-2014 Registration Successful', $body, $headers);
        $body .= $listener->getTextReport();
        error_log($body);
    }
} 
else {
    // manually investigate the invalid IPN
    error_log($listener->getTextReport());
    mail($_POST['receiver_email'], 'Invalid IPN', $listener->getTextReport());
}

?>
