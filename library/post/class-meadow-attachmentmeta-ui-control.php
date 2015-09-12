<?php
/**
 * A UI control for attachments in the wp-admin interface.
 */
class Meadow_Attachmentmeta_UI_Control {
	function __construct($args) {
		foreach ( $args as $key => $val ) {
			$this->$key = $val;
		}
		add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), 10, 2 );
		add_action( 'edit_attachment', array( $this, 'edit_attachment' ) );
	}

	/**
	 * Display UI for attachment metadata in the attachment details modal.
	 *
	 * @todo can this handle other data types easily? radio?
	 */
	public function attachment_fields_to_edit( $form_fields, $post ) {
		$value = get_post_meta( $post->ID, $this->meta->key, true );
		// Namespace the field name to avoid any collisions with existing fields.
		$form_fields['custom_field_' . $this->meta->key] = array(
			'value' => $value,
			'label' => $this->label,
			// todo think about this:
			'helps' => __( 'Person who took the picture' )
		);
		return $form_fields;
	}

	/**
	 * Handle saving attachment metadata from the attachment details modal.
	 */
	public function edit_attachment( $attachment_id ) {
		if ( isset( $_REQUEST['attachments'][$attachment_id]['custom_field_' . $this->meta->key] ) ) {
			$value = $_REQUEST['attachments'][$attachment_id]['custom_field_' . $this->meta->key];
			update_post_meta( $attachment_id, $this->meta->key, $value );
		}
	}
}