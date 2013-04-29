<?php

class WCB_Header extends WCB_Element {
	function get_id() {
		return 'header';
	}

	function content() { ?>
	<div id="<?php echo $this->get_id(); ?>">
		<div class="event-logo">
			<a class="event-logo-link" href="<?php echo home_url(); ?>"></a>
		</div>
		<?php wp_nav_menu(array('menu'=>'main',
					'container'=>'',
					'items_wrap'=>'%3$s'));
		?>
		<div class="clear"></div>
		<div class="grid_12 branding-buildings">&nbsp;</div>
	</div>
    <?php
	}
}

?>
