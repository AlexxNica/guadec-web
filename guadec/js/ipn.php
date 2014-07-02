<?php
/*
PayPal Instant Notification Receiver Page
GUADEC 2014 Registration
*/
?>
<?php
// tell PHP to log errors to ipn_errors.log in this directory
ini_set('log_errors', true);
//ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

ini_set('error_log', 'ipn_errors.log');

// intantiate the IPN listener
include('ipnlistener.php');
$listener = new IpnListener();

// tell the IPN listener to use the PayPal test sandbox
$listener->use_sandbox = true;

// try to process the IPN POST
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    error_log("fdfds");
    exit(0);
}

// TODO: Handle IPN Response here

if ($verified) {
    // TODO: Implement additional fraud checks and MySQL storage

    $errmsg = '';   // stores errors from fraud checks
    
    // 1. Make sure the payment status is "Completed" 
    if ($_POST['payment_status'] != 'Completed') { 
        // simply ignore any IPN that is not completed
        exit(0); 
    }

    // 2. Make sure seller email matches your primary account email.
    if ($_POST['receiver_email'] !='saumya.zero-felicitator@gmail.com') {
        $errmsg .= "'receiver_email' does not match: ";
        $errmsg .= $_POST['receiver_email']."\n";
    }
    
    // 3. Make sure the amount(s) paid match
    if ($_POST['mc_gross'] != '9.99') {
        $errmsg .= "'mc_gross' does not match: ";
        $errmsg .= $_POST['mc_gross']."\n";
    }
    
    // 4. Make sure the currency code matches
    if ($_POST['mc_currency'] != 'GBP') {
        $errmsg .= "'mc_currency' does not match: ";
        $errmsg .= $_POST['mc_currency']."\n";
    }

    // TODO: Check for duplicate user_id
    
    if (!empty($errmsg)) {
    
        // manually investigate errors from the fraud checking
        $body = "IPN failed fraud checks: \n$errmsg\n\n";
        $body .= $listener->getTextReport();
        error_log($body);
	error_log($_POST['custom']);
        mail($_POST['receiver_email'], 'IPN Fraud Warning', $body);
        
    } else {
	error_log($_POST['custom']);
        mail($_POST['receiver_email'], 'Registration Successful', $body);
        mail($_POST['payer_email'], 'Registration Successful', $body);
    
        // TODO: process order here
    }
} else {
    // manually investigate the invalid IPN

    error_log($listener->getTextReport());

    error_log("2");
    mail('saumya.zero@gmail.com', 'Invalid IPN', $listener->getTextReport());

    error_log($listener->getTextReport());
}

?>
