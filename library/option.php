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
		new Meadow_Option_UI_Control( array( 'meta' => $this ) );
	}

	public function admin_init() {
		// Register the sanitize callback manually rather than using register_setting(),
		// because that function also describes how wp-admin ui should work, which is
		// aagainst our intended separation of concerns.
		if ( $this->sanitization_callback != '' )
			add_filter( "sanitize_option_{$this->key}", $this->sanitization_callback );
	}
}

class Meadow_Option_UI_Control {
	function __construct($args) {
		$this->meta = $args['meta'];
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		global $new_whitelist_options;
		$new_whitelist_options[ $this->meta->section ][] = $this->meta->key;

		// register_setting( $this->meta->section, $this->meta->key );
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
			?><input type="text" name="<?php echo $this->meta->key ?>" value="<?php esc_attr( $value ) ?>"><?php
		}
	}
}