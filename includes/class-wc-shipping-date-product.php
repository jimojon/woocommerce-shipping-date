<?php

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}

/**
 * Shipping Date Product class
 *
 * Add shipping date functionality to products
 *
 * @since 0.1
 */
class WC_Shipping_Date_Product {

	/**
	 * Adds needed hooks / filters
	 *
	 * @since 0.1
	 */
	public function __construct() {

        add_filter( 'woocommerce_get_stock_html', array( $this, 'woocommerce_get_stock_html'));

	}

    /**
     * @param $html
     * @return string
     */
    public function woocommerce_get_stock_html( $html )
    {
        global $product;

        $timestamp = $this->get_product_shipping_timestamp( $product );

        if( $timestamp == 0 )
            $newHTML = $html;

        else if( $timestamp < time() )
            $newHTML = $html;
        else
            $newHTML = '<div class="woocommerce-variation-availability"><p class="stock in-stock">Expédition à partir du '.Time_Utils::format_date($timestamp).'</p></div>';

        if( isset ( $info ) )
            $newHTML .= $info;

        return $newHTML;
    }

	/**
	 * Checks if a given product has a shipping date enabled
	 *
	 * @since 0.1
     * @param WC_Product the product object
	 * @return bool true if product has an enabled shipping date, false otherwise
	 */
	public static function product_has_shipping_date( WC_product $product ) {
		return 'yes' === get_post_meta( $product->get_id(), Shipping_Date_Core::PRODUCT_ENABLED_META_KEY, true );
	}

    /**
     * Gets the availability timestamp of the product localized to the configured
     * timezone
     *
     * @since 0.1
     * @param WC_Product the product object
     * @return int the timestamp, localized to the current timezone
     */
    public static function get_product_shipping_timestamp( $product ) {

        if( ! is_object( $product ) )
            $product = wc_get_product( $product );

        // If shipping date is not enabled fot this product
        if( ! self::product_has_shipping_date( $product ) )
            return 0;

        $productId = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
        $type = get_post_meta( $productId, Shipping_Date_Core::PRODUCT_SHIPPING_DATE_TYPE, true );

        if( $type == Shipping_Date_Core::TYPE_DATE) {
            $timestamp = get_post_meta( $productId, Shipping_Date_Core::PRODUCT_DATETIME_META_KEY, true );

            // If missing timestamp
            if( ! $timestamp )
                return 0;

            $timestamp = Time_Utils::convert_timestamp_to_wp_timezone($timestamp);

            // Check if defined date is past
            if( time() > $timestamp )
                return 0;

        } else if( $type == Shipping_Date_Core::TYPE_DELAY) {
            $num_days = get_post_meta( $productId, Shipping_Date_Core::PRODUCT_DELAY, true );

            // If missing num days
            if( ! $num_days )
                return 0;

            return Shipping_Date_Core::calculate_timestamp( $num_days );

        // If type is not known
        }else{
            return 0;
        }
    }


    /**
     * Gets the type of product shipping date
     *
     * @since 0.3.3
     * @param WC_Product the product object
     * @return string the type of product shipping date
     */
    public static function get_product_shipping_type( $product ) {

        if( ! is_object( $product ) )
            $product = wc_get_product( $product );

        $productId = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
        $type = get_post_meta( $productId, Shipping_Date_Core::PRODUCT_SHIPPING_DATE_TYPE, true );
        return $type;
    }

    /**
     * Gets the product shipping delay
     *
     * @since 0.3.3
     * @param WC_Product the product object
     * @return int the shipping delay (in days)
     */
    public static function get_product_shipping_delay( $product ) {

        if( ! is_object( $product ) )
            $product = wc_get_product( $product );

        $productId = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
        return get_post_meta( $productId, Shipping_Date_Core::PRODUCT_DELAY, true );
    }
}
