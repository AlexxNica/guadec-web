<?php
/*
Template Name: Registration
*/

define('GOID', 8881354867);
define('SECURE_KEY', "BcGk8p8EWRhNwHQnz5wpkZFY");

require_once(WP_CONTENT_DIR . '/gopay-api/gopay_config.php');
require_once(WP_CONTENT_DIR . '/gopay-api/gopay_helper.php');
require_once(WP_CONTENT_DIR . '/gopay-api/gopay_soap.php');
GopayConfig::init(GopayConfig::TEST);

class ValidationInfo {
  var $valid = true;
  var $registration_code = false;
  var $tshirt_gender = false;
  var $check_in_out_dates = false;
  var $gender = false;
  var $room = false;
}

class Registration {
  var $ID = null;
  var $user_ID = null;

  var $registration_type = 'hobbyist';
  var $registration_code = null;

  var $tshirt = false;
  var $tshirt_gender = null;
  var $tshirt_size = null;
  var $foundation = null;

  var $lunch = false;
  var $vegetarian = null;

  var $dormitory = false;
  var $breakfast = null;
  var $check_in_date = null;
  var $check_out_date = null;
  var $gender = null;
  var $room = null;
  var $roommate = null;

  var $completed = false;
  var $total_payed = null;

  var $payment_session_id = null;

  var $notes = null;

  var $tax_doc_number = null;
  var $tax_document = null;

  function get_amount_to_pay() {
    $total = 0;

    switch ($this->registration_type) {
      case 'professional';
        $total += 3750;
        break;
      case 'hobbyist';
        $total += 500;
        break;
      case 'student';
        $total += 250;
        break;
    }

    if ($this->tshirt) {
      if ($this->foundation) {
        $total += 300;
      } else {
        $total += 400;
      }
    }

    if ($this->lunch) {
      $total += 440;
    }

    return $total;
  }

  function validate() {
    $info = new ValidationInfo();

    if ($this->registration_type != 'code') {
      $this->registration_code = null;
    } else if (!validate_code($this->registration_code)) {
      $info->valid = false;
      $info->registration_code = true;
    }

    if ($this->tshirt) {
      if (!($this->tshirt_gender == 'male' || $this->tshirt_gender == 'female')) {
        $info->valid = false;
        $info->tshirt_gender = true;
      }
    } else {
      $this->tshirt_gender = null;
      $this->tshirt_size = null;
      $this->foundation = null;
    }

    if (!$this->lunch) {
      $this->vegetarian = null;
    }

    if ($this->dormitory) {
      try {
        $in = new DateTime($this->check_in_date);
        $out = new DateTime($this->check_out_date);
        $delta = $out->diff($in);
        if ($delta->invert == 0 || $delta->d == 0) {
          throw new Exception();
        }
        $delta = $out->diff(new DateTime('2013-08-18'));
        if ($delta->invert == 1) {
          throw new Exception();
        }
        $delta = $in->diff(new DateTime('2013-07-13'));
        if ($delta->invert == 0) {
          throw new Exception();
        }
        $this->check_in_date = $in->format('Y-m-d');
        $this->check_out_date = $out->format('Y-m-d');
      } catch (Exception $e) {
        $info->valid = false;
        $info->check_in_out_dates = true;
      }

      if (!($this->gender == 'male' || $this->gender == 'female')) {
        $info->valid = false;
        $info->gender = true;
      }
      if (!($this->room == 'single' || $this->room == 'double')) {
        $info->valid = false;
        $info->room = true;
      }
      if ($this->room == 'single') {
        $this->roommate = null;
      }
    } else {
      $this->breakfast = null;
      $this->check_in_date = null;
      $this->check_out_date = null;
      $this->gender = null;
      $this->room = null;
      $this->roommate = null;
    }

    return $info;
  }

  function insert() {
    global $wpdb;
    global $current_user;

    if ($this->validate()->valid == false) {
      error_page("data validation failed on insert for user $current_user->ID");
    }

    $this->user_ID = $current_user->ID;
    $this->completed = false;
    $this->total_payed = $this->get_amount_to_pay();

    $inserted = $wpdb->insert(get_table_name(), array(
      'user_ID' => $this->user_ID,

      'registration_type' => $this->registration_type,
      'registration_code' => $this->registration_code,

      'tshirt' => $this->tshirt,
      'tshirt_gender' => $this->tshirt_gender,
      'tshirt_size' => $this->tshirt_size,
      'foundation' => $this->foundation,

      'lunch' => $this->lunch,
      'vegetarian' => $this->vegetarian,

      'dormitory' => $this->dormitory,
      'breakfast' => $this->breakfast,
      'check_in_date' => $this->check_in_date,
      'check_out_date' => $this->check_out_date,
      'gender' => $this->gender,
      'room' => $this->room,
      'roommate' => $this->roommate,

      'completed' => $this->completed,
      'total_payed' => $this->total_payed,

      'notes' => $this->notes
    ));

    if (!$inserted) {
      error_page("couldn't insert registration for user $this->user_ID");
    }

    $this->ID = $wpdb->insert_id;
  }

  function update_secrets($payment_session_id) {
    global $wpdb;

    $updated = $wpdb->update(get_table_name(), array(
      'payment_session_id' => $payment_session_id
    ),
    array('ID' => $this->ID));

    if (!$updated) {
      error_page("couldn't update secrets for registration $this->ID");
    }

    $this->payment_session_id = $payment_session_id;
  }

  function delete() {
    global $wpdb;
    $table_name = get_table_name();

    $deleted = $wpdb->query($wpdb->prepare("delete from $table_name where ID = %d", $this->ID));

    if (!$deleted) {
      error_page("couldn't delete registration $this->ID");
    }
  }

  function set_completed() {
    global $wpdb;
    $table_name = get_table_name();

    $updated = $wpdb->update($table_name, array(
      'completed' => true
    ),
    array('ID' => $this->ID));

    if (!$updated) {
      error_page("couldn't set registration $this->ID as completed");
    }

    $this->completed = true;

    if ($this->total_payed == 0) {
      return;
    }

    $wpdb->query("lock tables $table_name write");

    $tax_doc_number_base = 20130000;
    $tax_doc_number = $wpdb->get_var("select max(tax_doc_number) from $table_name");
    if ($tax_doc_number == null || $tax_doc_number < $tax_doc_number_base) {
      $tax_doc_number = $tax_doc_number_base;
    }
    $tax_doc_number += 1;

    $date = new DateTime(null, new DateTimeZone('Europe/Prague'));
    $date = $date->format('d.m.Y');

    $text = "Zjednodušený daňový doklad č. (simplified tax document #): $tax_doc_number\n";
    $text .= "\nProdávající (vendor):\nLiberix, o.p.s.\nErbenova 270/2\n779 00 Olomouc\nDIČ (tax identification number): CZ26860015\n\n";
    $text .= "Předmět plnění (items purchased):\n";
    $text .= "1x conference fee - $this->registration_type\n";
    if ($this->tshirt) {
      $text .= "1x GUADEC t-shirt\n";
    }
    if ($this->lunch) {
      $text .= "4x lunch voucher\n";
    }
    $text .= "\nDatum vystavení (issued on): $date\n";
    $text .= "Cena včetně 21% DPH (price including 21% VAT): $this->total_payed Kč (CZK)\n";

    $updated = $wpdb->update($table_name, array(
      'tax_doc_number' => $tax_doc_number,
      'tax_document' => $text
    ),
    array('ID' => $this->ID));

    if (!$updated) {
      error_log("couldn't set the tax document for registration $this->ID");
    } else {
      $this->tax_doc_number = $tax_doc_number;
      $this->tax_document = $text;
    }

    $wpdb->query("unlock tables");
  }
}

function error_page($msg, $url = null)
{
  if ($url == null) {
    $url = get_permalink(get_page_by_title('Registration Error')->ID);
  }

  error_log($msg);
  header('Location: ' . $url);
  exit;
}

function is_post()
{
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function get_table_name()
{
  global $wpdb;
  return $wpdb->prefix . 'guadec_registration';
}

function get_codes_table_name()
{
  global $wpdb;
  return $wpdb->prefix . 'guadec_registration_codes';
}

function validate_code($code)
{
  global $wpdb;
  $table_name = get_table_name();
  $codes_table_name = get_codes_table_name();

  $wpdb->get_row("select * from $codes_table_name where code like '$code'");
  if ($wpdb->num_rows == 0) {
    return false;
  }

  $wpdb->get_row("select * from $table_name where registration_code like '$code'");
  if ($wpdb->num_rows > 0) {
    return false;
  }

  return true;
}

function complete_registration($reg)
{
  $reg->set_completed();
  send_tax_document($reg);
  send_registration_email($reg);
  header('Location: ' . get_permalink());
}

function do_payment($reg)
{
  global $current_user;

  $reg->insert();
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

function finish_payment($reg)
{
  if (empty($_GET['paymentSessionId'])
  || empty($_GET['targetGoId'])
  || empty($_GET['orderNumber'])
  || empty($_GET['encryptedSignature'])) {
    error_page("finishing registration $reg->ID without gopay GET arguments");
  }

  $returnedPaymentSessionId = $_GET['paymentSessionId'];
  $returnedGoId = $_GET['targetGoId'];
  $returnedOrderNumber = $_GET['orderNumber'];
  $returnedEncryptedSignature = $_GET['encryptedSignature'];

  if ($returnedOrderNumber != $reg->ID
  ||  $returnedPaymentSessionId != $reg->payment_session_id) {
    error_page("got wrong secrets from gopay to finish registration $reg->ID");
  }

  try {
    GopayHelper::checkPaymentIdentity((float)$returnedGoId,
                                      (float)$returnedPaymentSessionId,
                                      null,
                                      $returnedOrderNumber,
                                      $returnedEncryptedSignature,
                                      (float)GOID,
                                      $reg->ID,
                                      SECURE_KEY);
    $result = GopaySoap::isPaymentDone((float)$returnedPaymentSessionId,
                                       (float)GOID,
                                       $reg->ID,
                                       $reg->total_payed * 100,
                                       'CZK',
                                       'GUADEC 2013',
                                       SECURE_KEY);
    if ($result["sessionState"] == GopayHelper::PAID
    || $result["sessionState"] == GopayHelper::AUTHORIZED) {
      complete_registration($reg);
    } else {
      $reg->delete();
      error_page("payment for registration $reg->ID unsuccessful: " . $result["sessionState"]);
    }
  } catch (Exception $e) {
    error_page($e->getMessage());
  }
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

  echo "<p>$current_user->first_name $current_user->last_name</p>";
  echo "<form action=$this_url method=post>";
  include 'registration-form.php';

  if (is_post() && $info->valid) {
    $amount = $reg->get_amount_to_pay();
    echo "<input type=submit name=update value='Update'/>";
    echo "<span style='margin: 0 3em'>Amount to pay: <strong>$amount CZK</strong></span>";
    echo "<input type=submit name=finish value='Finish'/>";
  } else {
    echo "<input type=submit name=submit value='Submit'/>";
  }

  echo "</form>";
}

function fill_registration_data_from_post($reg)
{
  if (!empty($_POST['registration_type'])) {
    $reg->registration_type = htmlspecialchars($_POST['registration_type']);
  }
  if (!empty($_POST['registration_code'])) {
    $reg->registration_code = htmlspecialchars($_POST['registration_code']);
  }
  if (!empty($_POST['tshirt'])) {
    $reg->tshirt = (bool)$_POST['tshirt'];
  }
  if (!empty($_POST['tshirt_gender'])) {
    $reg->tshirt_gender = htmlspecialchars($_POST['tshirt_gender']);
  }
  if (!empty($_POST['tshirt_size'])) {
    $reg->tshirt_size = htmlspecialchars($_POST['tshirt_size']);
  }
  if (!empty($_POST['foundation'])) {
    $reg->foundation = (bool)$_POST['foundation'];
  }
  if (!empty($_POST['lunch'])) {
    $reg->lunch = (bool)$_POST['lunch'];
  }
  if (!empty($_POST['vegetarian'])) {
    $reg->vegetarian = (bool)$_POST['vegetarian'];
  }
  if (!empty($_POST['dormitory'])) {
    $reg->dormitory = (bool)$_POST['dormitory'];
  }
  if (!empty($_POST['breakfast'])) {
    $reg->breakfast = (bool)$_POST['breakfast'];
  }
  if (!empty($_POST['check_in_date'])) {
    $reg->check_in_date = htmlspecialchars($_POST['check_in_date']);
  }
  if (!empty($_POST['check_out_date'])) {
    $reg->check_out_date = htmlspecialchars($_POST['check_out_date']);
  }
  if (!empty($_POST['gender'])) {
    $reg->gender = htmlspecialchars($_POST['gender']);
  }
  if (!empty($_POST['room'])) {
    $reg->room = htmlspecialchars($_POST['room']);
  }
  if (!empty($_POST['roommate'])) {
    $reg->roommate = htmlspecialchars($_POST['roommate']);
  }
  if (!empty($_POST['notes'])) {
    $reg->notes = htmlspecialchars($_POST['notes']);
  }
}

function get_registration_data_from_request()
{
  $reg = new Registration();

  if (is_post()) {
    fill_registration_data_from_post($reg);
  }

  return $reg;
}

function get_registration_data_for_user_id($id)
{
  global $wpdb;
  $table_name = get_table_name();

  $reg = new Registration();
  $row = $wpdb->get_row("select * from $table_name where user_ID = $id");

  $reg->ID = $row->ID;
  $reg->user_ID = $row->user_ID;

  $reg->registration_type = $row->registration_type;
  $reg->registration_code = $row->registration_code;

  $reg->tshirt = $row->tshirt;
  $reg->tshirt_gender = $row->tshirt_gender;
  $reg->tshirt_size = $row->tshirt_size;
  $reg->foundation = $row->foundation;

  $reg->lunch = $row->lunch;
  $reg->vegetarian = $row->vegetarian;

  $reg->dormitory = $row->dormitory;
  $reg->breakfast = $row->breakfast;
  $reg->check_in_date = $row->check_in_date;
  $reg->check_out_date = $row->check_out_date;
  $reg->gender = $row->gender;
  $reg->room = $row->room;
  $reg->roommate = $row->roommate;

  $reg->completed = $row->completed;
  $reg->total_payed = $row->total_payed;

  $reg->payment_session_id = $row->payment_session_id;

  $reg->notes = $row->notes;

  $reg->tax_doc_number = $row->tax_doc_number;
  $reg->tax_document = $row->tax_document;

  return $reg;
}

function get_registration_confirmation($reg)
{
  global $current_user;
  $msg = "";

  $msg .= "<p>$current_user->first_name, you are successfully registered for GUADEC 2013.</p>";

  $msg .= "<p>Registration type: ";
  switch ($reg->registration_type) {
    case 'professional';
    $msg .= "Professional";
    break;
    case 'hobbyist';
    $msg .= "Hobbyist";
    break;
    case 'student';
    $msg .= "Student";
    break;
    case 'code';
    $msg .= "Sponsored";
    break;
  }
  $msg .= "</p>";

  if ($reg->tshirt || $reg->lunch) {
    $msg .= "<p>When picking your badge you will receive:";
    $msg .= "<ul>";

    if ($reg->tshirt) {
      $msg .= "<li>A ";
      if ($reg->tshirt_gender == 'male') {
        $msg .= "men's";
      } else {
        $msg .= "women's";
      }
      $msg .= " T-Shirt, size ";
      $msg .= strtoupper($reg->tshirt_size) . "</li>";
    }

    if ($reg->lunch) {
      $msg .= "<li>Vouchers for lunch at the venue for August 1, 2, 3 and 4</li>";
    }

    $msg .= "</ul></p>";
  }

  if ($reg->dormitory) {
    $msg .= "<p>We have reserved for you a $reg->room room at the Taufer dormitory for the period ";
    $msg .= "$reg->check_in_date to $reg->check_out_date";
    if ($reg->breakfast) {
      $msg .= ", including breakfast";
    }
    $msg .= "</p>";
  }

  return $msg;
}

function set_html_content_type()
{
  return 'text/html';
}

function send_registration_email($reg)
{
  global $current_user;

  add_filter('wp_mail_content_type', 'set_html_content_type');
  wp_mail($current_user->user_email, '[GUADEC 2013] Registration successful', get_registration_confirmation($reg));
  // reset content-type to to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
  remove_filter('wp_mail_content_type', 'set_html_content_type');
}

function send_tax_document($reg)
{
  global $current_user;

  if ($reg->tax_document == null || $reg->tax_doc_number == null) {
    return;
  }

  wp_mail($current_user->user_email, '[GUADEC 2013] Registration tax document', $reg->tax_document);
}


$structure = wcb_get('structure');
$structure->full_width_content();

get_header();

echo "<div id='container'>";
echo "<div id='content' role='main'>";
echo "<div class='page type-page status-publish hentry'>";
echo "<div id='entry-content'>";

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
    $reg = get_registration_data_for_user_id($current_user->ID);
    echo get_registration_confirmation($reg);
  } else if (!$pending_registration) {
    $reg = get_registration_data_from_request();
    $info = $reg->validate();

    if (is_post() && $info->valid && !empty($_POST['finish'])) {
      do_payment($reg);
    } else {
      put_registration_form($reg, $info);
    }
  } else if ($pending_registration) {
    $reg = get_registration_data_for_user_id($current_user->ID);

    if (is_post()) {
      error_page("finishing registration $reg->ID with a POST");
    } else {
      finish_payment($reg);
    }
  }
} else {
  wp_login_form();
  wp_register("Don't have a GUADEC account yet? ", "");
}

out:
echo "</div></div></div></div>";

get_sidebar();
get_footer();

?>
