<?php

require_once "custom/custom.php";

require_once "lib/utils/functions.php";

wcb_maybe_define( 'WCB_DIR', dirname( __FILE__ ) );
wcb_maybe_define( 'WCB_URL', get_template_directory_uri() );

require_once "lib/class-wcb-manager.php";


function login_css() {
	wp_enqueue_style( 'login_css', get_template_directory_uri() . 'css/login.css' );
}
add_action('login_head', 'login_css');

?>
