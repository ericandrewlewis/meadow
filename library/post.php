<?php

/**
 * This class encapsulates functionality of "registered" post meta data.
 *
 * Currently optimizing for logical readability, unclear on the memory footprint.
 */
class Meadow_Postmeta {
	function __construct( $args ) {
		/*
		 * Use the built-in API to "register" meta,
		 * i.e. set sanitization and authorization callbacks on the right filters.
		 */
		register_meta(
			'post',
			$args['key'],
			$args['sanitization_callback'],
			$args['authentication_callback']
		);
		foreach ( $args as $key => $val ) {
			$this->$key = $val;
		}
	}
}

/**
 * Add form UI in the after title location.
 */
add_action( 'edit_form_after_title', function() {
	$post = get_post();
	$store = Meadow_Metadata_Store::getInstance();
	if ( empty( $store->get_meta()['post'][$post->post_type] ) ) {
		return;
	}
	$metas = $store->get_meta()['post'][$post->post_type];
	foreach ( $metas as $meta ) {
		if ( $meta->edit_post_location !== 'under_title' ) {
			return;
		}
		$value = get_post_meta( $post->ID, $meta['key'], true );
		?><label><h3 class="custom-field-label"><?php echo $meta['label'] ?></h3><?php
		if ( $meta['type'] === 'text' ) {
			?><input type="text" name="<?php echo 'custom_field_' . $meta['key'] ?>" value="<?php echo $value ?>"><?php
		}
		?></label><?php
	}
});

/**
 * Add form UI in the post_submitbox_misc_actions
 */
add_action( 'post_submitbox_misc_actions', function() {
	$post = get_post();
	$store = Meadow_Metadata_Store::getInstance();
	if ( empty( $store->get_meta()['post'][$post->post_type] ) ) {
		return;
	}
	$metas = $store->get_meta()['post'][$post->post_type];
	foreach ( $metas as $meta ) {
		if ( $meta->edit_post_location !== 'post_submitbox_misc_actions' ) {
			return;
		}
		$value = get_post_meta( $post->ID, $meta['key'], true );
		?><label><h3 class="custom-field-label"><?php echo $meta['label'] ?></h3><?php
		if ( $meta['type'] === 'text' ) {
			?><input type="text" name="<?php echo 'custom_field_' . $meta['key'] ?>" value="<?php echo $value ?>"><?php
		}
		?></label><?php
	}
});

/*
 * Save data from Edit Post screen.
 */
add_action( 'save_post', function() {
	$post = get_post();

	// @todo look into enabling these intelligently.
	// if ( wp_is_post_revision( $post_id ) )
			// return;
		// if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			// return;

	$store = Meadow_Metadata_Store::getInstance();
	if ( empty( $store->get_meta()['post'][$post->post_type] ) ) {
		return;
	}
	$metas = $store->get_meta()['post'][$post->post_type];
	foreach ( $metas as $meta ) {
		if ( isset( $_REQUEST['custom_field_' . $meta->key] ) ) {
			$value = $_REQUEST['custom_field_' . $meta->key];
			update_post_meta( $post->ID, $meta->key, $value );
		}
	}
});

/**
 * Add form UI in the Edit Post metabox
 */
function meadow_output_metabox_contents() {
	$post = get_post();
	$store = Meadow_Metadata_Store::getInstance();
	if ( empty( $store->get_meta()['post'][$post->post_type] ) ) {
		return;
	}
	$metas = $store->get_meta()['post'][$post->post_type];
	foreach ( $metas as $meta ) {
		if ( $meta->edit_post_location !== 'metabox' ) {
			return;
		}
		$value = get_post_meta( $post->ID, $meta->key, true );
		?><label><h3 class="custom-field-label"><?php echo $meta->label ?></h3><?php
		if ( $meta->type === 'text' ) {
			?><input type="text" name="<?php echo 'custom_field_' . $meta->key ?>" value="<?php echo $value ?>"><?php
		}
		?></label><?php
	}
}
/**
 * This is a container for UI controls on a post page.
 *
 * Need to think about how to make this more declarative, or at least how to bind
 * it to the registered metadata.
 */
add_action('add_meta_boxes', function() {
	add_meta_box(
		'metabox-id',                       // ID
		'Metabox Title',                    // title
		'meadow_output_metabox_contents',   // render callback
		'post',                             // screen
		'advanced',                         // context
		'default',                          // priority
		null                                // callback_args
	);
});