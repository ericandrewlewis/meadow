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
		$args['asset_type_exploded'] = explode( ':', $args['asset_type'] );
		$asset_type = $args['asset_type_exploded'][0];
		// Call the asset-type specific registration routine.
		call_user_func( array( $this, 'register_' . $asset_type . '_meta' ), $args );
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
		register_meta(
			'post',
			$args['key'],
			$args['sanitization_callback'],
			$args['authentication_callback']
		);
		$post_type = $args['asset_type_exploded'][1];
		// Store the meta for display by UI.
		$this->meta['post'][$post_type][] = $args;
	}
}