<?php
$meta = meadow_register_meta(
	array(
		/*
		 * Posts, comments, users, settings are top-level asset types.
		 *
		 * Narrow down from there with 'post_type'.
		 */
		'asset_type' => 'post',

		'post_type' => 'post',

		// The key of the meta data.
		'key' => 'subtitle',

		/*
		 * edit_post is always required for postmeta, @see map_meta_cap() and think
		 * about this some more. It's unintuitive that __return_true wouldn't work if the
		 * user couldn't edit the post.
		 */
		'authentication_callback' => '__return_true',

		/*
		 * Data sanitization callback.
		 */
		'sanitization_callback' => '__noop_sanitizer',
	)
);

// Create a UI control for the metadata.
$control = new Meadow_Postmeta_UI_Control( array(
	'meta' => $meta,

	// The "type" of the control. This describes what kind of UI to offer the user.
	'type' => 'text',

	// Label for the UI control.
	'label' => __( 'Subtitle' ),
) );

// Stuff the control into a section.
$section = new Meadow_Postmeta_UI_Section( array( 'location' => 'metabox' ) );
$section->add_control( $control );