<?php

if(!defined( 'ABSPATH')){
    exit;
}

/**
 * Class WC_Shipping_Date_Admin
 *
 */
class WC_Shipping_Date_Admin
{
	public function __construct() {

		$this->includes();
	}

	/**
	 * Includes
	 */
	protected function includes()
    {
        require_once 'class-wc-shipping-date-admin-settings-message.php';
        require_once 'class-wc-shipping-date-admin-product.php';
        require_once 'class-wc-shipping-date-admin-order.php';
    }
}

new WC_Shipping_Date_Admin();
