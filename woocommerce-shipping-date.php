<?php
/**
 * Plugin Name: WooCommerce Shipping Date
 * Description: Define product shipping date in your WooCommerce store.
 * Author: Jonas
 * Author URI: https://positronic.fr
 * Version: 0.3.0
 * Text Domain: woocommerce-shipping-date
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !function_exists('is_woocommerce_active') ){
    function is_woocommerce_active(){
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }
}

// Check if WooCommerce is active and deactivate extension if it's not.
if ( ! is_woocommerce_active() ) {
	return;
}

/**
 * The WC_Shipping_Date global object
 *
 * @name $woocommerce-shipping-date
 * @global WC_Shipping_Date $GLOBALS['woocommerce-shipping-date']
 */
$GLOBALS['woocommerce-shipping-date'] = new WC_Shipping_Date();

define( 'WOOCOMMERCE_SHIPPING_DATE_VERSION', '0.2.1' );

/**
 * Main Plugin Class
 *
 * @since 0.1
 */
class WC_Shipping_Date {

	/**
	 * Setup main plugin class
	 *
	 * @since  0.1.0
	 * @return \WC_Shipping_Date
	 */
	public function __construct() {

		// load core classes
		$this->load_classes();

		// load classes that require WC to be loaded
		add_action( 'woocommerce_init', array( $this, 'init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_plugin_css' ));
	}

	/**
	 * Load core classes
	 *
	 * @since 0.1.0
	 */
	public function load_classes()
    {
        // Load framework
        require( 'includes/framework/class-admin-settings.php' );

        // Load business classes
        require( 'includes/business/class-shipping-infos.php' );
        require( 'includes/business/class-shipping-date-utils.php' );

        // Load email customizations / overrides
        require( 'includes/class-wc-shipping-date-emails.php' );
        new WC_Shipping_Date_Emails();

        // Load product customizations / overrides
        require('includes/class-wc-shipping-date-product.php');
        new WC_Shipping_Date_Product();

        // Load cart customizations / overrides
        require( 'includes/class-wc-shipping-date-cart.php' );
        new WC_Shipping_Date_Cart();
	}

	/**
	 * Load actions and filters that require WC to be loaded
	 *
	 * @since 0.1.0
	 */
	public function init() {

        load_plugin_textdomain( 'woocommerce-shipping-date', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		if ( is_admin()) {
			require( 'includes/admin/class-wc-shipping-date-admin.php' );
		}
	}

    /**
     * Load plugin css
     *
     * @since 0.2.1
     */
    public function load_plugin_css()
    {
        wp_enqueue_style( 'front', plugin_dir_url( __FILE__ ) . 'assets/css/front.css' );
    }

    /**
     * Get supported product types.
     *
     * @since 0.1.0
     * @return array
     */
    public static function get_supported_product_types()
    {
        $product_types = array(
            'simple',
            'variable',
            'composite',
            'bundle',
            'booking',
            'mix-and-match',
        );

        return apply_filters( 'woocommerce_shipping_date_supported_product_types', $product_types );
    }

}
