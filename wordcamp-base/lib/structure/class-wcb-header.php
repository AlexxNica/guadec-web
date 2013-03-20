<?php

class WCB_Header extends WCB_Element {
	function get_id() {
		return 'header';
	}

	function content() { ?>
		<div id="<?php echo $this->get_id(); ?>">
			<div class="grid_4 event-location">Brno, Czech Republic</div>
			<div class="grid_4 event-logo">&nbsp;</div>
			<div class="grid_4 event-date">August 1 - 8, 2013</div>
			<div class="clear"></div>
			<div class="grid_12 branding-buildings">&nbsp;</div>
		</div><!-- #header -->
	<?php
	}
}

?>
