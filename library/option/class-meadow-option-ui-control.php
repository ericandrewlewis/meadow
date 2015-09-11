<?php
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

		/*
		 * Add the thing that will output the field in the Settings page form.
		 * This essentially does what the section object is doing in the post meta
		 * part of the library. Can we extract this into a section? Not sure because
		 * we need to define the save functionality above including the page to save
		 * on. Maybe there's a lower-level manner to register the whitelist?
		 */
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