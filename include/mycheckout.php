<?php
/**
 * Template Name: mycheckout Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
$options = get_option('wepay_options');
$client_id = $options['client_secret'];
$client_secret = $options['client_secret'];
$access_token = $options['access_token'];
$account_id = $options['account_id'];

/** 
 * Initialize the WePay SDK object 
 */
 	// $file = plugin_dir_path( __FILE__ ) . 'include/wepay.php';
require 'wepay.php';
Wepay::useStaging($client_id, $client_secret);
$wepay = new WePay($access_token);

/**
 * Make the API request to get the checkout_uri
 * 
 */
try {
	$checkout = $wepay->request('/checkout/create', array(
			'account_id' => $account_id, // ID of the account that you want the money to go to
			'amount' => $_GET['a'], // dollar amount you want to charge the user
			'short_description' => "Payment", // a short description of what the payment is for
			'type' => "GOODS", // the type of the payment - choose from GOODS SERVICE DONATION or PERSONAL
			'mode' => "iframe",
				'redirect_uri' => $_GET['r']
		) 
	);
} catch (WePayException $e) { // if the API call returns an error, get the error message for display later
	$error = $e->getMessage();
}
//echo get_home_url().$_GET['r'];
?>

<html>
	<head>
	</head>
	
	<body>
		
		<h1>Checkout:</h1>
		
								
			<div id="checkout_div"></div>
		
			<script type="text/javascript" src="https://stage.wepay.com/js/iframe.wepay.js">
			</script>
			
			<script type="text/javascript">
			WePay.iframe_checkout("checkout_div", "<?php echo $checkout->checkout_uri ?>");
			</script>
		
	
	</body>
	
</html>
<?php 
get_footer();
?>

