<?php
$meta = meadow_register_meta(
	array(
		/*
		 * Posts, comments, users, settings are top-level asset types.
		 *
		 * Narrow down from there with 'post_type'.
		 */
		'asset_type' => 'option',

		// The key of the meta data.
		'key' => 'disallow_something',

		/*
		 * edit_post is always required for postmeta, @see map_meta_cap() and think
		 * about this some more. It's awkard that __return_true wouldn't work for any
		 * user here.
		 */
		'authentication_callback' => '__return_true',

		// Data sanitization callback.
		'sanitization_callback' => '__noop_sanitizer',

		// The "type" of the data. This describes what kind of UI to offer the user.
		'type' => 'text',

		// Label for the UI control.
		'label' => __( 'Disallow Something' ),

		// The settings page for the control.
		'page' => 'general',

		// The settings section for the control.
		'section' => 'default',
	)
);

new Meadow_Option_UI_Control( array( 'meta' => $meta ) );