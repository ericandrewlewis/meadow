<?php
function __noop() {}
/**
 * A fallthrough helper for skipping sanitization.
 *
 * @param mixed $arg
 * @return mixed
 */
function __noop_sanitizer( $arg ) {
	return $arg;
}

/**
 * Register metadata.
 *
 * Just a candy wrapper.
 *
 * @param array $args
 * @return
 */
function meadow_register_meta( $args ) {
	$store = Meadow_Metadata_Store::getInstance();
	$meta = $store->register_meta( $args );
	return $meta;
}