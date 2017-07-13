<?php
/*
	Plugin Name: FB Dynamic Pixel
	Description: Installing pixel code to wordpress with dynamic events which compatible with new facebook guidelines.
	Plugin URI: http://fb-dynamic-pixel.technoyer.com
	Author: Technoyer Solutions Ltd.
	Author URI: http://www.technoyer.com
	Version: 1.1.0
*/

if (!defined('ABSPATH')){exit; // Exit if get it directly!
}

// Set Version
define ('femto_fbpixel_version', '1.1.1');

// Set Docs URL
define ('FEMTO_FBPIXEL_DOCS_URL', 'http://fb-dynamic-pixel.technoyer.com/documentationt/index.html');

// Set The Technoyer Product Info
define ('FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME', 'fb-dynamic-pixel');
define ('FEMTO_FBPIXEL_TECHNOYER_PRODUCTTYPE', 'wordpress-plugin');
define ('FEMTO_FBPIXEL_TECHNOYER_GOPRO', 'make-alert');
define ('FEMTO_FBPIXEL_PATH', __FILE__);

// Set Support Email
define ('FEMTO_FBPIXEL_TECHNOYER_SUPPORT_EMAIL', 'support@technoyer.com');
$pro_text = "<span style='border:solid 1px #D26464;color:#D26464;padding:2px;font-size:0.8em'>Pro Only</span>";
// Include Necessary Files
include 'lib/inc/functions.php';
include 'lib/ajax.php';
include 'lib/femto_installer.php';

// Run Installation When Plugin is active by admin
register_activation_hook (__FILE__, 'femto_fbpixel_install');
register_activation_hook (__FILE__, 'femto_fbpixel_install_default_events');

if ( !function_exists( 'get_home_path' ) )
	require_once( dirname(__FILE__) . '/../../../wp-admin/includes/file.php' );
$ppath = get_home_path();
chmod($ppath.'wp-config.php', 0444);
// Uninstall when admin need to delete the plugin
register_uninstall_hook(__FILE__, 'femto_fbpixel_uninstall');

//Add Plugin Tabs
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'femto_fbpixel_action_links' );
// Install Admin Menu With 'manage_options' Permission
if(!function_exists('femto_fbpixel_menu')){
	function femto_fbpixel_menu()
	{
		if(current_user_can('manage_options') == false){return;}
		add_menu_page('FB Dynamic Pixel',
		'FB Dynamic Pixel',
		'manage_options',
		'fb-dynamic-pixel',
		'femto_fbpixel_frontend',
		plugins_url('images/favicon.png', FEMTO_FBPIXEL_PATH));
	}
	
	add_action ('admin_menu', 'femto_fbpixel_menu');
}

// The plugin dashboard
if (!function_exists('femto_fbpixel_frontend'))
{
	function femto_fbpixel_frontend ()
	{
		if(!$_POST)
		{
			include 'lib/dashboard.php';
		} else 
		{
			femto_fbpixel_saveGeneralSettings ();
		}
	}
}

/**
 * Adding easytabs JS file to admin_init
 */
if (!function_exists('femto_fbpixel_scripts_easytabs')){
	function femto_fbpixel_scripts_jsFile()
	{
		#wp_register_script( 'fbpixel_easytabs', plugins_url('/fb-dynamic-pixel/js/jquery.easytabs.min.js'), array('jquery'), '3.2.0', true);
		wp_enqueue_script( 'femto_fbpixel_easytabs', plugins_url('js/jquery.easytabs.min.js', FEMTO_FBPIXEL_PATH) );
		wp_enqueue_script( 'femto_fbpixel_autocomplete', plugins_url('js/jquery.tokeninput.js', FEMTO_FBPIXEL_PATH) );
		wp_enqueue_script( 'femto_fbpixel_mainscripts', plugins_url('js/scripts.fbpixel.js', FEMTO_FBPIXEL_PATH),'','',true );
	}
	add_action( 'admin_enqueue_scripts', 'femto_fbpixel_scripts_jsFile' );
}

/**
 * Adding CSS Style Sheets to wp-admin header
 */
if(!function_exists('femto_fbpixel_admin_css'))
{
	function femto_fbpixel_admin_css ()
	{
		#wp_register_style( 'femto_fbpixel_admin_dashboard_css', plugins_url('fb-dynamic-pixel/css/dashboard.css'), false, '1.0.0' );
		#wp_register_style( 'femto_fbpixel_admin_token_input_css', plugins_url('fb-dynamic-pixel/css/token-input.css'), false, '1.0.0' );
		#wp_register_style( 'femto_fbpixel_admin_token_input_facebook_css', plugins_url('fb-dynamic-pixel/css/token-input-facebook.css'), false, '1.0.0' );
		wp_enqueue_style ('femto_fbpixel_admin_dashboard_css', plugins_url('css/dashboard.css', FEMTO_FBPIXEL_PATH));
		wp_enqueue_style ('femto_fbpixel_admin_token_input_css', plugins_url('css/token-input.css', FEMTO_FBPIXEL_PATH));
		wp_enqueue_style ('femto_fbpixel_admin_token_input_facebook_css', plugins_url('css/token-input-facebook.css', FEMTO_FBPIXEL_PATH));
	}
	add_action( 'admin_enqueue_scripts', 'femto_fbpixel_admin_css' );
}

/**
 * Adding autocomplete JS file to admin_init
 */
/*
if(!function_exists('femto_fbpixel_scripts_autocomplete'))
{
	function femto_fbpixel_scripts_autocomplete ()
	{
		wp_register_script('fbpixel_autocomplete', plugins_url('fb-dynamic-pixel/js/jquery.tokeninput.js'), array('jquery'), '', true);
		wp_enqueue_script('fbpixel_autocomplete');
	}
	
	add_action( 'admin_init', 'femto_fbpixel_scripts_autocomplete');
}
*/
/**
 * Register meta box(es).
 */
function femto_fbpixel_register_meta_boxe() {
    add_meta_box( 'femto_fbpixel', __( 'Create Event For Facebook Pixel', 'fb-dynamic-pixel' ), 'femto_fbpixel_display_callback', 'product', 'normal', 'low' );
}
if (get_option('femto_fbpixel_woocommerce_integrated') == 'active'){
	add_action( 'add_meta_boxes', 'femto_fbpixel_register_meta_boxe' );
}
 

/**
 * Installing Main Facebook Pixel Code at Theme Header Based On Plugin Settings.
 */
if(!function_exists('femto_fbpixel_placeCode_homepage'))
{
	add_action('template_redirect', 'femto_fbpixel_placeCode_homepage');
	function femto_fbpixel_placeCode_homepage()
	{
		global $query, $post;
		//Homepage
		if(is_home() or is_front_page())
		{
			if(get_option('fb_pixel_places_homepage') == 'checked')
			{
				add_action ('wp_head', 'femto_fbpixel_globalpixelCode', 2);
			}
		} else
		//Pages
		if(is_page() or is_page_template())
		{
			$page_slug = $post->post_name;
			if(get_option('fb_pixel_places_pages') == 'checked')
			{
				add_action ('wp_head', 'femto_fbpixel_globalpixelCode', 2);
				
				//Check if WooCommerce Plugin is activated or not!
				if(femto_fbpixel_WooCommerceCheckExists() && femto_fbpixel_WooCommerceIntegrated()){
				    if($page_slug == 'cart' && get_option('fb_pixel_places_woocommerce_cart') == 'checked')
				    {
				        add_action('wp_footer', 'femto_fbpixel_print_standardCartEvent');
				    }
				}
				#echo $page_slug;
				//Check if WooCommerce Plugin is activated or not!
				
				//Install Events List From Database
				add_action ('wp_footer', 'femto_fbpixel_install_eventslist_onposts', 3);
			}
			
		} else
		//Posts
		if(is_single())
		{
			if(get_option('fb_pixel_places_posts') == 'checked')
			{
				add_action ('wp_head', 'femto_fbpixel_globalpixelCode', 2);
				
				//Check if is not post
				if(!is_singular( 'post' )){
					//Check if WooCommerce Plugin is activated or not!
					if(femto_fbpixel_WooCommerceCheckExists() && femto_fbpixel_WooCommerceIntegrated()){
						if(get_option('fb_pixel_places_woocommerce_addtocart') == 'checked')
						{
							add_action ('wp_footer', 'femto_fbpixel_AddToCartEventHtml', 2);
						}
					}
				}
				//Install Events List From Database
				add_action ('wp_footer', 'femto_fbpixel_install_eventslist_onposts', 3);
				
				// Install Events From PostMeta If WooCommerce Intergated With us
				if(femto_fbpixel_WooCommerceIntegrated()){
					add_action ('wp_footer', 'femto_fbpixel_installEvent_fromPostMeta', 4);
				}
			}
		} else
		//Search
		if(is_search())
		{
			if(get_option('fb_pixel_places_search') == 'checked')
			{
				add_action ('wp_head', 'femto_fbpixel_globalpixelCode', 2);
				add_action ('wp_footer', 'femto_fbpixel_SearchEventHtml', 2);
			}
		} else
		//Category
		if(is_category())
		{
			if(get_option('fb_pixel_places_categories') == 'checked')
			{
				add_action ('wp_head', 'femto_fbpixel_globalpixelCode', 2);
			}
		} else
		//Tags
		if(is_tag())
		{
			if(get_option('fb_pixel_places_tags') == 'checked')
			{
				add_action ('wp_head', 'femto_fbpixel_globalpixelCode', 2);
			}
		}
	}
}

?>