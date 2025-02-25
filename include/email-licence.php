<?php
/**
 * Email Licence to buyer
 *
 * @since       1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
add_action('woocommerce_email_before_order_table', 'ag_wooslm_email_content', 10, 2);

function ag_wooslm_email_content($order, $is_admin_email) {
 if ($order->post->post_status == 'wc-completed') {
   $output = '';

   // Check if licenses were generated
   $licenses = get_post_meta($order->post->ID, '_wc_slm_payment_licenses', true);

   if ($licenses && count($licenses) != 0) {
     $output = '<h3>' . __('Your Licenses', 'wc-slm') . ':</h3><table><tr><th class="td">' . __('Item', 'wc-slm') . '</th><th class="td">' . __('License', 'wc-slm') . '</th><th class="td">' . __('Expire Date', 'wc-slm') . '</th></tr>';
     foreach ($licenses as $license) {
       $output .= '<tr>';
       if (isset($license['item']) && isset($license['key'])) {

         if ($output) {
           $output .= '<br />';
         }
         $output .= '<td class="td">' . $license['item'] . '</td>';
         $output .= '<td class="td">' . $license['key'] . '</td>';
       } else {
         $output .= 'No item and key assigned';
       }

       if (isset($license['expires'])) {
                    $output .= '<td class="td">' . $license['expires'] . '</td>';
       }
       $output .= '</tr>';
     }
     $output .= '</table>';
   } else {
     $output .= 'No License Generatred';
   }

   echo $output;
 }
}
 
