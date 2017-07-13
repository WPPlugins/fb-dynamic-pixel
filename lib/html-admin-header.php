<?php
/**
 * FB Dynamic Pixel Admin Dashboard Header HTML.
 */
if (!defined('ABSPATH')){exit; // Exit if get it directly!
}


?>
<!-- Print Message -->
<div id="message" class="updated notice is-dismissible" style="display:none;"><p id="msgP"></p></div>

<!-- Print Error -->
<div id="error"><span></span></div>

<!-- Print Loading Div -->
<div class="darkenBG"><div class="loader">
<center><img src="images/loading.gif"><br> <center>Loading ... Please Wait</center></div></div>
<script type="text/javascript">
    jQuery(document).ready( function($) {
    	jQuery('#tab-container').easytabs();
    });
  
</script>
<!-- 
<script>
jQuery(document).ready(function ($)
		{
		$('.darkenBG').show();
		}
		);
</script>

-->
<div class="error" style="width:94%">Thank you for using our product, It is recommended to use the pro version.<br>
Do not miss the 25% OFF. <a href="https://codecanyon.net/item/wordpress-facebook-pixel-plugin-for-wordpress-and-woocommerce/19752894?ref=Technoyer" target=_blank>Purchase Premuim Now</a></div>