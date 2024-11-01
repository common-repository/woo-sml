<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
/**
 * When purchase complete
 *
 * @since 1.0.0
 * @return void
 */

function ag_wooslm_when_complete_purchase_create_licence($order_id) {

  $order = new WC_Order($order_id);

    // Collect license keys
   $licenses = array();

   $items = $order->get_items();

   foreach ( $items as $item ) {
   $product_name = $item['name'];
   $product_id = $item['product_id'];




           $expiry = get_post_meta($product_id, '_wc_slm_licensing_renewal_period', true);
           $sites_allowed = get_post_meta($product_id, '_wc_slm_sites_allowed', true);
           $licenced = get_post_meta($product_id, '_wc_slm_licensing_enabled', true);

           if($licenced){


            $api_params = array(
            'slm_action' => 'slm_create_new',
            'secret_key' => AG_WooSLM_API_SECRET,
            'first_name' => $order->get_billing_first_name(),
            'last_name' => $order->get_billing_last_name(),
            'email' => $order->get_billing_email(),
            'company_name' => $order->get_billing_company(),
            'txn_id' => $product_id,
            'max_allowed_domains' => $sites_allowed,
            'date_created' =>date('Y-m-d'),
            'date_expiry' => date('Y-m-d', strtotime('+' . $expiry . ' years')),
            );

            // Send query to the license manager server
            $url = 'http://' . AG_WooSLM_API_URL . '?' . http_build_query($api_params);
            $url = str_replace(array('http://', 'https://'), '', $url);
            $url = 'http://' . $url;

            $response = wp_remote_get($url, array('timeout' => 20, 'sslverify' => false));

            // Check for error in the response
            if (is_wp_error($response)){
            echo "Unexpected Error! The query returned with an error.";
            }

            // License data.
            $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', utf8_encode(wp_remote_retrieve_body($response)));
            $license_data = json_decode($json);



            if ($license_data->key) {
               $licenses[] = array(
                 'item' => $product_name,
                 'key' => $license_data->key,
                 'expires' => date('Y-m-d', strtotime('+' . $expiry . ' years')),
               );

               $order->add_order_note('License Key generated: ' .$license_data->key);

               update_post_meta($order_id, '_wc_slm_payment_licenses', $licenses);
 
               ag_wooslm_assign_licenses_to_order($order_id, $licenses);
             }

           }



         }



}
add_action('woocommerce_order_status_completed', 'ag_wooslm_when_complete_purchase_create_licence', 10, 1);


function ag_wooslm_assign_licenses_to_order($order_id, $licenses) {

 if (count($licenses) != 0) {
   update_post_meta($order_id, '_wc_slm_payment_licenses', $licenses);
 }
}
