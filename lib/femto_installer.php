<?php 
if (!defined('ABSPATH')){exit; // Exit if get it directly!
}

global $femto_fbpixel_dbversion;
$femto_fbpixel_dbversion = femto_fbpixel_version;

// Create table and add new options to wordpress database
if(!function_exists( 'femto_fbpixel_install' )){
function femto_fbpixel_install ()
{
	global $femto_fbpixel_dbversion;
	global $wpdb;
	
	// Check for security
	if(current_user_can('manage_options') == false){exit;}

	$charset_collate = $wpdb -> get_charset_collate();
	
	$sql = "CREATE TABLE ".$wpdb->prefix."fbpixel_events (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		postid int(11) NOT NULL,
		eventtype tinytext NOT NULL,
		eventname tinytext NOT NULL,
		posttype tinytext NOT NULL,
		extradata mediumtext NOT NULL,
		daction char(25) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	
	require_once ABSPATH .'wp-admin/includes/upgrade.php';
	dbDelta($sql);
	
	add_option('femto_fbpixel_dbversion', $femto_fbpixel_dbversion);
	add_option('femto_fbpixel_register_confrimation_msg_to_replace', 'Registration complete. Please check your email.');
	add_option('femto_fbpixel_addtocart_css_classname','');
	add_option('femto_fbpixel_addtowish_css_classname','');
	add_option('femto_fbpixel_woocommerce_integrated', '');
	add_option('femto_fbpixel_code', xss_clean($_POST['femto_fbpixel_code']));
	add_option('fb_pixel_places_homepage', 'checked');
	add_option('fb_pixel_places_pages', 'checked');
	add_option('fb_pixel_places_posts', 'checked');
	add_option('fb_pixel_places_search', 'checked');
	add_option('fb_pixel_places_categories', 'checked');
	add_option('fb_pixel_places_tags', 'checked');
	add_option('fb_pixel_places_signup', 'checked');
	add_option('fb_pixel_places_woocommerce_cart', 'checked');
	add_option('fb_pixel_places_woocommerce_checkout', '');
	add_option('fb_pixel_places_woocommerce_paymentinfo', '');
	add_option('fb_pixel_places_woocommerce_addtocart', '');
	add_option('fb_pixel_active_http_agent', 'checked');
	add_option('femto_fbpixel_item_id', '');
	
	//v 1.1.0
	add_option('fb_pixel_places_woocommerce_purchase', '');
}
}

// Insert some data to the new table
if(!function_exists( 'femto_fbpixel_install_default_events' ))
{
function femto_fbpixel_install_default_events()
{
	global $wpdb;
	
	// Check for security
	if(current_user_can('manage_options') == false){exit;}
	
	$wpdb->insert(
		$wpdb->prefix."fbpixel_events",
		array
		(
			'name' => 'Search Results',
			'eventname' => 'Search',
			'eventtype' => 'Standard',
		)
	);
	
	$wpdb->insert(
		$wpdb->prefix."fbpixel_events",
		array
		(
			'name' => 'After Sign Up',
			'eventname' => 'CompleteRegistration',
			'eventtype' => 'Standard',
		)
	);
}
}
// It will called when admin want to delete the plugin
// uninstall the tables which had been created 
// delete options which had been added
if(!function_exists( 'femto_fbpixel_uninstall' ))
{
function femto_fbpixel_uninstall()
{
	global $wpdb;
	
	// Check for security
	if(current_user_can('manage_options') == false){exit;}
	
	// Delete fbpixel_events table
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}fbpixel_events");
	
	// Delete all options
	delete_option('femto_fbpixel_dbversion');
	delete_option('femto_fbpixel_register_confrimation_msg_to_replace');
	delete_option('femto_fbpixel_addtocart_css_classname');
	delete_option('femto_fbpixel_addtowish_css_classname');
	delete_option('femto_fbpixel_code');
	delete_option('fb_pixel_places_homepage');
	delete_option('fb_pixel_places_pages');
	delete_option('fb_pixel_places_posts');
	delete_option('fb_pixel_places_search');
	delete_option('fb_pixel_places_categories');
	delete_option('fb_pixel_places_tags');
	delete_option('fb_pixel_places_signup');
	delete_option('fb_pixel_places_woocommerce_cart');
	delete_option('fb_pixel_places_woocommerce_checkout');
	delete_option('fb_pixel_places_woocommerce_paymentinfo');
	delete_option('fb_pixel_places_woocommerce_addtocart');
	delete_option('fb_pixel_active_http_agent');
	delete_option('femto_fbpixel_item_id');
	// V 1.1.0
	delete_option('fb_pixel_places_woocommerce_purchase');

}
}
//update v1.1.0
if($femto_fbpixel_dbversion !== get_option( 'femto_fbpixel_dbversion' )){
if(!function_exists('femto_fbpixel_update_1'))
{
	add_action('plugins_loaded', 'femto_fbpixel_update_1');
	function femto_fbpixel_update_1 ()
	{
		global $femto_fbpixel_dbversion;
		
		add_option('fb_pixel_places_woocommerce_purchase', '');
		update_option('femto_fbpixel_dbversion', $femto_fbpixel_dbversion);
		
		@setcookie('success_msg', '03', time()+3600);
		
	}
}
}
?>