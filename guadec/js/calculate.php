
<?php
    include(__DIR__ . "/../pricing.php");

    $aResult = "";

    if( !isset($_POST['functionname']) ) { $aResult = 'error'; }

    if( !isset($_POST['arguments']) ) { $aResult = 'error'; }

    if( !($aResult == 'error')) {
       switch($_POST['functionname']) {
            case 'updateLunchTotal':
               # nb_lunch, lunch_checked
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) ) {
                   $aResult = 'error';
               }
               else {
                  if ($_POST['arguments'][1] == 'false'){
                   $aResult = 0;
                 }
                 else{
                   $aResult = lunchPrice((int)($_POST['arguments'][0]));
                 }
               }
               break;
            case 'updateAccomTotal' :
               # arrive, depart, accommodation_checked, room_type
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) ) {
                   $aResult = 'error';
               }
               else {
                   if ($_POST['arguments'][2] == 'false') {
                     $aResult = 0;
                   }
                   else{
                     $nights = dayParser($_POST['arguments'][0], $_POST['arguments'][1]);
                     $aResult = accomPrice($nights, $_POST['arguments'][3]){
                  }
               }
               break;
	    case 'updateTotal' :
                # nb_lunch, arrive, depart, fee, lunch_checked, accommodation_checked, room_type
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 7) ) {
                    $aResult = 'error';
                }
		else {
                   $nights = dayParser($_POST['arguments'][1], $_POST['arguments'][2]);
		   $lunch = lunchPrice((int)($_POST['arguments'][0]));
		   $accom = accomPrice($nights, $_POST['arguments'][6]);
		   $fee = (int)($_POST['arguments'][3]);
                   if ($_POST['arguments'][4] == 'false'){
                     $lunch = 0;
                   }
                   if ($_POST['arguments'][5] == 'false'){
                     $accom = 0;
                   }
                  $aResult = $accom + $lunch + $fee);
                  if ($total_days == "Incorrect dates"){
                      $aResult = $total_days;
                  }
                     
               }
               break;
	    case 'roomAvailable':
               # room_type
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 1 || ($_POST['arguments'][0] != 'single' && $_POST['arguments'][0]) != 'double') ) {
                   $aResult = 'error';
	       }
               else {
                   $room_type = $_POST['arguments'][0];
                   $booked = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM wp_guadec2014_registrations WHERE accom = 'YES' AND room=%s", $room_type);
		   $total_beds = $room_type == 'single' ? 14 : 26;
                   $aResult = ($total_beds>$booked) ? 'true' : 'false'; 
               }
               break;
            default:
               $aResult = 'error';
               break;
        }

    }
    echo $aResult;
?>
