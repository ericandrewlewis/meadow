<?php
/**
 * Set up a UI control for a piece of postmeta inside the wp-admin
 * application.
 */
class Meadow_Postmeta_UI_Control {
	function __construct($args) {
		foreach ( $args as $key => $val ) {
			$this->$key = $val;
		}
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public function render() {
		$post = get_post();
		$value = get_post_meta( $post->ID, $this->meta->key, true );
		?><label><h3 class="custom-field-label"><?php echo $this->label ?></h3><?php
		if ( $this->type === 'text' ) {
			?><input type="text" name="<?php echo 'custom_field_' . $this->meta->key ?>" value="<?php echo $value ?>"><?php
		}
		?></label><?php
	}

	public function save_post() {
		$post = get_post();

		if ( wp_is_post_revision( $post->ID ) )
			return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

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