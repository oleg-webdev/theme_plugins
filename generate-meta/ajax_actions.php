<?php
/**
 * Handle repeater data
 */

add_action( 'wp_ajax_aa_func_20152903032931', 'aa_func_20152903032931' );
function aa_func_20152903032931()
{
	if ( isset( $_POST[ 'repeater_req' ] ) ) {
		$post_id    = $_POST[ 'post_id' ];
		$meta_key   = $_POST[ 'meta_key' ];
		$meta_value = serialize( $_POST[ 'repeater_data' ] );

		update_post_meta( $post_id, $meta_key, $meta_value );

		echo $meta_value;

		die;
	}
}