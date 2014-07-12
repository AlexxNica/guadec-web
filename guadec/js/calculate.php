
<?php
    include("../pricing.php");

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
                     $aResult = accomPrice($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2]){
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
            
            default:
               $aResult = 'error';
               break;
        }

    }
    echo $aResult;
?>
