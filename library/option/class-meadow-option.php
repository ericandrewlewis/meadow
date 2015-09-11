<?php

/**
 * This class encapsulates functionality of "registered" post meta data.
 *
 * Currently optimizing for logical readability, unclear on the memory footprint.
 */
class Meadow_Option {
	function __construct( $args ) {
		foreach ( $args as $key => $val ) {
			$this->$key = $val;
		}
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		// Register the sanitize callback manually rather than using register_setting(),
		// because that function also describes how wp-admin ui should work, which is
		// aagainst our intended separation of concerns.
		if ( $this->sanitization_callback != '' )
			add_filter( "sanitize_option_{$this->key}", $this->sanitization_callback );
	}
}