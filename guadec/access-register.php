<?php
/*
Template Name: Access Registration
*/
?>
<?php
// Restricted Admin Access. View Registration details.
global $wpdb;
function display_result($result){
	echo "<div style='overflow: auto'>";
	echo "<table class='regtable'><tr><th>ID</th><th>Name</th>
				<th>Irc</th><th>Email</th><th>Gender</th>
				<th>Accom</th><th>Arrival</th><th>Departure</th>
				<th>Sponsored</th><th>Country</th>
				<th>EntryFee</th>
				<th>LunchFee</th><th>AccomFee</th>
				<th>TotalFee</th>
				<th>Payment Status</th>
				<th>Public</th></tr>";
	foreach($result as $results){
		echo "<tr>";
		echo "<td>"; echo $results['id']; echo "</td>";
		echo "<td>"; echo $results['name']; echo "</td>";
		echo "<td>"; echo $results['irc']; echo "</td>";
		echo "<td>"; echo $results['email']; echo "</td>";
		echo "<td>"; echo $results['gender']; echo "</td>";
		echo "<td>"; echo $results['accom']; echo "</td>";
		echo "<td>"; echo $results['arrive']; echo "</td>";
		echo "<td>"; echo $results['depart']; echo "</td>";
		echo "<td>"; echo $results['sponsored']; echo "</td>";
		echo "<td>"; echo $results['country']; echo "</td>";
//		echo "<td>"; echo $results['lunchdays']; echo "</td>";
		echo "<td>"; echo $results['entryfee']; echo "</td>";
		echo "<td>"; echo $results['lunchfee']; echo "</td>";
		echo "<td>"; echo $results['accomfee']; echo "</td>";
		echo "<td>"; echo $results['totalfee']; echo "</td>";
		echo "<td>"; echo $results['payment']; echo "</td>";
		echo "<td>"; echo $results['ispublic']; echo "</td>";
		echo "</tr>";
	}
	echo "</table></div>";
}
function display_totals($result){
	$lunches = Array('lunch_saturday' => 0, 'lunch_sunday' => 0, 'lunch_monday' => 0, 'lunch_tuesday' => 0);
	$single_rooms = 0;
	$double_rooms = 0;
	foreach($result as $results) {
		foreach(explode(" ", $results['lunchdays']) as $lunch_day) {
			$lunches[$lunch_day] += 1;
		}
		if ($results['accom'] == 'YES') {
			if ($results['room'] == 'single') {
				$single_rooms += 1;
			} else {
				$double_rooms += 1;
			}
		}
	}
	echo "<h2>Lunch</h2><ul>";
	foreach($lunches as $day => $count) {
		echo "<li>$day: $count</li>";
	}
	echo "</ul>\n";
	echo "<h2>Accomodation</h2><ul>";
	echo "<li>Beds in single room: $single_rooms</li>";
	echo "<li>Beds in double room: $double_rooms</li>";
	echo "</ul>\n";
}

require_once("header.php");
 if( !(current_user_can( 'administrator' ) )){
 	echo "You are not authorised to view this page.";
 }
else{
	
	if(isset($_POST['viewtype']) && !empty($_POST['viewtype'])) {
	    $action = $_POST['viewtype'];
	   // $table_name = $wpdb->prefix .'guadec2014_registrations';
	    switch($action) {

	        case 'showall' :
	            $result = $wpdb->get_results('SELECT * FROM wp_guadec2014_registrations', ARRAY_A);
	        	echo display_result($result);
	        	break;
	     	case 'showcomplete' :
	    		$result = $wpdb->get_results("SELECT * FROM wp_guadec2014_registrations WHERE payment = 'Completed' OR payment ='NoPayment'", ARRAY_A);
	     		echo display_result($result);
	        	break;
		case 'showtotals' :
			$result = $wpdb->get_results("SELECT * FROM wp_guadec2014_registrations WHERE payment = 'Completed' OR payment ='NoPayment'", ARRAY_A);
			echo display_totals($result);
			break;
		    default :
	      	  	$result = 'Error';
	        break;
	    }
	}
	else{

		echo 'Select an option';
	}
		echo "<form method='post' action=''>
		<div><select id='viewtype' name='viewtype'>
     	<option value='showall' selected='selected'>Show All Entries</option>
     	<option value='showcomplete'>Only Completed Registration</option>
	<option value='showtotals'>Totals For Completed Registrations</option>
		</select></div>
		<input type='submit' value='Go' />
		</form>";

}
require_once("footer.php");
?>
