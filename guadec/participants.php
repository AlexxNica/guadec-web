<?php
/*
Template Name: List of participants
*/
?>
<?php
global $wpdb;
require_once("header.php");
echo "<table class='regtable'><tr><th>Name</th><th>Location</th></th></tr>";
$result = $wpdb->get_results("SELECT name, country FROM wp_guadec2014_registrations WHERE ispublic = 'YES' AND (payment = 'Completed' OR payment ='NoPayment' OR payment ='OnSite')", ARRAY_A);
foreach($result as $results){
	echo "<tr>";
	echo "<td>"; echo $results['name']; echo "</td>";
	echo "<td>"; echo $results['country']; echo "</td>";
	echo "</tr>";
}
echo "</table>";
require_once("footer.php");
?>
