
<?php

    define('day_fee', 25);
    define('lunch_fee', 8.5);

    function dayParser($arrive_string, $depart_string){
       $result = ""; 
       $a = explode('-', $arrive_string);
       $b = explode('-', $depart_string);
       $aDate = substr($a[1],3);
       $bDate = substr($b[1],3);
       $aMon = substr($a[1],0,3);
       $bMon = substr($b[1],0,3);

       $aDate = (int)$aDate;
       $bDate = (int)$bDate;
       
       if ($aMon == $bMon){
        if ($aDate > $bDate){
          $result = "Incorrect dates";
          return $result;
        }
        else {
          $diffDate = ($bDate - $aDate);
        }
       }
       else{
         $diffDate = (31 - $aDate) + 1;
       }
      if ($diffDate == 0){
        $diffDate = 1;
      }
       return $diffDate;   
    }
    
    $aResult = "";

    if( !isset($_POST['functionname']) ) { $aResult = 'error'; }

    if( !isset($_POST['arguments']) ) { $aResult = 'error'; }

    if( !($aResult == 'error')) {
       switch($_POST['functionname']) {
            case 'updateLunchTotal':
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 2) ) {
                   $aResult = 'error';
               }
               else {
                  if ($_POST['arguments'][1] == 'false'){
                   $aResult = 0;
                 }
                 else{
                   $aResult = (int)($_POST['arguments'][0]) * lunch_fee;
                 }
               }
               break;
            case 'updateAccomTotal' :
               if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 4) ) {
                   $aResult = 'error';
               }
               else {
                   if (($_POST['arguments'][2] == 'false') || ($_POST['arguments'][3] == 'true')) {
                     $aResult = 0;
                   }
                   else{ 
                     $total_days = dayParser($_POST['arguments'][0], $_POST['arguments'][1]);
                     $aResult = (int)($total_days) * day_fee;
                     if ($total_days == "Incorrect dates"){
                      $aResult = $total_days;
                     }
                     
                  }
               }
               break;
            case 'updateTotal' :
                if( !is_array($_POST['arguments']) || (count($_POST['arguments']) < 7) ) {
                    $aResult = 'error';
                }
                else {
                   $total_days = dayParser($_POST['arguments'][1], $_POST['arguments'][2]);
                   $lunch = (int)($_POST['arguments'][0]) * lunch_fee;
                   $accom = (int)($total_days) * day_fee;
                   if ($_POST['arguments'][4] == 'false'){
                     $lunch = 0;
                   }
                   if ($_POST['arguments'][5] == 'false'){
                     $accom = 0;
                   }
                   if(($_POST['arguments'][6] == 'true')){ //&& ($_POST['arguments'][5] == 'true')) {
                     $accom = 0;
                   }
                  $aResult = $accom + $lunch + (int)($_POST['arguments'][3]);
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