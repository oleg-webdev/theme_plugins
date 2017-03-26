<?php

// Dynamic metabox obj start
$theid_meta      = 'example_dynamic';
$dynamic_metabox = new GenerateMeta( $theid_meta, 'Example Dynamic box', 'post' );
$dynamic_metabox->run( $theid_meta . '_dynamic_meta_box', 'side', 'high' );
$dynamic_metabox->saveMetadata();
//
add_action( $theid_meta . '_dynamic_meta_action', 'aa_dynamic_callback' );
//function aa_dynamic_callback( $metadata ){}
// Dynamic metabox obj end


$gallery = new GenerateMeta( 'gall_images', 'Gallery', 'page' );
$gallery->run( 'gallery_box', 'side', 'high' );
$gallery->saveMetadata();

//id , title, post_type
$mataboz = new GenerateMeta( 'project_price', 'Project price', 'recent_projects' );
$mataboz->run( 'number', 'side', 'high' );
$mataboz->saveMetadata();
echo $mataboz->renderMetadata();

$matabod = new GenerateMeta( 'short_remark', 'Short remark', 'recent_projects' );
//input_type('text' by default), placement('normal' by default), priority('default' by default)
$matabod->run( null, 'side', 'high' );
$matabod->saveMetadata();
echo $matabod->renderMetadata();

$textarea = new GenerateMeta( 'anvanced_description', 'Advanced desctiption', 'recent_projects' );
$textarea->run( 'textarea', null, 'high' );
$textarea->saveMetadata();
echo $textarea->renderMetadata();

$colorpicker = new GenerateMeta( 'color_mark', 'Color Mark', 'recent_projects' );
$colorpicker->run( 'color', 'side', 'high' );
$colorpicker->saveMetadata();

//Select
$quality = new GenerateMeta( 'project_quality', 'Project quality', 'recent_projects' );
//input_type('text' by default), placement('normal' by default), priority('default' by default) and array of options
$option_args = array(
	'one'   => array(
		'label' => 'Great Quality',
		'value' => 'great'
	),
	'two'   => array(
		'label' => 'Middle Quality',
		'value' => 'middle'
	),
	'three' => array(
		'label' => 'Lower Quality',
		'value' => 'low'
	)
);
$quality->run( 'select', 'side', 'high', $option_args );
$quality->saveMetadata();

// File
$image_second = new GenerateMeta( 'second_featured_image', 'Second Image', 'recent_projects' );
$image_second->run( 'file', 'side', 'high' );
//saveFileData() for Files
$image_second->saveFileData();
echo $image_second->renderMetadata()['url'];

//CheckboxList
$checkboxlist = new GenerateMeta( 'checkbox_list', 'Check options', 'recent_projects' );
//input_type('text' by default), placement('normal' by default), priority('default' by default) and array of options
$cxb_args = array(
	'opt_one'  => array(
		'label' => 'Option One',
		'value' => 'option_one'
	),
	'opt_two'  => array(
		'label' => 'Option Two',
		'value' => 'option_two'
	),
	'opt_tree' => array(
		'label' => 'Option Tree',
		'value' => 'option_tree'
	)
);
$checkboxlist->run( 'checkbox', 'side', 'high', $cxb_args );
$checkboxlist->saveMetadata();
//Get all metas in frontend ($arg = post_type)
//GenerateMeta::fetchAllMeta('recent_projects');

//Radiolist
$radiolist = new GenerateMeta( 'radio_list', 'Swith options', 'recent_projects' );
//input_type('text' by default), placement('normal' by default), priority('default' by default) and array of options
$rd_args = array(
	'option_one'  => array(
		'label' => 'Option One',
		'value' => 'opts_one'
	),
	'option_two'  => array(
		'label' => 'Option Two',
		'value' => 'opts_two'
	),
	'option_tree' => array(
		'label' => 'Option Tree',
		'value' => 'opts_tree'
	)
);
$radiolist->run( 'radio', 'side', 'high', $rd_args );
$radiolist->saveMetadata();

/**
 * Repeater
 */
$section_template = array(
	array(
		'type' => 'text',
		'name' => 'title',
		'value' => '',
	),
	array(
		'type' => 'image',
		'name' => 'slider-image',
		'value' => '',
	),
	array(
		'type' => 'textarea',
		'name' => 'description',
		'value' => '',
	)
);

$dynamic_metabox = new Repeater( 'pages_repeater_meta', 'Page Accordion', 'page' );
$dynamic_metabox->run( 'repeater', null, 'high', $section_template );
$dynamic_metabox->saveMetadata();