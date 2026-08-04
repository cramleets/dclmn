<?php

/**
 * @deprecated 6.0.12 With CT1 active, this is being deactivated and handled in \TEC\Events_Pro\Custom_Tables\V1\Events\Event_Cleaner\Provider
 */
class Tribe__Events__Pro__Recurrence__Old_Events_Cleaner {

	/**
	 * @var Tribe__Events__Pro__Recurrence__Old_Events_Cleaner
	 */
	protected static $instance;
	/**
	 * @var
	 */
	private $scheduler;

	/**
	 * @return Tribe__Events__Pro__Recurrence__Old_Events_Cleaner
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * The Old Events Cleaner constructor.
	 *
	 * @since 6.0.0
	 * @since 7.8.0 Made $scheduler explicitly nullable.
	 *
	 * @param Tribe__Events__Pro__Recurrence__Scheduler|null $scheduler The scheduler instance or null.
	 */
	public function __construct( ?Tribe__Events__Pro__Recurrence__Scheduler $scheduler = null ) {
		$this->scheduler = $scheduler ? $scheduler : new Tribe__Events__Pro__Recurrence__Scheduler();
	}

	public function clean_up_old_recurring_events( array $old_value, array $new_value ) {
		$old_value = empty( $old_value['recurrenceMaxMonthsBefore'] ) ? 24 : $old_value['recurrenceMaxMonthsBefore'];
		$new_value = empty( $new_value['recurrenceMaxMonthsBefore'] ) ? 24 : $new_value['recurrenceMaxMonthsBefore'];

		if ( $new_value == $old_value ) {
			return;
		}
		if ( $new_value > $old_value ) {
			return;
		}

		$this->scheduler->set_before_range( $new_value );

		$this->scheduler->clean_up_old_recurring_events();
	}

	public function get_scheduler() {
		return $this->scheduler;
	}
}
