<?php

/*--------------------------------------------------------------------------------------
 *
 *	enables contributors to upload files to theirs posts
 *
 *-------------------------------------------------------------------------------------*/
if ( current_user_can('contributor') && !current_user_can('upload_files') )
	add_action('admin_init', 'allow_contributor_uploads');

function allow_contributor_uploads() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}


/*--------------------------------------------------------------------------------------
 *
 *	check_user_role
 *  - checks if a particular user has a role. returns true if a match was found.
 *
 * 	@param string $role Role name.
 * 	@param int $user_id (Optional) The ID of a user. Defaults to the current user.
 * 	@return bool
 *
 *-------------------------------------------------------------------------------------*/
function check_user_role( $role, $user_id = null ) {
 
    if ( is_numeric( $user_id ) )
	$user = get_userdata( $user_id );
    else
        $user = wp_get_current_user();
 
    if ( empty( $user ) )
	return false;
 
    return in_array( $role, (array) $user->roles );
}

/*--------------------------------------------------------------------------------------
 *
 *	remove_menus
 *	- unset option from wp-admin menu undesired settings. hide everything from the menu
 *  except for talks, if user is a contributor or a subscriber.
 *
 *-------------------------------------------------------------------------------------*/
function remove_menus() {	
	
	if (check_user_role("contributor") || check_user_role("subscriber")) {
		global $menu;
	
		$restricted = array(
			__('Dashboard'),
			__('Posts'),
			__('Media'),
			__('Speakers'),
			__('Sponsors'),
			__('Organizers'),
			__('Comments'),
			__('Tools')
		);
	
		end($menu);
	
		while (prev($menu)) {
			$value = explode(' ', $menu[key($menu)][0]);
			if (in_array($value[0] != null ? $value[0] : "", $restricted)) {
				unset($menu[key($menu)]);
			}		
		}
	}
}

/*--------------------------------------------------------------------------------------
 *
 *	applies remove_menus as an action hook for the admin_menu in wp-admin.
 *
 *-------------------------------------------------------------------------------------*/
add_action('admin_menu', 'remove_menus');

/*--------------------------------------------------------------------------------------
 *
 *	show a custom logo on login
 *
 *-------------------------------------------------------------------------------------*/



function login_css() {
	wp_enqueue_style( 'login_css', get_template_directory_uri() . '/css/login.css' );
}
add_action('login_head', 'login_css');


/*--------------------------------------------------------------------------------------
 *
 *	remove_footer_admin
 *	- this function removes the default footer of wordpress admin and replaces it
 *	with other text. in this case, GUADEC.
 *
 *-------------------------------------------------------------------------------------*/

function remove_footer_admin() {
  echo 'GUADEC 2013';
}

add_filter('admin_footer_text', 'remove_footer_admin');

/*--------------------------------------------------------------------------------------
 *
 *	change_footer_version
 *	- this function removes the default versioning in the footer of wp-admin.
 *
 *-------------------------------------------------------------------------------------*/

function change_footer_version() {
  return '';
}

add_filter('update_footer', 'change_footer_version', 9999);



/*--------------------------------------------------------------------------------------
 *
 *	my_admin_bar_render
 *	- hides items from the wp-admin top bar. hides the wp-logo, the comments link and
 *	the new-content tab.
 *
 *-------------------------------------------------------------------------------------*/

function my_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('new-content');
}
add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' );


/*--------------------------------------------------------------------------------------
 *
 *	password reset message
 *
 *-------------------------------------------------------------------------------------*/

add_filter ("retrieve_password_title", "my_reset_password_title");

function my_reset_password_title() {
	return "[GUADEC 2013] Your password reset";
}

	
add_filter ("retrieve_password_message", "my_reset_password_message");
function my_reset_password_message($content, $key) {
	global $wpdb;
	$user_login = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE user_activation_key = ' . $key . '");
	
	ob_start();
	
	$email_subject = "[GUADEC 2013] Your password reset";;
?>

	<html>
		<head>
			<title>Your password reset for GUADEC 2013</title>
		</head>

		<body>
			<p>
				It looks like you want to reset your password for your GUADEC 2013 account.
			</p>
			
			<p>
				To reset your password, visit the following address, otherwise just ignore this email and nothing will happen. <br>
				<?php echo wp_login_url("url") ?>?action=rp&key=<?php echo $key ?>&login=<?php echo $user_login ?>
			</p>
			
			<p>
				Cheers,
				The GUADEC 2013 Team
			</p>
		</body>
	</html>
	
<?php	
	$message = ob_get_contents();

	ob_end_clean();
  
	return $message;
}


?>
