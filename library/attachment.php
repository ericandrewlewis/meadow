<?php
/**
 * Display UI for attachment metadata in the attachment details modal.
 *
 * @todo can this handle other data types easily? radio?
 */
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
			'helps' => __( 'Person who took the picture' )
		);
	}
	return $form_fields;
}, 10, 2 );

/**
 * Handle saving attachment metadata from the attachment details modal.
 */
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