<?php

if(!defined( 'ABSPATH')){
	exit;
}

/**
 * Shipping Date Admin Products class.
 */
class WC_Shipping_Date_Admin_Products {

	/**
	 * Initialize the admin products actions.
	 */
	public function __construct() {

		// Add shipping date product writepanel tab
		add_action( 'woocommerce_product_data_tabs', array( __CLASS__, 'add_product_tab' ), 1 );

		// Add shipping date tab content
		add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_tab_options' ), 11 );

		// Save shipping date product options
		$product_types = WC_Shipping_Date::get_supported_product_types();
		foreach ( $product_types as $product_type ) {
			add_action( 'woocommerce_process_product_meta_' . $product_type, array( $this, 'save_product_tab_options' ) );
		}
	}

	/**
	 * Add shipping date tab to product panel.
	 *
	 * @param  array $tabs
	 * @return array
	 */
	public static function add_product_tab( $tabs ) {

		$tabs['shipping_date'] = array(
			'label'  => __( 'Shipping date', 'woocommerce-shipping-date' ),
			'target' => 'wc_shipping_date_data',
		);

		return $tabs;
	}

	/**
	 * Add shipping date options to product
	 */
	public function add_product_tab_options() {
		include 'views/html-product-tab-options.php';
	}

	/**
	 * Save shipping date options.
	 *
	 * @param int $post_id The ID of the product being saved.
	 */
	public function save_product_tab_options( $post_id ) {

		// Shipping date enabled
        $enabled = isset($_POST[Shipping_Date_Core::PRODUCT_ENABLED_META_KEY]) && 'yes' === $_POST[Shipping_Date_Core::PRODUCT_ENABLED_META_KEY];
		if ( $enabled ) {
			update_post_meta( $post_id, Shipping_Date_Core::PRODUCT_ENABLED_META_KEY, 'yes' );
		} else {
			update_post_meta( $post_id, Shipping_Date_Core::PRODUCT_ENABLED_META_KEY, 'no' );
		}

        // Shipping date type
        if ( isset( $_POST[Shipping_Date_Core::PRODUCT_SHIPPING_DATE_TYPE] ) ) {
            $type = $_POST[Shipping_Date_Core::PRODUCT_SHIPPING_DATE_TYPE];
            update_post_meta( $post_id, Shipping_Date_Core::PRODUCT_SHIPPING_DATE_TYPE, $type );
        }

		// Save the UTC shipping date/time.
		if ( $type == Shipping_Date_Core::TYPE_DATE ) {
		    if( empty( $_POST[Shipping_Date_Core::PRODUCT_DATETIME_META_KEY] ))
		        $this->fatal_error('Missing date');

			try {

				// Get datetime object from site timezone.
				$datetime = new DateTime( $_POST[Shipping_Date_Core::PRODUCT_DATETIME_META_KEY], new DateTimeZone( Time_Utils::get_wp_timezone_string() ) );

				// Get the unix timestamp (adjusted for the site's timezone already).
				$timestamp = $datetime->format( 'U' );

				// Don't allow shipping date dates in the past.
				if ( $timestamp <= time() ) {
					$timestamp = '';
				}

				// Save the shipping datetime.
				update_post_meta( $post_id, Shipping_Date_Core::PRODUCT_DATETIME_META_KEY, $timestamp );
                delete_post_meta( $post_id, Shipping_Date_Core::PRODUCT_DELAY);

			} catch ( Exception $e ) {
				global $woocommerce_shipping_date;

				$woocommerce_shipping_date->log( $e->getMessage() ); //TODO
			}

		} else if ( $type == Shipping_Date_Core::TYPE_DELAY ) {
            $delay = $_POST[Shipping_Date_Core::PRODUCT_DELAY];
            if( empty( $delay ))
                $this->fatal_error('Missing delay');

            update_post_meta( $post_id, Shipping_Date_Core::PRODUCT_DELAY, $delay );
			delete_post_meta( $post_id, Shipping_Date_Core::PRODUCT_DATETIME_META_KEY);
		}

		do_action( Shipping_Date_Core::PRODUCT_DATETIME_META_KEY, $post_id ); //TODO: usefull ? good hook name ?
	}

	public function fatal_error( string $message ):void
    {
        wp_die(
            $message,
            'Une erreur est survenue',
            array(
                'response' => 500,
                'back_link' => true
            )
        );
    }
}

new WC_Shipping_Date_Admin_Products();
