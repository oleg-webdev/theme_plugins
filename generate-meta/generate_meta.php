<?php
/*
Plugin Name: Generate Meta
Plugin URI: http://www.upwork.com/fl/olegtsibulnik
Description: Alicelf Generate Metaboxes plugin - Upload and Activate.
Author: Alicelf WebArtisan
Version: 3.1.2
Author URI: http://www.upwork.com/fl/olegtsibulnik
*/
//@Template Todo: create separate sections
//@Template Todo: define user frendly factory
//@Template Todo: removable single image
//@Template Todo: output gallery to frontend
//@Template Todo: datepicker box type
//@Template Todo: extend plugin

require( 'GenerateMeta.php' );
require( 'ajax_actions.php' );

// Include all dynamic custom fields
foreach ( glob( plugin_dir_path( __FILE__ ) . "/dynamic_metafields/*.php" ) as $filename ) {
	require_once( $filename );
}

/**
 * Include styles and scripts
 *
 */
add_action( 'admin_enqueue_scripts', 'aa_func_20154904124919' );
function aa_func_20154904124919()
{
	$plugindir = plugin_dir_url( __DIR__ ) . basename( __DIR__ );

	// Plugin scripts
	wp_enqueue_style( 'alice_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'GenerateMetaStyle', $plugindir . '/style_script/style.css' );
	wp_enqueue_script( 'GenerateMetaScript', $plugindir . '/style_script/script.js', array( 'jquery' ), false, true );
	$data = array(
		'site_url'     => get_site_url(),
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'template_uri' => get_template_directory_uri(),
	);
	wp_localize_script( 'GenerateMetaScript', 'aa_generate_meta_var', $data );
}