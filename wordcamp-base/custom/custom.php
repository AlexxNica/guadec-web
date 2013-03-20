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



?>
