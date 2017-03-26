<?php
/*
Plugin Name: Custom Captcha
Plugin URI: http://www.upwork.com/fl/olegtsibulnik
Description: Captcha - Upload and Activate.
Author: Alicelf WebArtisan
Version: 1.3.1
Author URI: http://www.upwork.com/fl/olegtsibulnik
*/
add_action('wp_print_styles', 'secureimage_style');
function secureimage_style() {
	wp_enqueue_style('secure_image_few_styles', plugins_url('/captcha_style.css', __FILE__));
}

/**
 * ==================== Comments and Contact form ======================
 * 23.07.2016
 */
add_action('comment_form_after_fields', 'captcha_init_incapsulator');
add_action('alice_contact_captha', 'captcha_init_incapsulator');
function captcha_init_incapsulator () {
	$img_src = plugin_dir_url(__FILE__).'securimage/securimage_show.php';
	?>
	<div class="captha-holder">
		<img id="captcha" src="<?php echo $img_src ?>" alt="CAPTCHA Image" />
		<input type="text" name="captcha_code" size="10" maxlength="6" />
		<a class="btn btn-default" href="#" onclick="document.getElementById('captcha').src = '<?php echo $img_src ?>?sid=' + Math.random(); return false">Change Image</a>
	</div>
	<?php
}
add_filter( 'preprocess_comment', 'verify_comment_meta_data' );

function verify_comment_meta_data( $commentdata ) {
	include_once plugin_basename( '/securimage/securimage.php' );
	$securimage = new Securimage();
	if ( $securimage->check($_POST['captcha_code']) == false) {
		wp_die( __( 'Error: please fill correct the captcha.' ) );
	}
	return $commentdata;
}


/**
 * ==================== Captcha For login form ======================
 * 23.07.2016
 */
add_action( 'login_form', 'aa_func_20160423060401' );
function aa_func_20160423060401()
{
	$img_src = plugin_dir_url(__FILE__).'securimage/securimage_show.php';
	?>
	<div class="captha-holder row">
		<div class="col-sm-6">
			<img id="captcha" src="<?php echo $img_src ?>" alt="CAPTCHA Image" />
		</div>
		<div class="col-sm-6">
			<input type="text" name="captcha_code" size="10" maxlength="6" />
			<a class="btn btn-default btn-fullwidth" href="#" onclick="document.getElementById('captcha').src = '<?php echo $img_src ?>?sid=' + Math.random(); return false">Change Image</a>
		</div>
	</div>
	<?php
}

add_filter( 'authenticate', 'aa_func_20162223062249', 20, 1 );
function aa_func_20162223062249( $user )
{
	$data = $_POST;
	if ( isset( $data[ 'captcha_code' ] ) ) {
		include_once plugin_basename( '/securimage/securimage.php' );
		$securimage = new Securimage();
		if ( $securimage->check( $_POST[ 'captcha_code' ] ) == false ) {
			$user = new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Captha failed' ) );
		}
	}
	return $user;
}