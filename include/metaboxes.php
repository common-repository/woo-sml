<?php
/**
 * Meta boxes
 *
 * @since       1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'ag_wooslm_custom_general_fields'); 
// Save Fields
add_action('woocommerce_process_product_meta', 'ag_wooslm_custom_general_fields_save');

function ag_wooslm_custom_general_fields() {
 global $woocommerce, $post;

 $post_id = $post->ID;
 $wc_slm_licensing_enabled = get_post_meta($post_id, '_wc_slm_licensing_enabled', true) ? true : false;
 $wc_slm_sites_allowed = esc_attr(get_post_meta($post_id, '_wc_slm_sites_allowed', true));
 $_wc_slm_licensing_renewal_period = esc_attr(get_post_meta($post_id, '_wc_slm_licensing_renewal_period', true));
 $wc_slm_display = $wc_slm_licensing_enabled ? '' : ' style="display:none;"';
 /**
  * if nothing set so we assume lifetime!
  * @since 1.0.3
  */
 if (trim($_wc_slm_licensing_renewal_period) == '') {
   $_wc_slm_licensing_renewal_period = 0;
 }

 ?>

    <script type="text/javascript">jQuery( document ).ready( function($) {
            $( "#_wc_slm_licensing_enabled" ).on( "click",function() {
                // TODO: Improve toggle handling and prevent double display
                $( ".wc-slm-variable-toggled-hide" ).toggle();
                $( ".wc-slm-toggled-hide" ).toggle();
            })
        });</script>

    <p class="form-field">
        <input type="checkbox" name="_wc_slm_licensing_enabled" id="_wc_slm_licensing_enabled" value="1" <?php echo checked(true, $wc_slm_licensing_enabled, false); ?> />
        <label for="_wc_slm_licensing_enabled"><?php _e('Enable licensing for this download.', 'wc-slm');?></label>
    </p>

    <div <?php echo $wc_slm_display; ?> class="wc-slm-toggled-hide">
   <p class="form-field">
     <label for="_wc_slm_licensing_renewal_period"><?php _e('license renewal period(yearly).', 'wc-slm');?></label>
     <input type="number" name="_wc_slm_licensing_renewal_period" id="_wc_slm_licensing_renewal_period" value="<?php echo $_wc_slm_licensing_renewal_period; ?>"  />
   </p>
        <p class="form-field">
            <label for="_wc_slm_sites_allowed"><?php _e('How many sites can be activated trough a single license key?', 'wc-slm');?></label>
            <input type="number" name="_wc_slm_sites_allowed" class="small-text" value="<?php echo $wc_slm_sites_allowed; ?>" />
        </p>
    </div>
    <?php

}
function ag_wooslm_custom_general_fields_save($post_id) {
 // Textarea
 $woocommerce_wc_slm_licensing_enabled = $_POST['_wc_slm_licensing_enabled'];
 $woocommerce_wc_slm_sites_allowed = $_POST['_wc_slm_sites_allowed'];
 $_wc_slm_licensing_renewal_period = $_POST['_wc_slm_licensing_renewal_period'];
 if (!empty($woocommerce_wc_slm_licensing_enabled)) {
   update_post_meta($post_id, '_wc_slm_licensing_enabled', esc_html($woocommerce_wc_slm_licensing_enabled));
 }

 if (!empty($woocommerce_wc_slm_sites_allowed)) {
   update_post_meta($post_id, '_wc_slm_sites_allowed', esc_html($woocommerce_wc_slm_sites_allowed));
 }

 if (!empty($_wc_slm_licensing_renewal_period)) {
   update_post_meta($post_id, '_wc_slm_licensing_renewal_period', esc_html($_wc_slm_licensing_renewal_period));
 }

}
