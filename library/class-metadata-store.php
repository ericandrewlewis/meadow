<?php
/**
 * A place to decribe metadata in respect to its data.
 *
 * Describe data sanitization and authentication callbacks.
 *
 * Stores registered metadata for generic use to inform administrative UI. This
 * feels like something that should be separated into a separate method call at least,
 * e.g. register_ui_for_meta. Remember: a "setting" in the Customizer is any
 * encapsulated piece of data that can be edited, e.g. post meta or a widget instance.
 */
class Meadow_Metadata_Store {
	private static $instance;

	private $meta = array( 'post' => array() );

	public static function getInstance() {
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}
	protected function __construct() {}

	/**
	 * Register any meta. This makes
	 */
	public function register_meta( $args ) {
		// Call the asset-type specific registration routine.
		call_user_func( array( $this, 'register_' . $args['asset_type'] . '_meta' ), $args );
	}

	/**
	 * Get all registered meta.
	 */
	public function get_meta() {
		return $this->meta;
	}

	/**
	 * Register postmeta for any post type.
	 */
	private function register_post_meta( $args ) {
		$meta = new Meadow_Postmeta( $args );
		// Store the meta to describe to external APIs.
		$this->meta['post'][$meta->post_type][] = $meta;

		// Create a UI control for the metadata, which will decorate the wp-admin
		// application with interface for the user to edit it.
		if ( $meta->post_type === 'attachment' ) {
			new Meadow_Attachmentmeta_UI_Control( array( 'meta' => $meta ) );
		} else {
			new Meadow_Postmeta_UI_Control( array( 'meta' => $meta ) );
		}
	}

	/**
	 * Register an option.
	 */
	private function register_option_meta( $args ) {
		$meta = new Meadow_Option( $args );
		// Store the meta to describe to external APIs.
		$this->meta['option'][] = $meta;

		new Meadow_Option_UI_Control( array( 'meta' => $meta ) );
	}
}