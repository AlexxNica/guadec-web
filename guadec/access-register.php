<?php
/*
Template Name: Access Registration
*/
?>
<?php
// Restricted Admin Access. View Registration details.
global $wpdb;
function display_result($result){
	$total_beds = 0;
	$total_sponsored = 0;
	$total_entryfee = 0;
	$total_lunchfee = 0;
	$total_accomfee = 0;
	$total_totalfee = 0;
	$total_confirmed = 0;
	$total_onsite = 0;
	$total_ispublic = 0;

	echo "<div>";
	echo "<table class='regtable'><thead><tr><th>ID</th><th>Name</th>
				<th>Irc</th><th>Email</th><th>Gender</th>
				<th>Accom</th><th>Arrival</th><th>Departure</th>
				<th>Sponsored</th><th>Country</th>
				<th>EntryFee</th>
				<th>LunchFee</th>
				<th>LunchDays</th>
				<th>AccomFee</th>
				<th>TotalFee</th>
				<th>Payment Status</th>
				<th>Public</th>
                <th>Update</th></tr></thead>";
	echo"<tbody>";
	foreach($result as $results){
		echo "<tr>";
		echo "<td>"; echo $results['id']; echo "</td>";
		echo "<td>"; echo $results['name']; echo "</td>";
		echo "<td>"; echo $results['irc']; echo "</td>";
		echo "<td>"; echo $results['email']; echo "</td>";
		echo "<td>"; echo $results['gender']; echo "</td>";
		echo "<td>"; echo $results['accom']; echo "</td>";
			if ($results['accom'] == 'YES') { $total_beds += 1;}
		echo "<td>"; echo $results['arrive']; echo "</td>";
		echo "<td>"; echo $results['depart']; echo "</td>";
		echo "<td>"; echo $results['sponsored']; echo "</td>";
			if ($results['sponsored'] == 'YES') { $total_sponsored += 1;}
		echo "<td>"; echo $results['country']; echo "</td>";
		echo "<td>"; echo $results['entryfee']; echo "</td>";
			$total_entryfee += $results['entryfee'];
		echo "<td>"; echo $results['lunchfee']; echo "</td>";
			$total_lunchfee += $results['lunchfee'];
		echo "<td>"; echo $results['lunchdays']; echo "</td>";
		echo "<td>"; echo $results['accomfee']; echo "</td>";
			$total_accomfee += $results['accomfee'];
		echo "<td>"; echo $results['totalfee']; echo "</td>";
			$total_totalfee += $results['totalfee'];
		echo "<td>"; echo $results['payment']; echo "</td>";
			if ($results['payment'] == 'Completed') { $total_confirmed += 1;}
			else if ($results['payment'] == 'OnSite') { $total_onsite += 1;}
		echo "<td>"; echo $results['ispublic']; echo "</td>";
			if ($results['ispublic'] == 'YES') { $total_ispublic += 1;}
        echo "<td>";
            if (($results['payment'] != 'Completed') && ($results['payment'] != 'Dupe')) {
                print('<form method="post" action="">
                <input type="hidden" name="regid" value="' . $results['id'] . '">
                <input type="hidden" name="newstatus" value="Dupe">
                <input type="hidden" name="viewtype" value="' . $_POST['viewtype'] . '">
                <input type="submit" value="Mark dupe">
                </form>');
                if($results['payment'] == 'Pending')
                {
                    print('<form method="post" action="">
                    <input type="hidden" name="regid" value="' . $results['id'] . '">
                    <input type="hidden" name="action" value="sendreminder">
                    <input type="hidden" name="newstatus" value="Reminded">
                    <input type="hidden" name="viewtype" value="' . $_POST['viewtype'] . '">
                    <input type="submit" value="Send payment reminder">
                    </form>');
                }
            }
            if ($results['payment'] == 'OnSite' || $results['payment'] == 'Pending') {
                print('<form method="post" action="">
                <input type="hidden" name="regid" value="' . $results['id'] . '">
                <input type="hidden" name="newstatus" value="OnSitePaid">
                <input type="hidden" name="viewtype" value="' . $_POST['viewtype'] . '">
                <input type="submit" value="Mark paid on-site">
                </form>');
            }
            if ($results['payment'] == 'Dupe') {
                print('<form method="post" action="">
                <input type="hidden" name="regid" value="' . $results['id'] . '">
                <input type="hidden" name="newstatus" value="Pending">
                <input type="hidden" name="viewtype" value="' . $_POST['viewtype'] . '">
                <input type="submit" value="Un-mark dupe">
                </form>');
            }
        echo "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo "<tfoot><tr><td>Total</td><td colspan='4'>"; echo count($result); echo " registered people</td>
				<td colspan='3'>"; echo $total_beds; echo " beds</td>
				<td>"; echo $total_sponsored; echo " sponsored</td><td></td>
				<td>Entry: "; echo $total_entryfee; echo " ("; echo $total_entryfee/count($result);echo " average)</td>
				<td colspan='2'>Lunch: "; echo $total_lunchfee; echo "</td>
				<td>Accommodation: "; echo $total_accomfee; echo "</td>
				<td>Total: "; echo $total_totalfee; echo "</td>
				<td>"; echo $total_confirmed; echo " confirmed, "; echo $total_onsite; echo " on site</td>
				<td>"; echo $total_ispublic; echo " public</td></tr></tfoot>";
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

function display_accommodation($result){
	$total_single = 0;
	$total_double = 0;
	$total_accomfee = 0;

	echo "<div>";
	echo "<table class='regtable'><thead><tr><th>ID</th><th>Name</th>
				<th>Email</th><th>Gender</th>
				<th>Arrival</th><th>Departure</th>
				<th>Room type</th>
				<th>Room mate</th>
				<th>AccomFee</th>
				<th>Payment Status</th>
				</tr></thead>";
	echo"<tbody>";
	foreach($result as $results){
		echo "<tr>";
		echo "<td>"; echo $results['id']; echo "</td>";
		echo "<td>"; echo $results['name']; echo "</td>";
		echo "<td>"; echo $results['email']; echo "</td>";
		echo "<td>"; echo $results['gender']; echo "</td>";
		echo "<td>"; echo $results['arrive']; echo "</td>";
		echo "<td>"; echo $results['depart']; echo "</td>";
		echo "<td>"; echo $results['room']; echo "</td>";
			if ($results['room'] == 'single') { $total_single += 1;}
			else if ($results['room'] == 'double') { $total_double += 1;}
		echo "<td>"; if ($results['room'] == 'double') { echo $results['roommate'];} else { echo "NA";}; echo "</td>";
		echo "<td>"; echo $results['accomfee']; echo "</td>";
			$total_accomfee += $results['accomfee'];
		echo "<td>"; echo $results['payment']; echo "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo "<tfoot><tr><td>Total</td><td colspan='2'>"; echo count($result); echo " booked beds</td>
				<td></td><td></td><td></td>
				<td>"; echo $total_single; echo " single, "; echo $total_double; echo " double</td><td></td>
				<td>Total: "; echo $total_accomfee; echo "</td>
				<td></td></tr></tfoot>";
	echo "</table></div>";
}

function display_lunch($result){
	$total_lunchsaturday = 0;
	$total_lunchsunday = 0;
	$total_lunchmonday = 0;
	$total_lunchtuesday = 0;
	echo "<div>";
	echo "<table class='regtable'><thead><tr><th>ID</th><th>Name</th>
				<th>Saturday</th>
				<th>Sunday</th>
				<th>Monday</th>
				<th>Tuesday</th>
				<th>Dietary requirements</th>
				<th>Payment Status</th>
				</tr></thead>";
	echo"<tbody>";
	foreach($result as $results){
		echo "<tr>";
		echo "<td>"; echo $results['id']; echo "</td>";
		echo "<td>"; echo $results['name']; echo "</td>";
		echo "<td>"; if (strpos($results['lunchdays'], 'saturday') !== false) { echo "✓"; $total_lunchsaturday += 1;}; echo "</td>";
		echo "<td>"; if (strpos($results['lunchdays'], 'sunday') !== false) { echo "✓"; $total_lunchsunday += 1;}; echo "</td>";
		echo "<td>"; if (strpos($results['lunchdays'], 'monday') !== false) { echo "✓"; $total_lunchmonday += 1;}; echo "</td>";
		echo "<td>"; if (strpos($results['lunchdays'], 'tuesday') !== false) { echo "✓"; $total_lunchtuesday += 1;}; echo "</td>";
		echo "<td>FIXME</td>";
		echo "<td>"; echo $results['payment']; echo "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo "<tfoot><tr><td>Total</td><td>"; echo count($result); echo " people with meals</td>
				<td>"; echo $total_lunchsaturday; echo "</td>
				<td>"; echo $total_lunchsunday; echo "</td>
				<td>"; echo $total_lunchmonday; echo "</td>
				<td>"; echo $total_lunchtuesday; echo "</td>
				<td></td><td></td>
				</tr></tfoot>";
	echo "</table></div>";
}

require_once("header.php");
 if( !(current_user_can( 'administrator' ) )){
 	echo "You are not authorised to view this page.";
 }
else{
	
    if(isset($_POST['action'])) {
        $action = $_POST['action'];
        $regid = $_POST['regid'];
        if($action == 'sendreminder')
        {
            // Get the email address of the registrant
            $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM wp_guadec2014_registrations WHERE id=%d",
                                                     $regid),
                                      ARRAY_A);
            
            $name = $results['name'];
            $email = $results['email'];

            $headers = "From: GUADEC 2014 Registration Script <contact@guadec.org>\n";
            $body = "Hi " . $name . "\n";
            $body .= "\n";
            $body .= "It seems you haven't completed the registation process. To finish it, please either pay online with Paypal or select the on-site payment option on the following page: https://www.guadec.org/confirm-payment/?payfor=" . $regid . "\n";
            $body .= "\n";
            $body .= "Thanks for registering! We hope you have a nice GUADEC.\n";
            $body .= "\n";
            $body .= "--\n";
            $body .= "The GUADEC organization team";
            mail($email, 'GUADEC-2014 Registration Payment: Reminder', $body, $headers);

            print('<div>Reminder email sent to ' . $name . '&lt;' . $email . '&gt;</div><br />');
        }
    }
    if(isset($_POST['newstatus'])) {
        $newstatus = $_POST['newstatus'];
        $regid = $_POST['regid'];

        $updated = $wpdb->update('wp_guadec2014_registrations',
                                 array('payment' => $newstatus),
                                 array('id' => $regid));

        // This should always be one, but let's make sure..
        print('<div>' . print_r($updated, true) . ' entries were updated to status ' . $newstatus . '</div><br />');
    }
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
			$result = $wpdb->get_results("SELECT * FROM wp_guadec2014_registrations WHERE payment = 'Completed' OR payment ='NoPayment' OR payment ='OnSite'", ARRAY_A);
			echo display_totals($result);
			break;
		case 'showaccommodation' :
			$result = $wpdb->get_results("SELECT * FROM wp_guadec2014_registrations WHERE accom = 'YES' AND payment != 'Dupe'", ARRAY_A);
			echo display_accommodation($result);
			break;
		case 'showlunch' :
			$result = $wpdb->get_results("SELECT * FROM wp_guadec2014_registrations WHERE lunchfee != '0' AND payment != 'Dupe'", ARRAY_A);
			echo display_lunch($result);
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
	<option value='showtotals'>Totals For Completed And OnSite Registrations</option>
	<option value='showaccommodation'>Accommodation</option>
	<option value='showlunch'>Lunch</option>
		</select></div>
		<input type='submit' value='Go' />
		</form>";

}
require_once("footer.php");
?>
