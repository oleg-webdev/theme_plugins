<?php
/**
 * Cards
 */
if ( ! function_exists( 'am_cp_cfg' ) ) {
	function am_cp_cfg()
	{
		$slug_name = "fraction-card";
		$tax_name  = "cards-category";

		return [
			'card_slug'      => $slug_name,
			'card_label'     => ucfirst( str_replace( "-", " ", $slug_name ) ),
			'card_tax_slug'  => $tax_name,
			'card_tax_label' => ucfirst( str_replace( "-", " ", $tax_name ) ),
		];
	}
}

$labels = [
	'name'               => __( 'Cards' ),
	'singular_name'      => __( 'Card' ),
	'add_new'            => __( 'Add new' ),
	'add_new_item'       => 'Add new ' . am_cp_cfg()[ 'card_label' ],
	'edit_item'          => 'Edit ' . am_cp_cfg()[ 'card_label' ],
	'new_item'           => 'New ' . am_cp_cfg()[ 'card_label' ],
	'view_item'          => 'View ' . am_cp_cfg()[ 'card_label' ],
	'search_items'       => 'Search ' . am_cp_cfg()[ 'card_label' ],
	'not_found'          => am_cp_cfg()[ 'card_label' ] . ' not found',
	'not_found_in_trash' => 'Empty basket ' . am_cp_cfg()[ 'card_label' ],
	'parent_item_colon'  => '',
	'menu_name'          => 'Cards'
];

$args = [
	'labels'             => $labels,
	'public'             => true,
	'menu_icon'          => 'dashicons-tickets-alt',
	'publicly_queryable' => true,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => true,
	'rewrite'            => [ 'slug' => am_cp_cfg()[ 'card_slug' ] ],
	'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => true,
	'menu_position'      => null,
	'taxonomies'         => [ 'category', 'post_tag' ],
	'supports'           => [
		'title',
		'editor',
		'author',
		'thumbnail',
		'excerpt',
		'comments',
		'custom-fields',
		'page-attributes'
	]
];

if ( class_exists( 'GenerateCPosts' ) ) {
	$new_post = new GenerateCPosts( am_cp_cfg()[ 'card_slug' ], $labels, $args );
	$new_post->run( 65 );
	$new_post->taxonomy( am_cp_cfg()[ 'card_tax_slug' ], am_cp_cfg()[ 'card_tax_label' ], am_cp_cfg()[ 'card_slug' ] . '-cat' );
	$new_post->createField('img','fractionimage', 'url');
//	$new_post->addContextualHelp('Some Contextual text');
//	$new_post->postFormatSupport();
}

// Column Label
add_filter( "manage_" . am_cp_cfg()[ 'card_slug' ] . "_posts_columns", 'aa_func_20160211020209', 30, 1 );
function aa_func_20160211020209( $columns )
{
	$columns[ am_cp_cfg()[ 'card_tax_slug' ] . '_info' ] = am_cp_cfg()[ 'card_tax_label' ];

	return $columns;
}

add_filter( "manage_edit-" . am_cp_cfg()[ 'card_slug' ] . "_sortable_columns", 'aa_func_20170725030730', 31, 1 );
function aa_func_20170725030730( $columns )
{
	$columns[ am_cp_cfg()[ 'card_tax_slug' ] . '_info' ] = am_cp_cfg()[ 'card_tax_slug' ];

	return $columns;
}

add_filter( "manage_" . am_cp_cfg()[ 'card_slug' ] . "_posts_custom_column", 'aa_func_20160011020038', 32, 2 );
function aa_func_20160011020038( $column, $post_id )
{
	if ( am_cp_cfg()[ 'card_tax_slug' ] . '_info' === $column ) {
		$events_tags = get_the_terms( $post_id, am_cp_cfg()[ 'card_tax_slug' ] );
		if ( is_array( $events_tags ) ) {
			echo "<ul class='list-inline'>";
			foreach ( $events_tags as $events_tag ) {
				$tag_id   = $events_tag->term_id;
				$image_data = get_option('taxonomy_'.$tag_id);
				$tag_name = $events_tag->name;
				$tag_slug = $events_tag->slug;
				$link     = "?post_type=" . am_cp_cfg()[ 'card_slug' ] . "&" . am_cp_cfg()[ 'card_tax_slug' ] . "={$tag_slug}";
				$img = null;
				if($image_data) {
					$img = "<img src='{$image_data['url']}'>";
				}
				?>
				<li class="fraction-category-item">
					<a href="<?php echo $link ?>">
						<?php echo $img . $tag_name ?> ( <i class='fa fa-tag'></i><?php echo $tag_id ?> )
					</a>
				</li>
				<?php
			}
			echo "</ul>";
		} else {
			echo "Without any Category";
		}

	}
}