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
		'post_type' => 'attachment',
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
		// Need this cuz post.php for attachments is dumb?
		'edit_post_location' => null,
	)
);
