<?php
/**
 * Plugin Name: Meadow
 * Version: 0.1
 * Author: Eric Andrew Lewis
 */

// Generic library code
require( 'library/class-metadata-store.php' );
require( 'library/functions.php' );

// Library code for asset types
require( 'library/attachment.php' );
require( 'library/post/class-meadow-postmeta.php' );
require( 'library/post/class-meadow-postmeta-control.php' );
require( 'library/option.php' );

$include_examples = true;
if ( $include_examples ) {
	require( 'examples/attachment.php' );
	require( 'examples/post.php' );
	require( 'examples/option.php' );
}