<?php
/*
Plugin Name: Custom Posts Generator
Plugin URI: http://www.upwork.com/fl/olegtsibulnik
Description: Generate Custom posts plugin - Upload and Activate.
Author: Alicelf WebArtisan
Version: 2.7.1
Author URI: http://www.upwork.com/fl/olegtsibulnik
*/

class GenerateCPosts {

	public $help;
	public $args;
	public $labels;
	public $post_type;
	public $taxonomy;
	public $taxonomy_label;
	public $taxonomy_slug;
	public $array_post_holder;
	public $type_of_field;
	public $array_post_value;
	public $position;

	public function __construct( $post_type, $labels, $args )
	{
		if ( is_array( $labels ) && is_array( $args ) ) {
			$this->post_type = $post_type;
			$this->labels    = $labels;
			$this->args      = $args;
		}
	}

	public function messagesMethod( $messages )
	{
		global $post, $post_ID;

		$messages[ $this->post_type ] = [
			0  => '',
			1  => sprintf( __( $this->post_type . ' updated. <a href="%s">View</a>' ), esc_url( get_permalink( $post_ID ) ) ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.' ),
			4  => __( $this->post_type . ' updated.' ),
			5  => isset( $_GET[ 'revision' ] ) ? sprintf( __( $this->post_type . ' restored to revision from %s' ), wp_post_revision_title( (int) $_GET[ 'revision' ], false ) ) : false,
			6  => sprintf( __( $this->post_type . ' published. <a href="%s">View </a>' ), esc_url( get_permalink( $post_ID ) ) ),
			7  => __( $this->post_type . ' saved.' ),
			8  => sprintf( __( $this->post_type . ' submitted. <a target="_blank" href="%s">Preview</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			9  => sprintf( __( $this->post_type . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview</a>' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
			10 => sprintf( __( $this->post_type . ' draft updated. <a target="_blank" href="%s">Preview</a>' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		];

		return $messages;
	}

	public function conHelpCallback( $contextual_help, $screen_id, $screen )
	{
		//$contextual_help .= var_dump($screen); // Use this for detect parameter $screen->id
		if ( $this->post_type == $screen->id ) {
			$contextual_help = $this->help;
		} elseif ( 'edit-' . $this->post_type == $screen->id ) {
			$contextual_help = '<p>This category for ' . $this->post_type . ' articles</p>';
		}

		return $contextual_help;
	}

	public function run( $position = null )
	{
		$this->position                = ! empty( $position ) ? $position : null;
		$this->args[ 'menu_position' ] = $this->position;
		register_post_type( $this->post_type, $this->args );

		add_filter( 'post_updated_messages', [ $this, 'messagesMethod' ] );
	}

	public function addContextualHelp( $help = null )
	{
		if ( $help ) {
			$this->help = $help;
		} else {
			$this->help = 'Without Helpers';
		}

		add_action( 'contextual_help', [ $this, 'conHelpCallback' ], 10, 3 );
	}

	public function postFormatSupport()
	{
		add_post_type_support( $this->post_type, 'post-formats' );
	}

	public function customTaxonomy()
	{
		// create a new taxonomy
		register_taxonomy(
			$this->taxonomy,
			$this->post_type,
			[
				'hierarchical' => true,
				'labels'       => [
					'name'              => $this->taxonomy_label,
					'singular_name'     => 'Single ' . $this->taxonomy_label,
					'search_items'      => 'Search for: ' . $this->taxonomy_label,
					'all_items'         => 'All ' . $this->taxonomy_label,
					'parent_item'       => 'Parent of ' . $this->taxonomy_label,
					'parent_item_colon' => 'Parent of ' . $this->taxonomy_label,
					'edit_item'         => 'Edit ' . $this->taxonomy_label,
					'update_item'       => 'Update ' . $this->taxonomy_label,
					'add_new_item'      => 'Add New ' . $this->taxonomy_label,
					'new_item_name'     => 'New ' . $this->taxonomy_label,
					'menu_name'         => $this->taxonomy_label,
				],
				'rewrite'      => [
					'slug'         => $this->taxonomy_slug,
					'hierarchical' => true
				],
				'capabilities' => [
					'assign_terms' => 'edit_posts',
					'edit_terms'   => 'publish_posts'
				]
			]
		);
	}

	/**
	 * Generate custom taxonomy (via tags) dont forget add to args array in taxonomies
	 *
	 * @param $tax
	 * @param $label_taxonomy
	 * @param $slug_taxonomy
	 */
	public function taxonomy( $tax, $label_taxonomy, $slug_taxonomy )
	{
		$this->taxonomy       = $tax;
		$this->taxonomy_label = $label_taxonomy;
		$this->taxonomy_slug  = $slug_taxonomy;
		add_action( 'init', [ $this, 'customTaxonomy' ] );
	}

	/**
	 * Custom field for custom taxonomy creation process
	 *
	 * @Template Todo: Create field types
	 */
	public function initializeCustomField()
	{
		$temp_var          = $this->array_post_holder . "[" . $this->array_post_value . "]";
		$image_placeholder = "<img class='al-meta-custom-image' src='http://placehold.it/100x50'>";
		wp_enqueue_media();

		echo "<div class='form-field al-tax-custom-field'>";
		switch ( $this->type_of_field ) {
			case 'img' :
				?>
				<div class='al-taxonomy-image-holder row'>
					<div class='col-sm-4 img-placeholder'><?php echo $image_placeholder ?></div>
					<div class='col-sm-4'>
						<input type='text' class='al-taxonomy-image'
						       name='<?php echo $temp_var ?>' id='<?php echo $temp_var ?>' value=''>
						<div class='clearfix'>
							<button class='btn btn-default btn-upload' type='button'>Add Image</button>
						</div>
					</div>
				</div>
				<?php

				break;
			default :
				?>
				<input type='text' name='<?php echo $temp_var ?>' id='<?php echo $temp_var ?>' value=''>
				<?php
				;
		}

		echo "</div>";
	}

	public function editCustomField( $term )
	{
		// Taxonomy id
		$term_id = $term->term_id;
		// retrieve the existing value(s) for this meta field. This returns an array
		$term_meta_value = get_option( "taxonomy_$term_id" );
		$temp_var        = $this->array_post_holder . "[" . $this->array_post_value . "]";
		$term_ini        = $term_meta_value[ $this->array_post_value ];
		$label_field     = "<label for='$temp_var'><b>" . ucfirst( str_replace( '_', ' ', $this->array_post_value ) ) . "</b></label>";
		$img_element     = empty( $term_ini ) ? "<img src='http://placehold.it/100x50'>" : "<img src='$term_ini'>";
		wp_enqueue_media();

		switch ( $this->type_of_field ) {
			case 'img' :
				?>
				<tr class='form-field al-taxonomy-image-holder al-tax-custom-field'>
					<td scope='row' valign='top'><?php echo $label_field ?></td>
					<td>
						<div class="img-placeholder"><?php echo $img_element ?></div>
						<div>
							<input type='text' class="al-taxonomy-image"
							       name='<?php echo $temp_var ?>'
							       id='<?php echo $temp_var ?>' value='<?php echo $term_ini ?>'>
							<hr>
							<button class='btn btn-default btn-upload' type='button'>Edit Image</button>
						</div>
					</td>
				</tr>
				<?php
				break;

			default:
				?>
				<tr>
					<td scope='row' valign='top'>
						<?php echo $label_field ?>
					</td>
					<td>
						<input type='text'
						       name='<?php echo $temp_var ?>'
						       id='<?php echo $temp_var ?>' value='<?php echo $term_ini ?>'>
					</td>
				</tr>
				<?php
		}

	}

	public function saveCustomField( $term_id )
	{
		if ( isset( $_POST[ $this->array_post_holder ] ) ) {
			$post_data    = $_POST[ $this->array_post_holder ];
			$term_summary = [];

			foreach ( $post_data as $key => $value ) {
				if ( isset ( $post_data[ $key ] ) ) {
					$term_summary[ $key ] = $value;
				}
			}
			update_option( "taxonomy_$term_id", $term_summary );
		}
	}

	/**
	 * @param $type_of_field = input type(text,number, textarea, upload field, etc.)
	 * @param $array_post_holder = key of post array array_post_holder[....]
	 * @param $array_post_value = value of post array ...[array_post_value]
	 * @param null $another_tax = if another taxonomy
	 */
	public function createField( $type_of_field, $array_post_holder, $array_post_value, $another_tax = null )
	{
		// type of generated field
		$this->type_of_field = $type_of_field;
		// for 'somearray_name[...]'
		$this->array_post_holder = $array_post_holder;
		// for ...[somevalue_of_field]
		$this->array_post_value = $array_post_value;

		$current_taxonomy = empty( $another_tax ) ? $this->taxonomy : $another_tax;
		// For view
		add_action( $current_taxonomy . '_edit_form_fields', [ $this, 'editCustomField' ] );
		add_action( $current_taxonomy . '_add_form_fields', [ $this, 'initializeCustomField' ] );

		// For save
		add_action( 'edited_' . $current_taxonomy, [ $this, 'saveCustomField' ], 10, 2 );
		add_action( 'create_' . $current_taxonomy, [ $this, 'saveCustomField' ], 10, 2 );
	}
}