<?php

if(!defined( 'ABSPATH')){
	exit;
}

/**
 * Shipping Date Admin Products class.
 */
class WC_Shipping_Date_Admin_Order {

	/**
	 * Initialize the admin order actions.
     * @since 0.4
	 */
	public function __construct() {
        add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'action_woocommerce_admin_order_data_after_order_details' ), 10, 1 );
	}

    /**
     * Add "Show order confirmation page" control.
     *
     * @param $order
     * @since 0.4
     */
    public function action_woocommerce_admin_order_data_after_order_details( $order ) {
        echo '<a href="' . $order->get_checkout_order_received_url() . '" target="_blank" class="button" style="margin-top:20px; margin-bottom: 15px">' . __( 'Show order confirmation page', 'woocommerce-shipping-date' ) . '</a>';
    }


}

new WC_Shipping_Date_Admin_Order();
