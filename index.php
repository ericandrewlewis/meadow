<?php
/**
 * Plugin Name: Meadow
 * Version: 0.1
 * Author: Eric Andrew Lewis
 */

function __return_first_arg( $arg ) {
	return $arg;
}

/**
 * A place to decribe metadata in respect to its data.
 *
 * This includes sanitization and authentication callbacks.
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

	public function get_meta() {
		return $this->meta;
	}

	/**
	 * Register postmeta of any post type.
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

function meadow_register_meta( $args ) {
	$store = Meadow_Metadata_Store::getInstance();
	$store->register_meta( $args );
}

meadow_register_meta(
	array(
		/*
		 * Asset types are recursive, use ":" as a separator to keep this syntax brief.
		 *
		 * Posts, comments, users, settings are top-level citizens.
		 * Narrow down from there based on the respective schemas.
		 * Open up lower-level filters for more granular control.
		 */
		'asset_type' => 'post:attachment',
		// The key of the meta data.
		'key' => 'credit',
		/*
		 * edit_post is always required for postmeta, @see map_meta_cap() and think
		 * about this some more. It's awkard that __return_true wouldn't work for any
		 * user here.
		 */
		'authentication_callback' => '__return_true',
		/*
		 * Data sanitization callback.
		 */
		'sanitization_callback' => '__return_first_arg',
		'type' => 'text',
		'label' => __( 'Photo Credit' ),
	)
);

add_filter( 'attachment_fields_to_edit', function( $form_fields, $post ) {
	$store = Meadow_Metadata_Store::getInstance();
	$metas = $store->get_meta()['post']['attachment'];
	if ( empty( $metas ) ) {
		return;
	}
	foreach ( $metas as $meta ) {
		$value = get_post_meta( $post->ID, $meta['key'], true );
		// Namespace the field name to avoid any collisions with existing fields.
		$form_fields['custom_field_' . $meta['key']] = array(
			'value' => $value,
			'label' => $meta['label'],
			// todo think about this:
			// 'helps' => __( 'asdf' )
		);
	}
	return $form_fields;
}, 10, 2 );

add_action( 'edit_attachment', function( $attachment_id ) {
	$store = Meadow_Metadata_Store::getInstance();
	$metas = $store->get_meta()['post']['attachment'];
	if ( empty( $metas ) ) {
		return;
	}
	foreach ( $metas as $meta ) {
		if ( isset( $_REQUEST['attachments'][$attachment_id]['custom_field_' . $meta['key']] ) ) {
			$value = $_REQUEST['attachments'][$attachment_id]['custom_field_' . $meta['key']];
			update_post_meta( $attachment_id, $meta['key'], $value );
		}
	}

}, 10, 1 );
