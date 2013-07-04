<?php
/*
Template Name: GoPay Notify
*/

require_once('registration.php');

if (is_post() || !check_gopay_params()) {
  error_log('spurious gopay notify page hit');
  exit;
}

$reg = get_registration_data_for_payment_session_id($_GET['paymentSessionId']);

if ($reg->completed) {
  error_log("gopay notification for already paid registration $reg->ID");
  exit;
}

maybe_finish_payment($reg, true);

?>
