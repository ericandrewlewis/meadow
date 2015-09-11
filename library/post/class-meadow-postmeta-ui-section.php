<?php
/**
 * A section is a group of settings that live somewhere in the Edit Post screen.
 *
 * Locations include below the title, in the publish metabox,
 * and a standalone meta box.
 */
class Meadow_Postmeta_UI_Section {

	/**
	 * All controls registered to the section.
	 * @var array
	 */
	var $controls = array();

	/**
	 * @param array $args {
	 *      @type string $location The location of the section. Accepts 'edit_form_after_title',
	 *                             'post_submitbox_misc_actions', and 'metabox'.
	 * }
	 */
	function __construct( $args ) {
		$this->location = $args['location'];
		if ( $this->location === 'under_title' ) {
			add_action( 'edit_form_after_title', array( $this, 'render' ) );
		}
		if ( $this->location === 'post_submitbox_misc_actions' ) {
			add_action( 'post_submitbox_misc_actions', array( $this, 'render' ) );
		}
		if ( $this->location === 'metabox' ) {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		}
	}

	public function add_control( $control ) {
		$this->controls[] = $control;
	}

	public function add_meta_boxes() {
		add_meta_box(
			'custom-fields-metabox',
			'Custom Fields',
			array( $this, 'render' ),
			'post',
			'advanced',
			'default',
			null
		);
	}

	public function render() {
		foreach ( $this->controls as $control ) {
			$control->render();
		}
	}
}