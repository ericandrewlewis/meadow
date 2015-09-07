<?php

/**
 * This class encapsulates the "registered" post meta data.
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
		if ( $this->post_type === 'attachment' ) {
			new Meadow_Attachmentmeta_UI_Control( array( 'meta' => $this ) );
		} else {
			new Meadow_Postmeta_UI_Control( array( 'meta' => $this ) );
		}

	}
}

/**
 * Set up a UI control for a piece of postmeta inside the wp-admin
 * application.
 */
class Meadow_Postmeta_UI_Control {
	function __construct($args) {
		$this->meta = $args['meta'];
		if ( $this->meta->edit_post_location === 'under_title' ) {
			add_action( 'edit_form_after_title', array( $this, 'render' ) );
		}
		if ( $this->meta->edit_post_location === 'post_submitbox_misc_actions' ) {
			add_action( 'post_submitbox_misc_actions', array( $this, 'render' ) );
		}
		add_action( 'save_post', array( $this, 'save_post' ) );
	}
	public function render() {
		$post = get_post();
		$value = get_post_meta( $post->ID, $this->meta->key, true );
		?><label><h3 class="custom-field-label"><?php echo $this->meta->label ?></h3><?php
		if ( $this->meta->type === 'text' ) {
			?><input type="text" name="<?php echo 'custom_field_' . $this->meta->key ?>" value="<?php echo $value ?>"><?php
		}
		?></label><?php
	}

	public function save_post() {
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
	}
}