<?php
/*
Template Name: Access Registration
*/
?>
<?php
// Restricted Admin Access. View Registration details.
global $wpdb;
function display_result($result){
	echo "<table class='regtable'><tr><th>ID</th><th>Name</th>
				<th>Email</th><th>Gender</th>
				<th>Arrival</th><th>Departure</th>
				<th>Sponsored</th>
				<th>EntryFee</th>
				<th>LunchFee</th><th>AccomFee</th>
				<th>TotalFee</th>
				<th>Payment Status</th></tr>";
	foreach($result as $results){
		echo "<tr>";
		echo "<td>"; echo $results['id']; echo "</td>";
		echo "<td>"; echo $results['name']; echo "</td>";
		echo "<td>"; echo $results['email']; echo "</td>";
		echo "<td>"; echo $results['gender']; echo "</td>";
		echo "<td>"; echo $results['arrive']; echo "</td>";
		echo "<td>"; echo $results['depart']; echo "</td>";
		echo "<td>"; echo $results['sponsored']; echo "</td>";
//		echo "<td>"; echo $results['lunchdays']; echo "</td>";
		echo "<td>"; echo $results['entryfee']; echo "</td>";
		echo "<td>"; echo $results['lunchfee']; echo "</td>";
		echo "<td>"; echo $results['accomfee']; echo "</td>";
		echo "<td>"; echo $results['totalfee']; echo "</td>";
		echo "<td>"; echo $results['payment']; echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
}
require_once("header.php");
 if( !(current_user_can( 'administrator' ) )){
 	echo "You are not authorised to view this page.";
 }
else{
	
	if(isset($_POST['viewtype']) && !empty($_POST['viewtype'])) {
	    $action = $_POST['viewtype'];
	   // $table_name = $wpdb->prefix .'guadec2014_registration';
	    switch($action) {

	        case 'showall' :
	            $result = $wpdb->get_results('SELECT * FROM wp_guadec2014_registration', ARRAY_A);
	        	echo display_result($result);
	        	break;
	     	case 'showcomplete' :
	    		$result = $wpdb->get_results("SELECT * FROM wp_guadec2014_registration WHERE payment = 'Completed' OR payment ='NoPayment'", ARRAY_A);
	     		echo display_result($result);
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
		</select></div>
		<input type='submit' value='Go' />
		</form>";

}
require_once("footer.php");
?>