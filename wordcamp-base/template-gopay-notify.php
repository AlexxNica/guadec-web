<?php
/*
Template Name: GoPay Notify
*/

require_once('registration.php');

if (is_post() || !check_gopay_params()) {
  error_page('spurious gopay notify page hit', get_site_url());
}

$reg = get_registration_data_for_payment_session_id($_GET['paymentSessionId']);

if ($reg->completed) {
  error_page("gopay notification for already paid registration $reg->ID", get_site_url());
}

maybe_finish_payment($reg);

?>
