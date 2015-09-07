<?php
function __return_first_arg( $arg ) {
	return $arg;
}

/**
 * A place to decribe metadata in respect to its data.
 *
 * This includes sanitization and authentication callbacks, but also stores registered
 * metadata for generic use to inform administrative UI.
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

	/**
	 * Get all registered meta.
	 */
	public function get_meta() {
		return $this->meta;
	}

	/**
	 * Register postmeta for any post type.
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

/**
 * Register metadata.
 *
 * @param  [type] $args [description]
 * @return [type]       [description]
 */
function meadow_register_meta( $args ) {
	$store = Meadow_Metadata_Store::getInstance();
	$store->register_meta( $args );
}

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
		if ( $meta['edit_post_location'] !== 'under_title' ) {
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
		if ( $meta['edit_post_location'] !== 'post_submitbox_misc_actions' ) {
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
		if ( isset( $_REQUEST['custom_field_' . $meta['key']] ) ) {
			$value = $_REQUEST['custom_field_' . $meta['key']];
			update_post_meta( $post->ID, $meta['key'], $value );
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
		if ( $meta['edit_post_location'] !== 'metabox' ) {
			return;
		}
		$value = get_post_meta( $post->ID, $meta['key'], true );
		?><label><h3 class="custom-field-label"><?php echo $meta['label'] ?></h3><?php
		if ( $meta['type'] === 'text' ) {
			?><input type="text" name="<?php echo 'custom_field_' . $meta['key'] ?>" value="<?php echo $value ?>"><?php
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