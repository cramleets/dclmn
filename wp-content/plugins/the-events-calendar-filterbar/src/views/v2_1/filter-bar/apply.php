<?php
/**
 * View: Filter Bar Apply button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-filterbar/v2_1/filter-bar/apply.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.6.6
 */

?>
<div class="tribe-filter-bar__apply">
	<button
		class="tribe-filter-bar__apply-button tribe-common-c-btn tribe-common-c-btn--small"
		data-js="tribe-filter-bar-apply"
		type="button"
		disabled
	>
		<?php esc_html_e( 'Apply', 'tribe-events-filter-view' ); ?>
	</button>
</div>
