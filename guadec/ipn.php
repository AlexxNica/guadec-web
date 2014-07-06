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
    exit(0);
}

// TODO: Handle IPN Response here

if ($verified) {
    // TODO: Implement additional fraud checks and MySQL storage

    $errmsg = '';   // stores errors from fraud checks
    
    // 1. Make sure the payment status is "Completed" 
    if ($_POST['payment_status'] != 'Completed') { 
        $errmsg .= "'Payment status' does not match: ";
        $errmsg .= $_POST['receiver_email']."\n";
        // simply ignore any IPN that is not completed
        exit(0); 
    }

    // 2. Make sure seller email matches your primary account email.
    if ($_POST['receiver_email'] !='saumya.zero-facilitator@gmail.com') {
        $errmsg .= "'receiver_email' does not match: ";
        $errmsg .= $_POST['receiver_email']."\n";
    }
    
    // 3. Make sure the amount(s) paid match
    if ($_POST['mc_gross'] <= '0.0') {
        $errmsg .= "'mc_gross' does not match: ";
        $errmsg .= $_POST['mc_gross']."\n";
    }
    
    // 4. Make sure the currency code matches
    if ($_POST['mc_currency'] != 'EUR') {
        $errmsg .= "'mc_currency' does not match: ";
        $errmsg .= $_POST['mc_currency']."\n";
    }

    // TODO: Check for duplicate user_id
    
    if (!empty($errmsg)) {
    
        // manually investigate errors from the fraud checking
        $body = "IPN failed fraud checks: \n$errmsg\n\n";
        $body .= $listener->getTextReport();

        $body .= "Registration Payment--Detected Fraud--To be verified\n";
        $body .= "If not verified, payment is refunded in full amounts to the account of the holder\n";
        //append with the real payment details
        $body .= $_POST['custom'];
        error_log($body);
        mail($_POST['receiver_email'], 'IPN Fraud Warning', $body);
        mail($_POST['payer_email'], 'GUADEC 2014 Registration-Payment to be verified', $body);
        
        
    } else {
        $body .= $listener->getTextReport();
        $body .= "Registration Payment Successful for ";
        $body .= $_POST['custom'];
        error_log($body);
        mail($_POST['receiver_email'], 'GUADEC 2014 Registration Successful', $body);
        mail($_POST['payer_email'], 'GUADEC 2014 Registration Successful', $body);
    
    }
} 
else {
    // manually investigate the invalid IPN

    error_log($listener->getTextReport());
    mail($_POST['receiver_email'], 'Invalid IPN', $listener->getTextReport());
}

?>
