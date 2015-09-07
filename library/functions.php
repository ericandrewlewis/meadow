<?php
function __noop() {}
/**
 * A fallthrough helper for skipping sanitization.
 *
 * @param mixed $arg
 * @return mixed
 */
function __return_first_arg( $arg ) {
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
	$store->register_meta( $args );
}