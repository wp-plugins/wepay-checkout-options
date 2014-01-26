<?php
/*
Plugin Name: Wepay checkout options
Plugin URI: http://www.faisal.com.np
Description: Settings page wepay
Author: Faisal Adil
Author URI: http://www.faisal.com.np
*/

// Specify Hooks/Filters
//register_activation_hook(__FILE__, 'add_wepay_shortcode');
add_action('admin_init', 'sampleoptions_init_fns' );
add_action('admin_menu', 'sampleoptions_add_page_fns');


function wepay_func( $atts ) {
$options = get_option('wepay_options');
$redirect_uri = $options['redirect_uri'];
$checkout_template_page_slug = $options['checkout_template_page_slug'];
     extract( shortcode_atts( array(
	      'amount' => '0',
	      'desc' => 'payment',
		  'redirect' => $redirect_uri,
		  'title' => $options['title']
		     ), $atts ) );
 

return "<a href='".get_home_url()."/{$checkout_template_page_slug}/?a={$amount}&r={$redirect}'>{$title}</a>";
}
add_shortcode( 'wepay', 'wepay_func' );


// Define default option settings
function add_defaults_fns() {
	$tmp = get_option('wepay_options');
    if(!is_array($tmp)) {
		$arr = array("timer_seconds"=>"60");
		update_option('wepay_options', $arr);
	}
}

// Register our settings. Add the settings section, and settings fields
function sampleoptions_init_fns(){
	register_setting('wepay_options', 'wepay_options', 'wepay_options_validates' );
	add_settings_section('main_section', 'Main Settings', 'section_text_fns', __FILE__);
	add_settings_field('wepay_client_id', 'Client Id:', 'setting_string_fns', __FILE__, 'main_section');
	add_settings_field('wepay_client_secret', 'Client Secret:', 'setting_clientsecret_fns', __FILE__, 'main_section');
	add_settings_field('wepay_access_token', 'Access Token:', 'setting_access_token_fns', __FILE__, 'main_section');
	add_settings_field('wepay_account_id', 'Account Id:', 'setting_account_id_fns', __FILE__, 'main_section');
	add_settings_field('wepay_title', 'Link Title:', 'setting_title_uri_fns', __FILE__, 'main_section');
	add_settings_field('wepay_checkout_template_page_slug', 'Checkout Template Slug:', 'setting_chkou_uri_fns', __FILE__, 'main_section');
	add_settings_field('wepay_redirect_uri', 'Redirect Uri:', 'setting_redirect_uri_fns', __FILE__, 'main_section');
	
}

// Add sub page to the Settings Menu
function sampleoptions_add_page_fns() {
	add_options_page('Options wepay', 'Options wepay', 'administrator', __FILE__, 'options_page_fns');
}

// ************************************************************************************************************

// Callback functions

// Section HTML, displayed before the first option
function  section_text_fns() {
	echo '<p>Add your Wepay Api Values.</p>';
}

function setting_string_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_client_id' name='wepay_options[client_id]' size='20' type='text' value='{$options['client_id']}' />";
}

function setting_clientsecret_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_client_secret' name='wepay_options[client_secret]' size='20' type='text' value='{$options['client_secret']}' />";
}

function setting_access_token_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_access_token' name='wepay_options[access_token]' size='70' type='text' value='{$options['access_token']}' />";
}

function setting_account_id_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_account_id' name='wepay_options[account_id]' size='20' type='text' value='{$options['account_id']}' />";
}
function setting_title_uri_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_title' name='wepay_options[title]' size='20' type='text' value='{$options['title']}' />";
}

function setting_chkou_uri_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_checkout_template_page_slug' name='wepay_options[checkout_template_page_slug]' size='20' type='text' value='{$options['checkout_template_page_slug']}' />";
}

function setting_redirect_uri_fns() {
	$options = get_option('wepay_options');
	echo "<input id='wepay_redirect_uri' name='wepay_options[redirect_uri]' size='40' type='text' value='{$options['redirect_uri']}' />";
}

// Display the admin options page
function options_page_fns() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>wepay Options Page</h2>
				<form action="options.php" method="post">
		<?php settings_fields('wepay_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

// Validate user data for some/all of your input fields
function wepay_options_validates($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['wepay_client_id'] =  wp_filter_nohtml_kses($input['wepay_client_id']);	
	return $input; // return validated input
}