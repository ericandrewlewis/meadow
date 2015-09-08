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

/**
 * Set up a UI control for an option inside the wp-admin application.
 */
class Meadow_Option_UI_Control {
	function __construct($args) {
		$this->meta = $args['meta'];
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		/*
		 * Manually register the setting in the "options whitelist" which handles
		 * saving the data on an options page.
		 *
		 * This is an alternative to calling register_setting() which does too much.
		 */
		global $new_whitelist_options;
		$new_whitelist_options[ $this->meta->page ][] = $this->meta->key;

		// Add the thing that will output the field in the Settings page form.
		add_settings_field(
			$this->meta->key,
			$this->meta->label,
			array( $this, 'render' ),
			$this->meta->page,
			$this->meta->section
		);
	}

	public function render() {
		$value = get_option( $this->meta->key );
		if ( $this->meta->type === 'text' ) {
			?><input type="text" name="<?php echo $this->meta->key ?>" value="<?php echo esc_attr( $value ) ?>"><?php
		}
	}
}