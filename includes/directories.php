<?php

define('SEARCHANDFILTER_DIR', get_stylesheet_directory() . '/includes/search-filter');
define('GENESIS-BLOCKS_DIR', get_stylesheet_directory() . '/includes/genesis-custom-blocks/'); // todo: tbd

//------------------------------------------------
// # WEBPACK SCRIPTS
// register webpack compiled js and css with theme
// ------------------------------------------------
function enqueue_webpack_scripts():void {

	wp_enqueue_style( 'child-theme-styles', get_stylesheet_directory_uri() . '/build/index.css' );
	wp_enqueue_script( 'child-theme-scripts', get_stylesheet_directory_uri() . '/build/index.js', array(), "1.0", true );

}
add_action( 'wp_enqueue_scripts', 'enqueue_webpack_scripts' );