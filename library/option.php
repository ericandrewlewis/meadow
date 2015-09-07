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
		// This whitelists the setting so it's automatically saved by settings page form handlers.
		register_setting( $this->section, $this->key );

		add_settings_field(
			$this->key,
			'Example setting Name',
			'hi',
			$this->page,
			$this->section
		);
	}
}

/**
 * Just say *something*.
 *
 * @return [type] [description]
 */
function hi() {
	echo 'hi';
}