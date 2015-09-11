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
require( 'library/post/class-meadow-postmeta.php' );
require( 'library/post/class-meadow-postmeta-ui-control.php' );
require( 'library/post/class-meadow-attachmentmeta-ui-control.php' );
require( 'library/option/class-meadow-option.php' );
require( 'library/option/class-meadow-option-ui-control.php' );

$include_examples = true;
if ( $include_examples ) {
	require( 'examples/attachment.php' );
	require( 'examples/post.php' );
	require( 'examples/option.php' );
}