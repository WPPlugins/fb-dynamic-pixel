<?php 
/**
 * FB Dynamic Pixel Admin Dashboard.
 */
if (!defined('ABSPATH')){exit; // Exit if get it directly!
}
// Check for security
if(current_user_can('manage_options') == false){exit;}

// Print Admin HTML Header
include 'html-admin-header.php';

////////////////////////////////////
// Checkboxes for plugin settings//
//////////////////////////////////
global $pro_text;
// Install pixel in Homepage (checkbox)
$fb_pixel_places = "<input type=checkbox name=\"fb_pixel_places_homepage\" value=\"checked\" id=fb_pixel_places_homepage ";
if (get_option('fb_pixel_places_homepage') == 'checked'){$fb_pixel_places .= "checked";}
$fb_pixel_places .="> <label for=fb_pixel_places_homepage>".__('Homepage',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";

// Install pixel in Pages (every page) (checkbox)
$fb_pixel_places .= "<input type=checkbox name=\"fb_pixel_places_pages\" value=\"checked\" id=fb_pixel_places_pages ";
if (get_option('fb_pixel_places_pages') == 'checked'){$fb_pixel_places .= "checked";}
$fb_pixel_places .="> <label for=fb_pixel_places_pages>".__('Pages',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";

// Install pixel in Posts (every post) (checkbox)
$fb_pixel_places .= "<input type=checkbox name=\"fb_pixel_places_posts\" value=\"checked\" id=fb_pixel_places_posts ";
if (get_option('fb_pixel_places_posts') == 'checked'){$fb_pixel_places .= "checked";}
$fb_pixel_places .="> <label for=fb_pixel_places_posts>".__('Posts',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";

// Install pixel in Search Results (checkbox) -Event: Search
$fb_pixel_places .= "<input type=checkbox name=\"fb_pixel_places_search\" value=\"checked\" id=fb_pixel_places_search ";
if (get_option('fb_pixel_places_search') == 'checked'){$fb_pixel_places .= "checked";}
$fb_pixel_places .="> <label for=fb_pixel_places_search>".__('Search Results',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";

// Install pixel in After Sign Up (checkbox) -Event: CompleteRegistration //PRO VERSION
$fb_pixel_places .= "<input type=checkbox name=\"fb_pixel_places\" value=\"\" disabled id=fb_pixel_places ";
$fb_pixel_places .="> <label for=fb_pixel_places>".__('After Sign Up (Member Registration)',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label> ".$pro_text."<br>";

// Install pixel in Categories (checkbox)
$fb_pixel_places .= "<input type=checkbox name=\"fb_pixel_places_categories\" value=\"checked\" id=fb_pixel_places_categories ";
if (get_option('fb_pixel_places_categories') == 'checked'){$fb_pixel_places .= "checked";}
$fb_pixel_places .="> <label for=fb_pixel_places_categories>".__('Categories',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";

// Install pixel in Tags (checkbox)
$fb_pixel_places .= "<input type=checkbox name=\"fb_pixel_places_tags\" value=\"checked\" id=fb_pixel_places_tags ";
if (get_option('fb_pixel_places_tags') == 'checked'){$fb_pixel_places .= "checked";}
$fb_pixel_places .="> <label for=fb_pixel_places_tags>".__('Tags',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";

////////////////////////////////////
//Standard Places for WooCommerce//
//////////////////////////////////

// For View Cart Page -Event: ViewContent // PROVERSION
$fb_pixel_places_woocommerce = "<input type=checkbox name=\"fb_pixel_places_woocommerce_cart\" value=\"checked\" id=fb_pixel_places_woocommerce_cart ";
if (get_option('fb_pixel_places_woocommerce_cart') == 'checked'){$fb_pixel_places_woocommerce .= "checked";}
$fb_pixel_places_woocommerce .="> <label for=fb_pixel_places_woocommerce_cart>".__('ViewContent (for Cart Page)','fb-dynamic-pixel')."</label><br>";

// (for all products) with onClick as a Dynamic Event -Event: AddToCart
$fb_pixel_places_woocommerce .= "<input type=checkbox name=\"fb_pixel_places_woocommerce_addtocart\" value=\"checked\" id=fb_pixel_places_woocommerce_addtocart ";
if (get_option('fb_pixel_places_woocommerce_addtocart') == 'checked'){$fb_pixel_places_woocommerce .= "checked";}
$fb_pixel_places_woocommerce .="> <label for=fb_pixel_places_woocommerce_addtocart>".__('AddToCart (for all products) with onClick as a Dynamic Event',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label><br>";


// For Checkout Page for all products -Event: InitiateCheckout // PRO VERSION
$fb_pixel_places_woocommerce .= "<input type=checkbox name=\"fb_pixel_places_woo_checkout\" value=\"\" disabled id=fb_pixel_places_wooc_checkout ";
$fb_pixel_places_woocommerce .="> <label for=fb_pixel_places_woo_checkout>".__('InitiateCheckout (for Checkout Page for all products)',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label> ".$pro_text."<br>";

// [after submit] Checkout Page for all products -Event: AddPaymentInfo // PRO VERSION
$fb_pixel_places_woocommerce .= "<input type=checkbox name=\"fb_pixel_places_woo_paymentinfo\" value=\"checked\" disabled id=fb_pixel_places_woo_paymentinfo ";
$fb_pixel_places_woocommerce .="> <label for=fb_pixel_places_woo_paymentinfo>".__('AddPaymentInfo ([after submit] Checkout Page for all products)',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)."</label> ".$pro_text."<br>";

// [after submit] Checkout Page for all products -Event: Purchase // PRO VERSION
$fb_pixel_places_woocommerce .= "<input type=checkbox name=\"fb_pixel_places_woo_purchase\" value=\"checked\" disabled id=fb_pixel_places_woo_purchase ";
$fb_pixel_places_woocommerce .="> <label for=fb_pixel_places_woo_purchase>".__('Purchase ([after submit] Checkout Page for all products)',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)." <span class='smallfont'>v 1.1.0</span></label> ".$pro_text."<br>";

// HTTP HEADER INFROMATION CHECKBOX // PRO VERSION
$fb_pixel_active_http_agent_option = "<input type=checkbox name=\"fb_pixel_active_http_\" value=\"checked\" disabled id=fb_pixel_active_http_ ";
$fb_pixel_active_http_agent_option .="> <label for=fb_pixel_active_http_>".__('Send (HTTP HEADERS) Information with event details to Facebook',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME)." </label> ".$pro_text."<br>";


//////////////////////////////////////////
// Passing The Ajax via admin-ajax.php //
/////////////////////////////////////////

// To save general settings
$generalSettingsSubmitURL = add_query_arg (
array (
	'action' => 'femto_fbpixel_saveGeneralSettings',
	'_wpnonce' => wp_create_nonce ( 'femto_fbpixel_saveGeneralSettings' )
)
, admin_url( 'admin-ajax.php' ));

// To get from POSTS using autocomplete
$femto_fbpixel_autocomplete_postsURL = add_query_arg(array(
	'action' => 'femto_fbpixel_retrieve_autocomplete_posts',
	'_wpnonce' => wp_create_nonce ('femto_fbpixel_retrieve_autocomplete_posts')
), admin_url('admin-ajax.php'));

// To get from PAGES using autocomplete
$femto_fbpixel_autocomplete_pagesURL = add_query_arg(array(
	'action' => 'femto_fbpixel_retrieve_autocomplete_pages',
	'_wpnonce' => wp_create_nonce ('femto_fbpixel_retrieve_autocomplete_products')
), admin_url('admin-ajax.php'));

// To get from PRODUCTS using autocomplete
$femto_fbpixel_autocomplete_productsURL = add_query_arg(array(
	'action' => 'femto_fbpixel_retrieve_autocomplete_products',
	'_wpnonce' => wp_create_nonce ('femto_fbpixel_retrieve_autocomplete_products')
), admin_url('admin-ajax.php'));

// To save session for post_type when autocomplete is running
$femto_fbpixel_register_onchangesessionURL = add_query_arg(array(
	'action' => 'femto_fbpixel_register_onchangesession',
	'_wpnonce' => wp_create_nonce('femto_fbpixel_register_onchangesession')
), admin_url('admin-ajax.php'));

// To load events list under `manage events` tab
$loadEventsList = add_query_arg(array(
	'action' => 'femto_fbpixel_loadEventsList',
	'_wpnonce' => wp_create_nonce('femto_fbpixel_loadEventsList')
),admin_url('admin-ajax.php'));

// To install new event using $.POST
$installNewEvent = add_query_arg(array(
	'action' => 'femto_fbpixel_installNewEvent',
	'_wpnonce' => wp_create_nonce ('femto_fbpixel_installNewEvent')
), admin_url('admin-ajax.php'));

//////////////////////////////////
// Main Dashboard HTML by here //
////////////////////////////////

// Print Errors if found!
if (!empty($_COOKIE['site_error']))
{
	?><!-- Print Error -->
	<div class="error">
	<?php echo $_COOKIE['site_error'];?>
	</div>
	<?php
}
// Print Success Message
if(!empty($_COOKIE['success_msg']))
{
	echo femto_fbpixel_success_message($_COOKIE['success_msg']);
}
?>

<p>
<h2>Facebook Pixel & Events</h2>
</p>
<hr>
<div id="tab-container" class='tab-container'>

 <ul class='etabs'>
   <li class='tab' active><a href="#installPixel"><?php echo __('Pixel Settings',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a></li>
   <li class='tab'><a href="#installEvents"><?php echo __('Manage Events',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a></li>
   <li class='tab'><a href="#WooCommerce"><?php echo __('WooCommerce Integration',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a></li>
   <li class='tab'><a href="#Documentation"><?php echo __('Documentation',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a></li>
   <li class='tab'><a href="#Followus"><?php echo __('Follow Us',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a></li>
   <li class='tab' style="background:#E74848;color:#fff;"><a href="#GoPro">&hearts; <?php echo __('Go Pro Today',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a></li>
 </ul>
 <div class='panel-container'>
 <!-- Install Pixel tab -->
 	<div id="installPixel">
 	
 	<h3><?php echo __('Facebook Pixel Settings',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></h3>
 	
 	<form action="<?php echo $generalSettingsSubmitURL; ?>" method="post" id=fbpixelForm>
 	<table border=0 width=100% cellspacing=2>
 	<tr>
 		<td><hr><strong><?php echo __('Facebook Pixel ID',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></strong><br>
 		<input type=text name=femto_fbpixel_code value="<?php echo get_option('femto_fbpixel_code');?>">
 		<br>
 		<?php echo $fb_pixel_active_http_agent_option;?>
 		<span class="smallfont"><?php echo __('like e.g: User Language, Browser Information or Domain Referrer.',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></span>
 		</td>
 	</tr>
 	<tr>
 		<td><hr><strong><?php echo __('Where do you want to place FB pixel code?',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></strong><br>
 		<?php 
 		echo $fb_pixel_places;
 		?>
 		</td>
 	</tr>
 	<?php 
 	// Check if FB Dynamic Pixel plugins was integrated with WooCommerce or not!
 	// using get_option, if the value was active, this mean it is already integrated.
 	if(get_option('femto_fbpixel_woocommerce_integrated') == 'active')
 	{
 		?>
 		<tr>
 		<td><hr><strong><?php echo __('WooCommerce Settings',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></strong><br>
 		<?php echo __('CSS class name for (add to cart button)',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>:<br>
 		<input type="text" name="femto_fbpixel_addtocart_css_classname" value="<?php echo get_option('femto_fbpixel_addtocart_css_classname');?>"><br>
 		<br>
 		<?php echo __('CSS class name for (add to wishlist button)',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>:  <?php echo $pro_text;?><br>
 		<input type="text" name="femto_fbpixel_addtowish_css_classname" disabled><br>
 		<br><b><?php echo __('Standard Events for WooCommerce',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></b>:<br>
 		<?php echo $fb_pixel_places_woocommerce?>
 		</td></tr>
 		<?php 
 	}
 	?>
 	<tr>
 		<td><hr><strong><?php echo __('Complete Registration Event Settings',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></strong> <?php echo $pro_text; ?><br>
 		<?php echo __('Main phrase that will be worked on',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>:<br>
 		<input type="text" disabled name="femto_fbpixel_register_confrimation_msg_to_replace" value="<?php echo get_option('femto_fbpixel_register_confrimation_msg_to_replace');?>"><br>
 		<span class="smallfont"><?php echo __('If you do not know how it works, please do not change it.',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></span>
 		</td>
 	<tr>
 		<td>
 		<hr>
 		<button type=submit class=button id=saveBtn><img src="<?php echo plugins_url("images/save_16.png", FEMTO_FBPIXEL_PATH)?>"> <?php echo __('Save',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></button>
 		<button type=button class=button id=watchBtn><img src="<?php echo plugins_url("images/administrative-docs.png", FEMTO_FBPIXEL_PATH);?>"> <?php echo __('Read Guide',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></button>
 		</td>
 	</tr>
 	</table>
 	</form>
 	</div>
 	<!-- End // Install Pixel tab -->
 	
 	<!-- Install Events tab -->
 	<div id="installEvents">
 	<h3><?php echo __('Install New Event',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></h3>
 	<form id='installEvent' method=post action="<?php echo $installNewEvent;?>">
 	<select name="post_type" id="postType">
 	<option value="post"><?php echo __('Blog Post',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></option>
 	<option value="page"><?php echo __('Website Pages',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></option>
 	<?php 
 	// Check if WooCommerce Integration is active, Show the "WooCommerce Producst Option"
 	if(get_option('femto_fbpixel_woocommerce_integrated') == 'active')
 	{
 	?>
 	<option value="product"><?php echo __('WooCommerce Products',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></option>
 	<?php 
 	}
 	?>
 	</select>
 	: 
 	<select name="fbpixel_standard_event" id="fbpixel_standard_event">
 	<?php 
 	// Retrieve Events List in the DropDown menu
 	$fbpixel_standard_eventArray = femto_fbpixel_EventsDropDownList();
 	foreach ($fbpixel_standard_eventArray as $fbKey)
 	{
 		?>
 		<option value="<?php echo $fbKey;?>"><?php echo $fbKey;?></option>
 		<?php 
 	}
 	?>
 	</select>
 	<?php echo __('or Custom Event',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>: <input type=text name="customevent" id="customevent" placeholder="<?php echo __('Type The Event Name',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>">
 	<hr>
 	<?php echo __('Select Post/Page/Product',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>:
 	<div>
        <input type="text" id="femto_fbpixel_autocomplete_search_posts" name="femto_fbpixel_event_id" />
          
        <p>
        <?php echo __('Value',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>: <font class="smallfont">(<?php echo __('Optional',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>)</font> <br /><input type="text" name="eventvalue" style="width:80px;"><br />
        <?php echo __('Currency',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>: <font class="smallfont">(<?php echo __('Optional',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>)</font> <br /><input type="text" name="eventcurrency" style="width:80px;"><br />
        <?php echo __('Num Items',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>: <font class="smallfont">(<?php echo __('Optional',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>)</font> <br /><input type="text" name="eventnumitems" style="width:80px;"><br />
        <p>
        <b><?php echo __('Create Custom Parameters',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?> <font class="smallfont">(<?php echo __('Optional',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>)</font></b>
        <table><tbody id="tableToModify">
        <tr id="rowToClone"><td><?php echo __('Key',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>: <input type="text" name="customkey[]#technoyer" style="width:80px;">
        <?php echo __('Value',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>: <input type="text" name="customvalue[]#technoyer" style="width:80px;">
        <img src="<?php echo plugins_url()."/fb-dynamic-pixel/images/plus_alt-16.png"?>" style="cursor:pointer" onclick="cloneRow()" alt="<?php echo __('Add new parameter',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>" title="<?php echo __('Add new parameter',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>">
        <img src="<?php echo plugins_url()."/fb-dynamic-pixel/images/minus_alt-16.png"?>" style="cursor:pointer" onclick="removeRow()" alt="<?php echo __('Remove parameter',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>" title="<?php echo __('Remove parameter',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>">
        </td></tr></tbody></table>
        <br>
        <b><?php echo __('Dynamic Action',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></b><p>
        <input type="radio" name="daction" value="onpageload" id="onpageload" checked><label for="onpageload"><?php echo __('onPageLoad',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></label>
        <input type="radio" name="daction" value="onclickaddtocart" disabled id="onclickaddtocart"><label for="onclickaddtocart"><?php echo __('onClick AddToCart Button',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?> <font class=smallfont><?php echo __('Product Page',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></font> <?php echo $pro_text;?></label>
        <input type="radio" name="daction" value="onclickaddtowish" disabled id="onclickaddtowish"><label for="onclickaddtowish"><?php echo __('onClick AddToWishList Button',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?> <font class=smallfont><?php echo __('Product Page',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></font> <?php echo $pro_text;?></label>
        </p>
        
        
        <p><button class="button" type="submit"><?php echo __('Install Event',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></button>
        </p></p>
        </form>
        
        <!-- Register Post Type in Session -->
        <script>
        jQuery('#postType').on('change', function($) 
				{
			var load_from_session_url = "<?php echo $femto_fbpixel_register_onchangesessionURL;?>";
			jQuery("#res").load(load_from_session_url+'&t='+this.value);
				}
				);
        </script>
        <!-- End // Register Post Type in Session -->
        
        <!-- Autocomplete Search Results -->
        <script type="text/javascript">
      //Blog Posts
        jQuery(document).ready(function() {
            var linkPostsUrl = "<?php echo $femto_fbpixel_autocomplete_productsURL;?>";
            	jQuery("#femto_fbpixel_autocomplete_search_posts").tokenInput(linkPostsUrl+'&t=post',{
        			tokenLimit: 1
        			}
                    );
        });
        </script>
        <script type="text/javascript">
      //Pages
        jQuery(document).ready(function() {
            var linkPagesUrl = "<?php echo $femto_fbpixel_autocomplete_productsURL;?>";
            	jQuery("#femto_fbpixel_autocomplete_search_pages").tokenInput(linkPagesUrl+'&t=page',{
        			tokenLimit: 1
        			}
                    );
        });
        </script>
        <script type="text/javascript">
      //Products
        jQuery(document).ready(function() {
            var linkProductUrl = "<?php echo $femto_fbpixel_autocomplete_productsURL;?>";
            	jQuery("#femto_fbpixel_autocomplete_search_products").tokenInput(linkProductUrl+'&t=product',{
        			tokenLimit: 1
        			}
                    );
        });
        </script>
        <!-- End // Autocomplete Search Results -->
    </div>
    	<!-- Load Events List in Div -->
	 	<div id="eventslist"></div>
	 	<script>
		jQuery("#eventslist").load("<?php echo $loadEventsList;?>"); // Load Results in Div
	 	</script>
	 	<!-- End // Load Events List in Div -->
 	</div>
 	<!-- End // Install Events tab -->
 	
 	<!-- WooComerce tab -->
 	<div id="WooCommerce">
 	<h3><?php echo __('WooCommerce Integration',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></h3>
 	<br>
 	<?php
 	// Check if FB Dynamic Pixel plugins was integrated with WooCommerece or not!
 	// using get_option, if the value was active, this mean it is already integrated.
 	if (get_option('femto_fbpixel_woocommerce_integrated') == 'active')
 	{
 		 $IntDivStyle_toDisplay_Stop = "display:;";
 		 $IntDivStyle_toDisplay_Start = "display:none;"; 
 	} else {
 		$IntDivStyle_toDisplay_Stop = "display:none;";
 		$IntDivStyle_toDisplay_Start = "display:;"; 
 	}
 	?>
 		<!-- Stop Integration Button -->
 		<div id="StpInt" style="<?php echo $IntDivStyle_toDisplay_Stop;?>">
 		<strong><?php echo __('WooCommerce Already Integrated!',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></strong><br>
 		<?php echo __('Do you want to disconnect this link? If yes, Please click the button below ...',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?><br><br>
 		<button id="wStpInt" class=button><?php echo __('Stop Integration',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></button>
 		</div>
 		<!-- End // Stop Integration Button -->
 		
 		<!-- Start Integration Button -->
 		<div id="StartInt" style="<?php echo $IntDivStyle_toDisplay_Start;?>">
	 	<button id="wInt" class=button><?php echo __('Start Integration',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></button>
	 	<br><br>
	 	<?php echo __('When you integrate',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?> <strong>WooCommerce</strong> <?php echo __('with',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?> <strong>FB Dynamic Pixel</strong> <?php echo __('plugin, You will see the results under the add/edit woocommerce product box.',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>
	 	</div>
	 	<!-- End // Start Integration Button -->
 	<hr>
 	</div>
 	<!-- End // WooComerce tab -->
 	
 	<!-- Documentation tab -->
 	<div id="Documentation"><h3><?php echo __('Documentation',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></h3>
 	<?php echo __('We are at the same side, So we created awesome documentation PDF file to inform you what will you do with plugin in order to make your Facebook campaigns more effective.',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?>
 	<br><br>
 	<a href="<?php echo FEMTO_FBPIXEL_DOCS_URL;?>" target=_blank><?php echo __('Read From Here',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a>
 	</div>
 	<!-- End // Documentation tab -->
 	
 	<!-- Follow Us tab -->
 	<div id="Followus"><h3><?php echo __('Follow Us',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></h3>
 	<b>Facebook</b>: <a href="https://www.facebook.com/Technoyer/" target=_blank><?php echo __('Click Here',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a><hr>
 	<b>Codecanyon</b>: <a href="https://codecanyon.net/user/technoyer" target=_blank><?php echo __('Click Here',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></a>
 	</div>
 	<!-- End // Follow Us tab -->
 	
 	<!-- Go Pro tab -->
 	<div id="GoPro"><h3><?php echo __('Premium Version',FEMTO_FBPIXEL_TECHNOYER_PRODUCTNAME);?></h3>
 	<center><a href="https://codecanyon.net/item/wordpress-facebook-pixel-plugin-for-wordpress-and-woocommerce/19752894?ref=Technoyer" target=_blank><img src="<?php echo plugins_url('fb-dynamic-pixel/images/fbdynamicpixelprice.png')?>"></a></center>
 	</div>
 	<!-- End // Go Pro tab -->
 	
 	<div id=loading><br><img src="<?php echo plugins_url()."/fb-dynamic-pixel/images/loading.gif";?>"></div>
 	<div id="integrationResult"></div>
 </div>
 
 <!-- Div to load jQuery results from loading any scripts -->
 <div id="res"></div>
<?php 
// Pass the integeration (start/stop) action via admin-ajax.php
// Create the _wpnonce by here
$integrationURL = add_query_arg ( array (
	'action' => 'femto_fbpixel_woocommerceIntergation',
	'_wpnonce' => wp_create_nonce( 'femto_fbpixel_woocommerceIntergation' )
), admin_url('admin-ajax.php'));

$DisintegrationURL = add_query_arg ( array (
	'action' => 'femto_fbpixel_woocommerceStopIntegration',
	'_wpnonce' => wp_create_nonce ( 'femto_fbpixel_woocommerceStopIntegration' )
), admin_url ( 'admin-ajax.php' ));
?>
<script>
// Create Intergration URL 
jQuery( "#wInt" ).click (function ( $ )
	{
	jQuery("#loading").show(200); //Show loading div
	jQuery("#res").load("<?php echo $integrationURL;?>"); //Load results
	}
	);
// Create Dis-Integrate URL
jQuery( "#wStpInt" ).click (function ( $ )
	{
	jQuery("#loading").show(200); //Show loading div
	jQuery("#res").load("<?php echo $DisintegrationURL;?>"); //Load results
	}
	);
// Create DOCS URL
jQuery("#watchBtn").click(function( $ )
		{
	window.open("<?php echo FEMTO_FBPIXEL_DOCS_URL;?>", "_blank")
		}
		);
 </script>
 
