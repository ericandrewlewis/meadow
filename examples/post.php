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

		// The "type" of the data. This describes what kind of UI to offer the user.
		'type' => 'text',

		// Label for the UI control.
		'label' => __( 'Subtitle' ),

		/*
		 * Where the field should render on the Edit Post screen. either 'under_title',
		 * 'post_submitbox_misc_actions', or somehow a meta box.
		 */
		'edit_post_location' => 'post_submitbox_misc_actions',
	)
);

// Create a UI control for the metadata, which will decorate the wp-admin
// application with interface for the user to edit it.
if ( $meta->post_type === 'attachment' ) {
	new Meadow_Attachmentmeta_UI_Control( array( 'meta' => $meta ) );
} else {
	new Meadow_Postmeta_UI_Control( array( 'meta' => $meta ) );
}