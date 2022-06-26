<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Shipping date Cart class
 *
 * Customizes the cart
 *
 * @since 0.1
 */
class WC_Shipping_Date_Cart {


	/**
	 * Add hooks / filters
	 *
	 * @since 0.1
	 * @return \WC_Shipping_Date_Cart
	 */
	public function __construct()
    {
		add_filter('woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );

        add_action('woocommerce_after_shipping_calculator', array( $this, 'show_shipping_date_in_cart_total' ), 10 );
        add_action('woocommerce_checkout_create_order', array( $this, 'before_checkout_create_order'), 20, 2);
	}

    /**
     * Save shipping date in order if necessary
     *
     * @since 0.1
     * @param $order WC_Order
     * @param $data
     */
    function before_checkout_create_order( WC_Order $order, $data )
    {
        if(self::cart_contains_shipping_date())
        {
            // Store shipping date in order metadata
            Shipping_Date_Core::add_order_shipping_date_timestamp($order, self::get_max_shipping_date_in_cart());

            // Store shipping date in order items metadata
            $items = $order->get_items();
            foreach( $items as $item_id => $item ) {
                $product = $item->get_product();
                $timestamp = WC_Shipping_Date_Product::get_product_shipping_timestamp($product);
                $type = WC_Shipping_Date_Product::get_product_shipping_type($product);
                $item->add_meta_data('_wsd_shipping_date', $timestamp);
                $item->add_meta_data('_wsd_shipping_date_type', $type);
                if($type == Shipping_Date_Core::TYPE_DELAY)
                    $item->add_meta_data('_wsd_shipping_delay', $type);
                $item->save();
            }
        }
    }

    /**
     * Display shipping date in cart
     *
     * @since 0.1
     * @param void
     * @return void
     */
    public function show_shipping_date_in_cart_total()
    {
        // no shipping date in cart
        if(!$this->cart_contains_shipping_date())
            return;

        $max_shipping_date_timestamp = $this->get_max_shipping_date_in_cart();

        // outdated
        if($max_shipping_date_timestamp <= time() )
            return;

        $date = Time_Utils::format_date($max_shipping_date_timestamp); //1572822000

        echo '<br><strong><p style="color: #0f834d">'.__('Shipping from', 'woocommerce-shipping-date').' '.$date.'</p></strong>';
    }

	/**
	 * Get item data to display on cart/checkout pages that shows the availability date
	 *
	 * @since 0.1
	 * @param array $item_data any existing item data
	 * @param array $cart_item the cart item
	 * @return array
	 */
	public function get_item_data( $item_data, $cart_item ) {

        // only modify shipping date on cart/checkout page
        if ( ! $this->cart_contains_shipping_date() )
            return $item_data;

        // get title text
        $name = __('Availability', 'woocommerce-shipping-date'); //get_option( 'woocommerce_shipping_date_availability_date_cart_title_text' );

        // don't add if empty
        if (empty($name))
            return $item_data;

        $product = $cart_item['data'];
        if ( ! is_object( $product ) ) {
            $product = wc_get_product($product);
        }

        $timestamp = WC_Shipping_Date_Product::get_product_shipping_timestamp($product);
        if($timestamp !== 0 && $timestamp >= time())
        {
            $order_meta = apply_filters( 'woocommerce_shipping_date_cart_item_meta', array(
                'name'    => $name,
                'display' => Time_Utils::format_date($timestamp),
            ), $cart_item );

            // add title and localized date
            if ( ! empty( $order_meta ) )
                $item_data[] = $order_meta;
        }

        return $item_data;
	}


    /**
     * Returns max shipping date in cart
     *
     * @since 0.1
     * @return int
     */
    public static function get_max_shipping_date_in_cart() {
        global $woocommerce;

        $max_time_stamp = 0;

        if (!empty( $woocommerce->cart->cart_contents)) {

            foreach($woocommerce->cart->cart_contents as $cart_item){

                $product = $cart_item['product_id'];
                if( ! is_object( $product ) )
                    $product = wc_get_product($product);
                $product_has_shipping_date = WC_Shipping_Date_Product::product_has_shipping_date($product);

                if($product_has_shipping_date){
                    $shipping_timestamp = WC_Shipping_Date_Product::get_product_shipping_timestamp($product);
                    if($shipping_timestamp > $max_time_stamp)
                        $max_time_stamp = $shipping_timestamp;
                }
            }
        }

        return $max_time_stamp;
    }


	/**
	 * Checks if the current cart contains a product with shipping date enabled
	 *
	 * @since 0.1
	 * @return bool true if the cart contains a shipping date, false otherwise
	 */
	public static function cart_contains_shipping_date() {
		global $woocommerce;

		$contains_shipping_date = false;

		if ( ! empty( $woocommerce->cart->cart_contents ) ) {

			foreach ( $woocommerce->cart->cart_contents as $cart_item ) {

                $product = $cart_item['product_id'];
                if( ! is_object( $product ) )
                    $product = wc_get_product($product);

				if ( WC_Shipping_Date_Product::product_has_shipping_date( $product ) ) {

					$contains_shipping_date = true;
					break;
				}
			}
		}

		return $contains_shipping_date;
	}
}
