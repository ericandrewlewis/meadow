<?php
meadow_register_meta(
	array(
		/*
		 * Asset types are recursive, use ":" as a separator to keep this syntax brief.
		 *
		 * Posts, comments, users, settings are top-level citizens.
		 * Narrow down from there based on the respective schemas.
		 * Open up lower-level filters for more granular control.
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
		// The "type" of the data. This describes what kind of UI to offer the user.
		'type' => 'text',
		// Label for the UI control.
		'label' => __( 'Subtitle' ),
		// Where the field should render on the Edit Post screen. either 'under_title',
		// 'post_submitbox_misc_actions', or somehow a meta box.
		'edit_post_location' => 'post_submitbox_misc_actions',
	)
);