<?php 
/**
 * Functions will be required in all the plugin files
 * All of it written by Technoyer @2017
 */
if (!defined('ABSPATH')){exit; // Exit if get it directly!
}

// Events data for drowdown list
if (!function_exists('femto_fbpixel_EventsDropDownList'))
{
	function femto_fbpixel_EventsDropDownList ()
	{
		$listAr = array (
		'ViewContent',
		'Search',
		'AddToCart',
		'AddToWishlist',
		'InitiateCheckout',
		'AddPaymentInfo',
		'Purchase',
		'Lead',
		'CompleteRegistration'
		);
		
		return $listAr;
	}
}

// Retrieve the post meta event.
// Under the WooCommerce edit box, There were some meta to install new event added by Fb Dynamic Pixel
// All theses meta stored in post_meta, now we need to get it to run event
if(!function_exists('femto_fbpixel_installEvent_fromPostMeta'))
{
	function femto_fbpixel_installEvent_fromPostMeta ( )
	{
		global $post;
		
		//Check if is product and is WooCommerce product
		if(is_singular( 'product' ) && femto_fbpixel_WooCommerceCheckExists())
		{
		$event = esc_html( get_post_meta($post->ID, 'femto_fbpixel_woocommerce_event', true) );
		$daction = esc_html( get_post_meta($post->ID, 'femto_fbpixel_daction', true) );
		$includePrice = esc_html( get_post_meta($post->ID, 'femto_fbpixel_includePrice', true) );
		$includeCurrency = esc_html( get_post_meta($post->ID, 'femto_fbpixel_includeCurrency', true) );
		$includeName = esc_html( get_post_meta($post->ID, 'femto_fbpixel_includeProductname', true) );
		$includeCategory = esc_html( get_post_meta($post->ID, 'femto_fbpixel_includeCategory', true) );
		$includeId = esc_html( get_post_meta($post->ID, 'femto_fbpixel_includeProductID', true) );
		
		if(!empty($event))
		{
			if($daction == 'onclickaddtocart'){
				$cssClassName = esc_html( get_option('femto_fbpixel_addtocart_css_classname') );
				$cssClassName = ".".str_replace(" ",".",$cssClassName);	
			} else if($daction == 'onclickaddtowish'){
				$cssClassName = esc_html( get_option('femto_fbpixel_addtowish_css_classname') );
				$cssClassName = ".".str_replace(" ",".",$cssClassName);	
			} else {
				
			}
			$action_is_onClick = '';
			
				$_product = wc_get_product( $post->ID );
				$product_price = $_product->get_price();
				$product_currency = get_woocommerce_currency();
			
			
			// Product Name
			$product_name = get_the_title($post->ID);
			
			// Get The Product Category Name
			$term_list = wp_get_post_terms($post->ID,'product_cat',array('fields'=>'ids'));
			$cat_id = (int)$term_list[0];
			$category = get_term ($cat_id, 'product_cat');
			$product_category = esc_html( $category->name );
			
			$extraData = "";
			// Extra Data // Price
			if($includePrice == 1){$extraData .="\nvalue: '".$product_price."',";}
			//Extra Data // Category
			if($includeCategory == 1){$extraData .="\ncontent_category: '".$product_category."',";}
			//Extra Data // Currency
			if($includeCurrency == 1){$extraData .="\ncurrency: '".$product_currency."',";}
			//Extra Data // Currency
			if($includeName == 1){$extraData .="\ncontent_name: '".$product_name."',";}
			//Extra Data // Currency
			if($includeId == 1){$extraData .="\ncontent_ids: '".$post->ID."',";}
			
			$output = "<script>";
			
			$output .= "\nfbq('track', '".$event."',";
			$output .= "\n{";
			if(!empty($extraData))
			{
				$output .= $extraData;
			}
			
			$output .= "\n});";
			if($action_is_onClick == 'yes')
			{
				$output .= "\n});";
			}
			$output .= "</script>";
			
			echo $output;
		}
		}
	}
}

// Events list for WooCommerce products dropdown menu
if (!function_exists('femto_fbpixel_EventsDropDownList_WooCommerce'))
{
	function femto_fbpixel_EventsDropDownList_WooCommerce ()
	{
		$listAr = array (
		'ViewContent',
		'AddToCart',
		'AddToWishlist',
		'InitiateCheckout',
		'AddPaymentInfo',
		'Purchase',
		'Lead',
		);
		
		return $listAr;
	}
}

if(!function_exists( 'femto_fbpixel_display_callback' )){
/**
 * Meta box display callback!
 * Html content for Fb Dynamic Pixel meta box under the WooCommerce editor
 * 
 * @param WP_Post $post Current post object.
 */
function femto_fbpixel_display_callback( $post ) {
     $listAr = femto_fbpixel_EventsDropDownList_WooCommerce ();
     
     $dropdown = "\n
     <select name=\"femto_fbpixel_woocommerce_event\" id='pevents'>";
     $dropdown .= "<option value=''> -- Select The Appropriate Event</option>";
     
     $curentEventValue = esc_html( get_post_meta ($post->ID, 'femto_fbpixel_woocommerce_event', true) );
     
     foreach ($listAr as $key)
     {
     	$dropdown .= "<option value='".$key."' id='".$key."' ";
     	if ($curentEventValue == $key){$dropdown .= "selected";}
     	$dropdown .=">&nbsp; ---&nbsp; ".$key."</option>";
     }
     $dropdown .= "</select>";
    
     $output = $dropdown."<br />";
     $output .= "<p id='ppricep'><input type='checkbox' id='pprice' name='femto_fbpixel_includePrice' value='1' ";
     if (esc_html( get_post_meta ($post->ID, 'femto_fbpixel_includePrice', true) ) == 1){$output .= "checked";}
     $output .= "> Include Product Price</p>";
     $output .= "<p id='pcurrencyp'><input type='checkbox' id='pcurrency' name='femto_fbpixel_includeCurrency' value='1' ";
	 if (esc_html( get_post_meta ($post->ID, 'femto_fbpixel_includeCurrency', true) ) == 1){$output .= "checked";}
     $output .= "> Include Currency</p>";
     $output .= "<p id='pnamep'><input type='checkbox' id='pname' name='femto_fbpixel_includeProductname' value='1' ";
	 if (esc_html( get_post_meta ($post->ID, 'femto_fbpixel_includeProductname', true) ) == 1){$output .= "checked";}
     $output .= "> Include Product Name</p>";
     $output .= "<p id='pcategoryp'><input type='checkbox' id='pcategory' name='femto_fbpixel_includeCategory' value='1' ";
	 if (esc_html( get_post_meta ($post->ID, 'femto_fbpixel_includeCategory', true) ) == 1){$output .= "checked";}
     $output .= "> Include Category</p>";
     $output .= "<p id='pidp'><input type='checkbox' id='pid' name='femto_fbpixel_includeProductID' value='1' ";
	 if (esc_html( get_post_meta ($post->ID, 'femto_fbpixel_includeProductID', true) ) == 1){$output .= "checked";}
     $output .= "> Include Product ID</p>";
    
     
     global $pro_text;
     
     $output .= "<input type='hidden' name='femto_fbpixel_ContentType' value='product'><hr>";
     $output .= '<b>Dynamic Action</b><p>
        <input type="radio" name="femto_fbpixel_daction" value="onpageload" id="onpageload" checked';
     $output .= '><label for="onpageload">onPageLoad</label>
        <input type="radio" name="femto_fbpixel_daction" value="onclickaddtocart" disabled id="onclickaddtocart" ';
        $output.='><label for="onclickaddtocart">onClick AddToCart Button <font class=smallfont>Product Page</font></label> '.$pro_text.'
        <input type="radio" name="femto_fbpixel_daction" value="onclickaddtowish" disabled id="onclickaddtowish" ';
        $output.= '><label for="onclickaddtowish">onClick AddToWishList Button <font class=smallfont>Product Page</font></label> '.$pro_text.'
        </p>';
         $output .= "<hr>For more events for this product, You can use <b>wp-admin >> Fb Dynamic Pixel >> Manage Events</b>";
     echo $output;
     ?>
     <style>
     .smallfont{font-size:8pt; color:#BABABA;}
     </style>
     <script>
     jQuery('#pevents').on('change', function($) {
    	  if (this.value == 'AddToCart'){
			jQuery('#ppricep').show();
			jQuery('#pcurrencyp').show();
			jQuery('#pcategoryp').show();
			jQuery('#pnamep').show();
			jQuery('#pidp').show();
			jQuery('#pnump').hide();
    	  }
    	  else if (this.value == 'AddToWishlist'){
			jQuery('#ppricep').show();
			jQuery('#pcurrencyp').show();
			jQuery('#pcategoryp').show();
			jQuery('#pnamep').show();
			jQuery('#pidp').show();
			jQuery('#pnump').hide();
    	  }
    	  else if (this.value == 'InitiateCheckout'){
  			jQuery('#ppricep').show();
  			jQuery('#pcurrencyp').show();
  			jQuery('#pcategoryp').show();
  			jQuery('#pnamep').show();
  			jQuery('#pidp').show();
  			jQuery('#pnump').show();
      	  }
    	  else if (this.value == 'AddPaymentInfo'){
    			jQuery('#ppricep').show();
    			jQuery('#pcurrencyp').hide();
    			jQuery('#pcategoryp').show();
    			jQuery('#pnamep').hide();
    			jQuery('#pidp').show();
    			jQuery('#pnump').hide();
          }
          else if (this.value == 'Purchase'){
			jQuery('#ppricep').show();
			jQuery('#pcurrencyp').show();
			jQuery('#pcategoryp').show();
			jQuery('#pnamep').show();
			jQuery('#pidp').show();
			jQuery('#pnump').show();
    	  }
    	  
    	});
 	
     </script>
     

     <?php 
}
}

if(!function_exists( 'femto_fbpixel_save_meta_box' )){
/**
 * Saving meta box content!
 * @param int $post_id Post ID
 */
function femto_fbpixel_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
	if (array_key_exists('femto_fbpixel_includeProductID', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_includeProductID',
            intval( $_POST['femto_fbpixel_includeProductID'] )
        );
    }
	if (array_key_exists('femto_fbpixel_woocommerce_event', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_woocommerce_event',
             intval( $_POST['femto_fbpixel_woocommerce_event'] )
        );
    }
    
	if (array_key_exists('femto_fbpixel_includePrice', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_includePrice',
            intval( $_POST['femto_fbpixel_includePrice'] )
        );
    }
    
	if (array_key_exists('femto_fbpixel_includeCurrency', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_includeCurrency',
            intval( $_POST['femto_fbpixel_includeCurrency'] )
        );
    }
    
	if (array_key_exists('femto_fbpixel_includeProductname', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_includeProductname',
            intval( $_POST['femto_fbpixel_includeProductname'] )
        );
    }
    
	if (array_key_exists('femto_fbpixel_includeCategory', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_includeCategory',
            intval( $_POST['femto_fbpixel_includeCategory'] )
        );
    }
    
	if (array_key_exists('femto_fbpixel_includeNumitems', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_includeNumitems',
            intval( $_POST['femto_fbpixel_includeNumitems'] )
        );
    }
    
	if (array_key_exists('femto_fbpixel_daction', $_POST)) {
        update_post_meta(
            $post_id,
            'femto_fbpixel_daction',
            'onpageload'
        );
    }
    
}
	// add action to wordpress in order to make the changes
	add_action( 'save_post', 'femto_fbpixel_save_meta_box' );
}

	
/**
* 1. Check WooCommerce file if exists
* 2. If (1.) passed, then check if WooCommerce plugin is active!
*
* @return boolean
**/
if (!function_exists( 'femto_fbpixel_WooCommerceCheckExists' ))
{
	function femto_fbpixel_WooCommerceCheckExists ()
	{
		$link_to_file = ABSPATH."wp-content/plugins/woocommerce/woocommerce.php";
		
		#if (file_exists($link_to_file)){return true;} else {return false;}
		if ( 
		  in_array( 
		    'woocommerce/woocommerce.php', 
		    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
		  ) 
		) {
			return true;
		} else {
			return false;
		}
	}
}

// to delete events by eventname
// constant event is the empty or zero post_id event
if(!function_exists('femto_fbpixel_del_event_constant'))
{
	function femto_fbpixel_del_event_constant ($eventtype)
	{
		global $wpdb;
		
		$wpdb->delete($wpdb->prefix."fbpixel_events", array("eventname" => sanitize_title_for_query( $eventtype )));
	}
}

/**
 * Add Events
 * 
 * @param $femto_fbpixel_event_id (optional) , for the event unique ID
 * @param $eventtype the event type (Standard or Custom)
 * @param $the_event eventname in database, it will something like AddToCart, PageViews or ViewContent
 * @param $post_type regarding wordpress post types, it makes difference between posts, pages and products
 * @param $name the title or content_name for Facebook event
 * @param $extraData , json data to store the custom parameters.
 */
if(!function_exists('femto_fbpixel_add_new_event')){
	
	function femto_fbpixel_add_new_event($femto_fbpixel_event_id = false,$eventtype,$the_event,$post_type = false, $name = false, $extraData = false, $daction = false)
	{
		global $wpdb;
		$checkExists = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix. "fbpixel_events
		WHERE postid='".$femto_fbpixel_event_id."' and eventtype='".$eventtype."' and eventname='".$the_event."' Limit 1");
		
		if(null == $checkExists)
		{
			if(!$femto_fbpixel_event_id){$the_title = $name;}
			else{$the_title = get_the_title($femto_fbpixel_event_id);}
			
		$wpdb->insert(
			$wpdb->prefix."fbpixel_events",
			array(
			'name' => $the_title,
			'postid' => $femto_fbpixel_event_id,
			'posttype' => $post_type,
			'eventtype' => $eventtype,
			'eventname' => $the_event,
			'extradata' => $extraData,
			'daction' => $daction,
			)
		);
		}
	}
}

// Standart Facebook Pixel Code
// Get femto_fbpixel_code from options
if (!function_exists('femto_fbpixel_globalpixelCode'))
{
	function femto_fbpixel_globalpixelCode ()
	{
		?><!-- Facebook Pixel Placed By FB Dynamic Pixel Plugin -->
		<!-- Facebook Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
		n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
		document,'script','https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?php echo esc_html( get_option('femto_fbpixel_code') );?>'); // Insert your pixel ID here.
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=<?php echo esc_html( get_option('femto_fbpixel_code') );?>&ev=PageView&noscript=1"
		/></noscript>
		<!-- DO NOT MODIFY -->
		<!-- End Facebook Pixel Code -->
		<?php
	}
}

// Print Search Event
if(!function_exists('femto_fbpixel_SearchEventHtml'))
{
	function femto_fbpixel_searchEventHtml ()
	{
		?><!-- Search Event By FB Dynamic Pixel Plugin -->
			<script>
			fbq("track", "Search", {
				search_string: "<?php if(isset($_GET['s'])){echo sanitize_text_field( $_GET['s'] );}?>"
				 });
			</script>
			<!-- End Search Event By FB Dynamic Pixel Plugin -->
			<?php 
	}
}
// Print ViewContent Event for path_to_woocommerce/cart WooCommerce page
if(!function_exists('femto_fbpixel_print_standardCartEvent'))
{
    function femto_fbpixel_print_standardCartEvent ()
    {
        global $post;
        
        //Check if WooCommerce is installed and active
        if(femto_fbpixel_WooCommerceCheckExists() && get_option('femto_fbpixel_woocommerce_integrated') == 'active'){
            global $woocommerce;
            $price = $woocommerce->cart->total;
            $product_currency = get_woocommerce_currency();
            
            ?><!-- ViewContent Event By FB Dynamic Pixel Plugin -->
		<script>
		fbq('track', 'ViewContent', {
			  content_name: 'View Cart',
			  value: <?php echo $price;?>,
			  currency: '<?php echo $product_currency;?>',
			  <?php 
			  if (get_option('fb_pixel_active_http_agent') == 'checked'){
			  ?>
			  referrer: document.referrer,
			  userAgent: navigator.userAgent,
			  language: navigator.language
			  <?php 
			}
			  ?>
			 });
		</script>
		<!-- End ViewContent Event By FB Dynamic Pixel Plugin -->
		<?php 
		}
	}
}
// Print AddToCart Event with onclick
if(!function_exists('femto_fbpixel_AddToCartEventHtml'))
{
    function femto_fbpixel_AddToCartEventHtml ()
    {
        global $post;
        
        if($post->ID != '')
        {
            $cssClassName = get_option('femto_fbpixel_addtocart_css_classname');
            $cssClassName = ".".str_replace(" ",".",$cssClassName);
            
            // Page Title
            $product_name = get_the_title($post->ID);
            
            //Check if is product and is WooCommerce product
            if(is_singular( 'product' ) && femto_fbpixel_WooCommerceCheckExists())
            {
                // Get The Product Category Name
                $term_list = wp_get_post_terms($post->ID,'product_cat',array('fields'=>'ids'));
                $cat_id = (int)$term_list[0];
                $category = get_term ($cat_id, 'product_cat');
                $product_category = $category->name;
                
                // Product ID
                $product_id = $post->ID;
                
                // check if woocommerce installed or not! active or not!
                if(femto_fbpixel_WooCommerceCheckExists()){
                    $_product = wc_get_product($product_id);
                    $product_price = $_product->get_price();
                    $product_currency = get_woocommerce_currency();
                }
                
                ?><!-- AddToCart Event By FB Dynamic Pixel Plugin -->
				<script>
				jQuery("<?php echo $cssClassName;?>").click(function( $ ) {
				    fbq("track", "AddToCart", {
		    	<?php 
		  			  if (get_option('fb_pixel_active_http_agent') == 'checked'){
		  			  ?>
		  			  referrer: document.referrer,
		  			  userAgent: navigator.userAgent,
		  			  language: navigator.language,
		  			  <?php
						}
					?> 
				      content_name: "<?php echo $product_name;?>", 
				      content_category: "<?php echo $product_category;?>",
				      content_ids: ["<?php echo $product_id;?>"],
				      content_type: "product",
				      value: <?php echo $product_price;?>,
				      currency: "<?php echo $product_currency;?>" 
				    });  
				  })
				</script>
				<!-- End AddToCart Event By FB Dynamic Pixel Plugin -->
				<?php
			} 
		}
	}
}


// SESSION ORDERNUMMER
#add_filter( 'woocommerce_order_number', 'femto_fbpixel_retrieve_WC_orderID', 1, 2 );
 
// Store order_id in a session to get it in the event
if(!function_exists('femto_fbpixel_store_woocommerce_order_number')){
function femto_fbpixel_store_woocommerce_order_number( $key = false ) {
	
	$order_id = femto_fbpixel_retrieve_autocomplete_products( $key );
	$_SESSION['order_id'] = $order_id;
    
	return $order_id;
}
}

if(!function_exists('femto_fbpixel_retrieve_WC_orderID')){
/**
 * Get Order ID from post_meta of woocommerce!
 *
 * @param string $key
 * @return (order_id(
 */
function femto_fbpixel_retrieve_WC_orderID( $key = false)
{
	global $wpdb;
	
	$key = sanitize_text_field( $key );
	
	$query = "SELECT post_id FROM " .$wpdb->prefix. "postmeta WHERE 
	meta_key='_order_key' and
	meta_value='".$key."' ORDER BY meta_id DESC Limit 1";
	
	$row = $wpdb->get_row($query);
	$order_id = $row->post_id;
	
	return $order_id;
}
}

// Print events for every post,page or product from events list which stored in table fbpixel_events
// Check the post,page or product ID then print its events regarding its settings
if(!function_exists('femto_fbpixel_install_eventslist_onposts'))
{
	function femto_fbpixel_install_eventslist_onposts ()
	{
		global $wpdb, $post;
		#echo "HELLO ... ".$post->ID;
		
		$output = "";
		$events = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix."fbpixel_events ORDER BY id ASC");
		if($events)
		{
			#$extraData = "";
			foreach ($events as $list)
			{
				if($list->postid == $post->ID)
				{
					$extraData = femto_fbpixel_getExtraData ($list->id);
					
					if($list->daction == 'onclickaddtocart'){
						$cssClassName = get_option('femto_fbpixel_addtocart_css_classname');
						$cssClassName = ".".str_replace(" ",".",$cssClassName);	
					} else if($list->daction == 'onclickaddtowish'){
						$cssClassName = get_option('femto_fbpixel_addtowish_css_classname');
						$cssClassName = ".".str_replace(" ",".",$cssClassName);	
					} else {
						$action_is_onClick = '';
					}
					$action_is_onClick = '';
					$cssClassName = esc_attr( $cssClassName );
					
					$output .="\n<script>";
					
					
					$output .= "\nfbq('track', '".$list->eventname."',";
					$output .= "\n{";
					$output .= "\ncontent_name: '".get_the_title($post->ID)."',";

					if(!empty($extraData))
					{
						$output .= $extraData;
					}

				  
					$output .= "\n});";
								  
					if($action_is_onClick == 'yes')
					{
					$output .= "\n});";	
					}
					
					$output .= "\n</script>";

				}
			}
			echo $output;
		}
	}
}

if(!function_exists('femto_fbpixel_getExtraData'))
{
	function femto_fbpixel_getExtraData ($event_id)
	{
		global $wpdb, $post;
		
		$event_id = intval($event_id);
		
		$events = "SELECT extradata FROM " .$wpdb->prefix."fbpixel_events WHERE id='$event_id' and postid='".$post->ID."' Limit 1";
		$row = $wpdb->get_row($events);
		
		if($row){
		if($row->extradata != '')
		{
			$json = @json_decode($row->extradata, TRUE);
			//Check if it is array
			$extraData="";
			if(is_array($json)){
				foreach ($json as $k => $v)
				{
					$extraData .= "\n".esc_html( $k ).":'".esc_html( $v )."',";
				}
				return $extraData;
			}
		}
		}
	}
}
// Print Event Code Preview in admin backend
if(!function_exists('femto_fbpixel_eventCodePreview'))
{
	function femto_fbpixel_eventCodePreview ($id)
	{
		global $wpdb, $post;
		#echo "HELLO ... ".$post->ID;
		$extraData="";
		$id = intval($id);
		$list = $wpdb->get_row("SELECT * FROM " .$wpdb->prefix."fbpixel_events where id='$id'");
		if($list)
		{

					if($list->extradata != '')
					{
						$json = @json_decode($list->extradata, TRUE);
						//Check if it is array
						if(is_array($json)){
							foreach ($json as $k => $v)
							{
								$extraData .= "\n".esc_html( $k ).":'".esc_html( $v )."',";
							}
						}
					}
					
					if($list->daction == 'onclickaddtocart'){
						$cssClassName = get_option('femto_fbpixel_addtocart_css_classname');
						$cssClassName = ".".str_replace(" ",".",$cssClassName);	
					} else if($list->daction == 'onclickaddtowish'){
						$cssClassName = get_option('femto_fbpixel_addtowish_css_classname');
						$cssClassName = ".".str_replace(" ",".",$cssClassName);	
					} else {
						$action_is_onClick = '';
					}
					$cssClassName = esc_attr( $cssClassName );
					$action_is_onClick = '';
					
					$output = "<div class='smallfont'><pre>";
					
					$output .= "\nfbq('track', '".$list->eventname."',";
					$output .= "\n{";
					if($list->postid != '' && $list->postid!='0'){
						$output .= "\ncontent_name: '".get_the_title( esc_html( $list->postid ) )."',";
					}
					if(!empty($extraData)){
						$output .= $extraData;
					}
					
					$output .= "\n});";
					if($action_is_onClick == 'yes')
					{
						$output .= "\n});";
					}
					$output .= "</pre></div>";
					return $output;
				
			
		}
	}
}

/**
 * To clean the string before pass it.
 * 
 * @param $str the string
 */
if (!function_exists('xss_clean'))
{
	function xss_clean ($str)
	{
		// Wordpress ESCAPES
		$str = esc_html($str);
		$str = esc_attr($str);
		// END Wordpress ESCAPES
		
		$str = preg_replace('/\0+/', '', $str);
        $str = preg_replace('/(\\\\0)+/', '', $str);
        #$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$str);
        #$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$str);
        #$str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
       # $str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);        
        
        $str = preg_replace("#\t+#", " ", $str);
        $str = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
        $words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
        foreach ($words as $word) {
            $temp = '';
            for ($i = 0; $i < strlen($word); $i++) {
                $temp .= substr($word, $i, 1)."\s*";
            }
            $temp = substr($temp, 0, -3);
            $str = preg_replace('#'.$temp.'#s', $word, $str);
            $str = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $str);
        }

         $str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
         $str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
         $str = preg_replace("#<(script|xss).*?\>#si", "", $str);
         $str = preg_replace('#</*(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU',"\\1>",$str);
        $str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);
        $str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
        $bad = array(

                        'document.cookie'    => '',

                        'document.write'    => '',

                        'window.location'    => '',

                        "javascript\s*:"    => '',

                        "Redirect\s+302"    => '',

                    );
        foreach ($bad as $key => $val) {
            $str = preg_replace("#".$key."#i", $val, $str);   
        }

        $str = str_replace('<iframe', '', $str);
        $str = str_replace('</scr', '', $str);
        $str = str_replace('alert(', '', $str);
#        $str = addslashes($str);
        return $str;
	}
}
if(!function_exists('femto_fbpixel_selfURL')){
	/**
	 * to get the current URL
	 *
	 * @return string
	 */
function femto_fbpixel_selfURL() 
{ 
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
	$protocol = femto_fbpixel_strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
}
}
if(!function_exists('femto_fbpixel_strleft')){
	/**
	 * to fint and cut string
	 *
	 * @param 1st String $s1
	 * @param 2nd String $s2
	 * @return output
	 */
function femto_fbpixel_strleft($s1, $s2) 
{ 
	return substr($s1, 0, strpos($s1, $s2)); 
}
}
if(!function_exists('femto_fbpixel_getDomainUrl')){
	/**
	 * Get domain name from URL
	 *
	 * @param full link $url
	 * @return string the domain name with ltd
	 */
function femto_fbpixel_getDomainUrl($url)
{
	$domain= preg_replace(
	array(
	'~^https?\://~si' ,// strip protocol
	'~[/:#?;%&].*~',// strip port, path, query, anchor, etc
	'~\.$~',// trailing period
	),
	'',$url);
	
	if(preg_match('#^www.(.*)#i',$domain))
	{
	$domain=preg_replace('#www.#i','',$domain);
	}
	return $domain;
}
}



if(!function_exists('femto_fbpixel_cURL'))
{
	/**
	 * a cURL function
	 * 
	 * @param the link $curl
	 */
	function femto_fbpixel_cURL ($url)
	{
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_USERAGENT => 'Technoyer Verify Purchase cURL Request'
		));
		// Send the request & save response to $resp
		$resp = @curl_exec($curl);
		
		return $resp;
		// Close request to clear up some resources
		@curl_close($curl);
	}
}

if(!function_exists('femto_fbpixel_print_error'))
{
	/**
	 * Print Plugin Errors
	 *
	 * @param String $error_no
	 */
	function femto_fbpixel_print_error ($error_no)
	{
		// define error messages by numbers
		$errors = array(
		"01" => "Invalid Purcahse Code",
		"02" => "Error from Technoyer API",
		);
		
		// Retrieve Error message by error number
		foreach ($errors as $k => $v)
		{
			if($error_no == $k)
			{
				$error_message = $v;
			}
		}
		
		// HTML Message
		?><!-- Print Error -->
		<h3><?php echo $error_message;?></h3>
		Sorry! It seems like to be incorrect action taken by you.<br>
		Contact US: <?php echo FEMTO_FBPIXEL_TECHNOYER_SUPPORT_EMAIL;?>
		<hr>
		<?php 
	}
}


if(!function_exists('femto_fbpixel_WooCommerceIntegrated'))
{
/**
 * Check Integrated with WooCommerce
 *
 * @return boolean
 */
	function femto_fbpixel_WooCommerceIntegrated()
	{
		if(get_option('femto_fbpixel_woocommerce_integrated') != 'active')
		{
			return false;
		} else {return true;}
	}
}

if(!function_exists( 'femto_fbpixel_success_message' )){
/**
 * Define Messages to add it to the WP filter
 *
 * @param int $no
 * @return string
 */
function femto_fbpixel_success_message ($no)
{
	$msgs = array
	(
	"01" => "Settings Updated Successfully.",
	"02" => "Link Created Successfully.",
	"03" => "Plugin Updated Successfully. You are now on <strong>FB Dynamic Pixel</strong> v ".femto_fbpixel_version,
	);
	
	foreach ($msgs as $key => $value)
	{
		if ( $key == $no ) {$message = $value;}
	}
	
	if(!empty ( $message ) ) {
		@setcookie('success_msg','');
		return '<div id="message" class="updated notice is-dismissible"><p>'.$message.'</p></div>';
	}
}
}

if(!function_exists( 'femto_fbpixel_action_links' ))
{
	function femto_fbpixel_action_links ( $links )
	{
			$links[] = '<a href="' . esc_url( admin_url( 'admin.php?page='.FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME ) ) . '">' . __( 'Settings', FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME ) . '</a>';
			if( defined( 'FEMTO_FBPIXEL_TECHNOYER_GOPRO' ) ){
				$links[] = '<a target="_blank" href="https://codecanyon.net/item/wordpress-facebook-pixel-plugin-for-wordpress-and-woocommerce/19752894?ref=Technoyer"><span style="border:solid 1px #C73939;color:#C73939;padding:2px">' . __( 'Premium Version', FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME ) . '</a>';
			}

			return $links;
	}
}
?>