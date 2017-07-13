<?php 
/**
 * Ajax.php
 * This file to pass all ajax actions via admin-ajax.php
 * using add_ations to wp_ajax
 * secured by creating _wpnonce and admins permissions
 */

if (!defined('ABSPATH')){exit; // Exit if get it directly!
}

/**
 * Save the general settings
 * @access public
 */
if (!function_exists( 'femto_fbpixel_saveGeneralSettings' ))
{
	add_action ('wp_ajax_femto_fbpixel_saveGeneralSettings', 'femto_fbpixel_saveGeneralSettings');
	function femto_fbpixel_saveGeneralSettings ()
	{
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_saveGeneralSettings') == false){exit;}
		
		if(!empty($_POST['femto_fbpixel_code'])){
			update_option('femto_fbpixel_code', sanitize_text_field($_POST['femto_fbpixel_code']));
		} else {
			?>
			<script>
					jQuery('.darkenBG').hide(); // Hide Loading Box and Darken Background
					alert('Please insert Facebook Pixel ID')
			</script>
			<?php
			exit;
		} 
		update_option('fb_pixel_places_homepage', sanitize_text_field( intval( $_POST['fb_pixel_places_homepage'] ) ));
		update_option('fb_pixel_places_pages', sanitize_text_field($_POST['fb_pixel_places_pages']));
		update_option('fb_pixel_places_posts', sanitize_text_field($_POST['fb_pixel_places_posts']));
		update_option('fb_pixel_places_search', sanitize_text_field($_POST['fb_pixel_places_search']));
		update_option('fb_pixel_places_categories', sanitize_text_field($_POST['fb_pixel_places_categories']));
		update_option('fb_pixel_places_tags', sanitize_text_field($_POST['fb_pixel_places_tags']));
		update_option('femto_fbpixel_addtocart_css_classname', sanitize_text_field($_POST['femto_fbpixel_addtocart_css_classname']));
		update_option('femto_fbpixel_addtowish_css_classname', sanitize_text_field($_POST['femto_fbpixel_addtowish_css_classname']));
		update_option('fb_pixel_places_woocommerce_cart', sanitize_text_field($_POST['fb_pixel_places_woocommerce_cart']));
		update_option('fb_pixel_places_woocommerce_addtocart', sanitize_text_field($_POST['fb_pixel_places_woocommerce_addtocart']));
		
		
		if($_POST['fb_pixel_places_search'] == 'checked')
		{
			femto_fbpixel_add_new_event('','Standard','Search','', 'Search Results');
		} else {
			femto_fbpixel_del_event_constant('Search');
		}
		
		
		@setcookie('success_msg', '01', time()+120);
		?>
		
		<script>
		
		setTimeout(function( $ )
				{
					jQuery('.darkenBG').hide(); // Hide Loading Box and Darken Background
					window.location.href = "<?php echo esc_url( admin_url('admin.php?page=').FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME );?>"; // Reload Dashboard 
				}
				,1500);
		</script>
		<?php
	}
	
}

/**
 * Integerate WooCommerce with FB Dynamic Pixel
 * wordpress option: femto_fbpixel_woocommerce_integrated
 */
if (!function_exists( 'femto_fbpixel_woocommerceIntergation' ))
{
	add_action ('wp_ajax_femto_fbpixel_woocommerceIntergation', 'femto_fbpixel_woocommerceIntergation');
	function femto_fbpixel_woocommerceIntergation ()
	{
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_woocommerceIntergation') == false){exit;}
		
		if (femto_fbpixel_WooCommerceCheckExists() == true)
		{
			update_option ('femto_fbpixel_woocommerce_integrated', 'active');
			?>
			<script>
			jQuery ("#loading").html ("WooCommerce Plugin: <font color=green>Installed</font><br>");
			jQuery ("#loading").append ("Integration Process: <font color=green>Done</font><br>");

			setTimeout(function( $ )
					{
						jQuery('#loading').hide(); // Hide Loading Div
						jQuery('#StpInt').show(); // Show the Stop button
						jQuery('#StartInt').hide(); // Hide the Start button
					}
					,1500);
			setTimeout(function( $ )
					{
						jQuery('#loading').show(); // Show Loading Box and Darken Background
						jQuery ("#loading").html ("<b><?php echo __('Please wait','fb-dynamic-pixel');?> </b><?php echo __('while redirecting in order to refresh the new settings...','fb-dynamic-pixel');?> ");
					}
					,1700);
			setTimeout(function( $ )
					{
				window.location.href = "<?php echo admin_url('admin.php?page=fb-dynamic-pixel');?>"; // Reload Dashboard
					}
					,2500);
			</script>
			<?php 
		} else {
			$link_to_file = ABSPATH."wp-content/plugins/woocommerce/woocommerce.php";
			?>
			<script>
			jQuery ("#loading").html ("<?php echo $link_to_file; ?> .. WooCommerce Plugin: <font color=brown><?php echo __('Not Installed','fb-dynamic-pixel');?></font><br>");
			jQuery ("#loading").append ("<?php echo __('Integration Process','fb-dynamic-pixel');?>: <font color=brown><?php echo __('Faild','fb-dynamic-pixel');?></font><br>");

			setTimeout(function( $ )
					{
						jQuery('#loading').hide(); // Hide Loading Div
						
					}
					,1500);
			</script>
			<?php 
		}
	}
}

/**
 * Stop intgeration with WooCommerce
 * Wordpress option: femto_fbpixel_woocommerce_integrated
 */
if (!function_exists( 'femto_fbpixel_woocommerceStopIntegration' ))
{
	add_action ('wp_ajax_femto_fbpixel_woocommerceStopIntegration', 'femto_fbpixel_woocommerceStopIntegration');
	function femto_fbpixel_woocommerceStopIntegration ()
	{
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_woocommerceStopIntegration') == false){exit;}
		
		// Update Option ,Set it to empty
		update_option ('femto_fbpixel_woocommerce_integrated', '');
		
		?>
		<script>
		jQuery ("#loading").html ("WooCommerce Plugin: <font color=green><?php echo __('Disintegrated Successfully','fb-dynamic-pixel');?></font><br>");
		
		setTimeout(function( $ )
				{
					jQuery('#loading').show(); // Show Loading Div
					jQuery ("#loading").html ("<b><?php echo __('Please wait','fb-dynamic-pixel');?> </b><?php echo __('while redirecting in order to refresh the new settings','fb-dynamic-pixel');?>... ");
				}
				,1300);
		setTimeout(function( $ )
				{
			window.location.href = "<?php echo admin_url('admin.php?page=fb-dynamic-pixel');?>";
				}
				,2100);
		</script>
		<?php
	}
}

/**
 * Print WooCommerce Products 
 * output: json
 */
if (!function_exists('femto_fbpixel_retrieve_autocomplete_products'))
{
	add_action('wp_ajax_femto_fbpixel_retrieve_autocomplete_products', 'femto_fbpixel_retrieve_autocomplete_products');
	function femto_fbpixel_retrieve_autocomplete_products ()
	{
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_retrieve_autocomplete_products') == false){exit;}
		
		if (!($_COOKIE['postTypefbpixel'])){$post_type = 'post';}
		else {
			$pArray = array ('post', 'page', 'product');
			$t = xss_clean($_COOKIE['postTypefbpixel']);
			if(in_array($t, $pArray))
			{
				$post_type = $t;
			} else {
				$post_type = 'post';
			}
		}

		if(!empty($_GET['q'])){
		$args = array ('post_type' => $post_type, 's' => sanitize_text_field($_GET['q']));
		$Query = new WP_Query( $args );
		}
		if ($Query -> have_posts())
		{
			while ($Query -> have_posts())
			{
				$Query -> the_post();
				
				$res[] = array ("id" => $Query->post->ID, "name" => get_the_title ());
			}
			$json_response = json_encode($res);
		}
		
		if(!empty($_GET["callback"])) {
	    $json_response = $_GET["callback"] . "(" . $json_response . ")";
		}
		
		//Print JSON
		echo $json_response;
		wp_reset_postdata(); exit();
	}
}

// store session/cookie for the post_type to ensure what data will be loaded via autocomplete function
// Cookie: postTypefbpixel
if (!function_exists('femto_fbpixel_register_onchangesession'))
{
	add_action('wp_ajax_femto_fbpixel_register_onchangesession', 'femto_fbpixel_register_onchangesession');
	function femto_fbpixel_register_onchangesession ()
	{
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_register_onchangesession') == false){exit;}
		
			$t = xss_clean($_GET['t']);
			$pArray = array ('post', 'page', 'product');
			if(in_array($t, $pArray))
			{
				$post_type = $t;
			} else {
				$post_type = 'post';
			}
		setcookie('postTypefbpixel',$post_type, time()+300);
		
		exit ();
	}
}

// Load events list fron database (tabel: fbpixel_events)
if (!function_exists('femto_fbpixel_loadEventsList'))
{
	add_action ('wp_ajax_femto_fbpixel_loadEventsList','femto_fbpixel_loadEventsList');
	function femto_fbpixel_loadEventsList ()
	{
		global $wpdb;
		global $post;
		
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_loadEventsList') == false){exit;}
		
		// Pass this action via admin-ajax.php in order to delete event without reloading the page
		$femto_fbpixel_delEventURL = add_query_arg(array(
		'action' => 'femto_fbpixel_delEvent',
		'_wpnonce' => wp_create_nonce('femto_fbpixel_delEvent')
		), admin_url( 'admin-ajax.php' ));
		
		$results = $wpdb->get_results ("SELECT id,eventname,eventtype,name,postid FROM " .$wpdb->prefix. "fbpixel_events ORDER BY id DESC");
		
		if($results)
		{
			$output = "<div id=results></div><h3>".__('Events List', 'fb-dynamic-pixel')."</h3><hr>";
			$output .= '<table class="wp-list-table widefat fixed striped posts">
	<thead><tr>
	<td  class="manage-column column-title column-primary sortable desc">'.__('Title/Link', 'fb-dynamic-pixel').'</td>
	<td  class="manage-column column-title column-primary sortable desc">'.__('Event Type', 'fb-dynamic-pixel').'</td>
	<td  class="manage-column column-title column-primary sortable desc">'.__('Event Action', 'fb-dynamic-pixel').'</td>
	<td class="manage-column column-author">'.__('Manage', 'fb-dynamic-pixel').'</td>
	</tr></thead><tbody>';
			foreach ($results as $result)
			{
				if(!empty($result->postid)){$link = esc_url(get_page_link(esc_html( $result->postid )));}
				else{$link = "#";}
				$tools = "<a href='$link' target=_blank>".__('Visit Page', 'fb-dynamic-pixel')."</a><br>
				<span id='fbpixel_del_event".$result->id."' class='fbpixel_del_event'>".__('Delete Event', 'fb-dynamic-pixel')."</span>";
				$output .= "<tr id='col".$result->id."'><td class='title column-title has-row-actions column-primary page-title'><b>".esc_html( $result->name )."</b><br><span class=smallfont>".
				$link."</span></td>";
				$output .= "<td class='title column-title has-row-actions column-primary page-title'>".esc_html( $result->eventtype )."</td>";
				$output .= "<td class='title column-title has-row-actions column-primary page-title'>".esc_html( $result->eventname )."
				<br>".femto_fbpixel_eventCodePreview($result->id)."</td>";
				$output .= "<td class='title column-title has-row-actions column-primary page-title'>".$tools."</td></tr>";
				
				$output .='
			<script>
jQuery("#fbpixel_del_event'.esc_html( $result->id ).'").click(function($)
		{
jQuery("#results").load("'.esc_url_raw( $femto_fbpixel_delEventURL.'&id='.$result->id ).'");
		}
		);
			</script>';
			 
			}
			$output .='</tbody></table>';
			
			echo $output;
			
		}
			
		exit();
	}
}

// Install new event
// $extraData for all custom paramters to Facebook pixel. It will be stored as json in database.
if (!function_exists('femto_fbpixel_installNewEvent'))
{
	add_action ('wp_ajax_femto_fbpixel_installNewEvent', 'femto_fbpixel_installNewEvent');
	function femto_fbpixel_installNewEvent ()
	{
		global $post;
		global $wpdb;
		global $loadEventsList;
		
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_installNewEvent') == false){exit;}
		
		$post_type = trim(sanitize_text_field( $_POST['post_type'] ));
		$fbpixel_standard_event = trim(sanitize_text_field( $_POST['fbpixel_standard_event'] ));
		$femto_fbpixel_event_id = trim( intval( $_POST['femto_fbpixel_event_id'] ));
		$customevent = trim(sanitize_text_field( $_POST['customevent'] ));
		$daction = trim(sanitize_text_field( $_POST['daction'] ));
		
		if ($customevent != ''){$the_event = $customevent; $eventtype = 'Custom';} 
		else {$the_event = $fbpixel_standard_event; $eventtype = 'Standard';}
		
		if($post_type == 'post'){$content_type = 'article';}
		else if($post_type == 'page'){$content_type = 'website';}
		else if($post_type == 'product'){$content_type = 'product';}
		
		$extradata_ar = array();
		if($_POST['eventvalue']!=''){$extradata_ar['value'] = trim(sanitize_text_field( $_POST['eventvalue'] ));}
		if($_POST['eventcurrency']!=''){$extradata_ar['currency'] = trim(sanitize_text_field( $_POST['eventcurrency'] ));}
		if($_POST['eventnumitems']!=''){$extradata_ar['num_items'] = trim(sanitize_text_field( $_POST['eventnumitems'] ));}
		
		if($_POST['customkey'] != '')
		{
			
			for($i=0; $i<@count($_POST['customkey']); $i++)
			{
				$exp0 = @explode("#", $_POST['customkey'][$i]);
				$exp1 = @explode("#", $_POST['customvalue'][$i]);
				if($exp0[0] != '')
				$extradata_ar[$exp0[0]] = $exp1[0];
			}
		}
		
		$extraData = json_encode($extradata_ar);
		if($femto_fbpixel_event_id > 0){
			femto_fbpixel_add_new_event($femto_fbpixel_event_id,$eventtype,$the_event,$post_type, '', $extraData, $daction);
		} else {
			?>
			<script>
					jQuery('.darkenBG').hide(); // Hide Loading Box and Darken Background
					alert('Please choose post,page or product to install new event');
			</script>
			<?php
			exit;
		}
		?>
		<script>
		jQuery(".darkenBG").hide();  // Hide Loading Box and Darken Background
		jQuery(document).ready(function(){location.reload();}); // Reload Dashboard
		</script>
		<?php 
		exit();
	}
}

// Delete event from database using its unique ID.
// Hide event row.
if (!function_exists('femto_fbpixel_delEvent'))
{
	add_action('wp_ajax_femto_fbpixel_delEvent', 'femto_fbpixel_delEvent');
	function femto_fbpixel_delEvent ()
	{
		global $wpdb;
		
		// Verify User Permissions
		if(current_user_can('manage_options') == false) {exit;}
		// Verify wpnonce
		if(empty($_GET['_wpnonce']) || wp_verify_nonce($_GET['_wpnonce'], 'femto_fbpixel_delEvent') == false){exit;}
		
		$id = intval( $_GET['id'] );
		
		$postid = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix."fbpixel_events WHERE id='$id'");
		if($postid->eventname == 'Search')
		{
			update_option('fb_pixel_places_search', '');
		} else if($postid->eventname == 'CompleteRegistration')
		{
			update_option('fb_pixel_places_signup', '');
		}
		$wpdb->delete ($wpdb->prefix."fbpixel_events", array ('id' => $id));
		
		?>
		<script>
jQuery("#col<?php echo $id;?>").hide(300); // Hide Column by ID
		</script>
		<?php 
		
		exit();
	}
}

?>