<?php
    define('single_fee', 37);
    define('double_fee', 32);
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
       return $diffDate;   
    }
    
    function accomPrice($nights, $room_type){
       if($nights == "Incorrect dates") {
          return $nights;
       }
       if ($room_type == 'single') {
          return single_fee * $nights;
       } elseif ($room_type == 'double') {
          return double_fee * $nights;
       }
       return "Invalid room type";
    }


    function lunchPrice($days){
       return $days * lunch_fee;
    }
?>
