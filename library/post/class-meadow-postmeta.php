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
	}
}