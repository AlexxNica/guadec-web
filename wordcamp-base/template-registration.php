<?php
/*
Template Name: Registration
*/

require_once('registration.php');

function do_payment($reg)
{
  global $current_user;

  $reg->insert($current_user->ID);
  if ($reg->total_payed == 0) {
    complete_registration($reg);
    exit;
  }

  try {
    $paymentSessionId = GopaySoap::createPayment((float)GOID,
                                                 'GUADEC 2013',
                                                 $reg->total_payed * 100,
                                                 'CZK',
                                                 $reg->ID,
                                                 get_permalink(),
                                                 get_permalink(),
                                                 array('eu_gp_u'), // VISA and MasterCard only
                                                 'eu_gp_u', // default payment method
                                                 SECURE_KEY,
                                                 $current_user->user_firstname,
                                                 $current_user->user_lastname,
                                                 'City',
                                                 'Street',
                                                 'Postal code',
                                                 'Country code',
                                                 $current_user->user_email,
                                                 'Phone nr.',
                                                 null,
                                                 null,
                                                 null,
                                                 null,
                                                 'en');
  } catch (Exception $e) {
    error_page($e->getMessage());
  }
  $encryptedSignature = GopayHelper::encrypt(
    GopayHelper::hash(
      GopayHelper::concatPaymentSession((float)GOID,
                                        (float)$paymentSessionId,
                                        SECURE_KEY)
    ), SECURE_KEY);

  $reg->update_secrets($paymentSessionId);

  header('Location: ' . GopayConfig::fullIntegrationURL() . "?sessionInfo.targetGoId=" . GOID . "&sessionInfo.paymentSessionId=" . $paymentSessionId . "&sessionInfo.encryptedSignature=" . $encryptedSignature);
  exit;
}

function put_registration_form($reg, $info)
{
  global $current_user;
  $this_url = get_permalink();
  $jquery_url = content_url() . '/jquery-ui-1.10.3';

  if (strlen(trim($current_user->first_name)) < 1
  ||  strlen(trim($current_user->last_name)) < 1) {
    $url = get_edit_user_link();
    echo "Please fill in your first and last name on your <a href=$url>profile</a> before registering.";
    return;
  }

  echo "<p><strong>$current_user->first_name</strong>, it's great that you are coming to GUADEC!</p>";
  echo "<form action=$this_url method=post>";
  include 'registration-form.php';

  if (is_post() && $info->valid) {
    $amount = $reg->get_amount_to_pay();
    echo "<input type=submit name=update value='Update'/>";
    echo "<span style='margin: 0 3em'>Amount to pay: <strong>$amount CZK</strong></span>";
    echo "<input type=submit name=finish value='Finish'/>";
    echo "<div style='font-size: 80%; line-height: 130%; margin: 1em 0 1em' >" .
         "• You'll be taken to gopay.cz, a Czech on-line payments service " .
         "whose terms of contract, protection principles of personal data " .
         "privacy and AML rules are available in Czech only. If you are not " .
         "confortable with this and don't want to rely on an on-line " .
         "translation service please contact us at the above mentioned address." .
         "</div>";
  } else {
    echo "<input type=submit name=submit value='Submit'/>";
  }

  echo "</form>";
}

function put_header()
{
  $structure = wcb_get('structure');
  $structure->full_width_content();

  get_header();

  echo "<div id='container'>";
  echo "<div id='content' role='main'>";
  echo "<div class='page type-page status-publish hentry'>";
  echo "<div id='entry-content'>";
}


if (is_user_logged_in()) {
  global $wpdb;
  $table_name = get_table_name();

  global $current_user;
  get_currentuserinfo();

  $u = wp_get_current_user();
  foreach ($u->roles as $r) {
    if ($r != 'registration') {
      echo "<p>Registration isn't available at this time.</p>";
      goto out;
    }
  }

  $completed = (bool)$wpdb->get_var("select completed from $table_name where user_ID = $current_user->ID");
  $pending_registration = ($wpdb->num_rows > 0 && !$completed);

  if ($completed) {
    put_header();
    $reg = get_registration_data_for_user_id($current_user->ID);
    echo get_registration_confirmation($reg, $current_user);
  } else if (!$pending_registration) {
    $reg = get_registration_data_from_request();
    $info = $reg->validate();

    if (is_post() && $info->valid && !empty($_POST['finish'])) {
      do_payment($reg);
    } else {
      put_header();
      put_registration_form($reg, $info);
    }
  } else if ($pending_registration) {
    $reg = get_registration_data_for_user_id($current_user->ID);

    if (is_post() || !check_gopay_params()) {
      put_header();
      echo "<p>Your payment is still pending.</p>";
      echo get_registration_data_string($reg);
    } else {
      maybe_finish_payment($reg);
    }
  }
} else {
  put_header();
  wp_login_form();
  wp_register("Don't have a GUADEC account yet? ", "");
}

out:
echo "</div></div></div></div>";

get_sidebar();
get_footer();

?>
