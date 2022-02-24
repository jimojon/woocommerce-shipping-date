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
    public function woocommerce_get_stock_html($html)
    {
        global $product;

        $timestamp = $this->get_localized_shipping_datetime_timestamp($product);

        if($timestamp == 0)
            $newHTML = $html;

        else if($timestamp < time())
            $newHTML = $html;
        else
            $newHTML = '<div class="woocommerce-variation-availability"><p class="stock in-stock">Expédition à partir du '.Shipping_Date_Utils::format_date($timestamp).'</p></div>';

        if(isset($info))
            $newHTML .= $info;

        return $newHTML;
    }

	/**
	 * Checks if a given product has a shipping date enabled
	 *
	 * @since 0.1
	 * @param object|int $product preferably the product object, or product ID if object is inconvenient to provide
	 * @return bool true if product has an enabled shipping date, false otherwise
	 */
	public static function product_has_shipping_date($product ) {
		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		return is_object( $product ) && 'yes' === get_post_meta( $product->get_id(), Shipping_Date_Utils::PRODUCT_ENABLED_META_KEY, true );
	}

    /**
     * Gets the availability timestamp of the product localized to the configured
     * timezone
     *
     * @since 0.1
     * @param WC_Product|int $product the product object or post identifier
     * @return int the timestamp, localized to the current timezone
     */
    public static function get_localized_shipping_datetime_timestamp($product ) {
        if ( ! is_object( $product ) ) {
            $product = wc_get_product( $product );
        }

        if ( ! $product || ! $timestamp = get_post_meta( $product->is_type( 'variation' ) && version_compare( WC_VERSION, '3.0', '>=' ) ? $product->get_parent_id() : $product->get_id(), Shipping_Date_Utils::PRODUCT_DATETIME_META_KEY, true ) ) {
            return 0;
        }

        try {
            // Get datetime object from unix timestamp
            $datetime = new DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );

            // Set the timezone to the site timezone
            $datetime->setTimezone( new DateTimeZone( self::get_wp_timezone_string() ) );

            // Return the unix timestamp adjusted to reflect the site's timezone
            return $timestamp + $datetime->getOffset();

        } catch ( Exception $e ) {
            global $woocommerce_shipping_date;

            // Log error
            $woocommerce_shipping_date->log( $e->getMessage() );
            return 0;
        }
    }

    /**
     * Returns the timezone string for a site, even if it's set to a UTC offset
     *
     * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
     *
     * @since 0.1
     * @return string valid PHP timezone string
     */
    public static function get_wp_timezone_string() {

        // If site timezone string exists, return it
        if ( $timezone = get_option( 'timezone_string' ) ) {
            return $timezone;
        }

        // Get UTC offset, if it isn't set then return UTC
        if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
            return 'UTC';
        }

        // Adjust UTC offset from hours to seconds
        $utc_offset *= 3600;

        // Attempt to guess the timezone string from the UTC offset
        $timezone = timezone_name_from_abbr( '', $utc_offset );

        // Last try, guess timezone string manually
        if ( false === $timezone ) {

            $is_dst = date( 'I' );

            foreach ( timezone_abbreviations_list() as $abbr ) {
                foreach ( $abbr as $city ) {
                    if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset && ! empty( $city['timezone_id'] ) ) {
                        return $city['timezone_id'];
                    }
                }
            }
        }

        // Fallback to UTC offset
        return $utc_offset / 3600;
    }


	/**
	 * Gets the availability date of the product localized to the site's date format
	 *
	 * @since 0.1
	 * @param object|int $product preferably the product object, or product ID if object is inconvenient to provide
	 * @param string $none_text optional text to return if there is no availability datetime set
	 * @return string the formatted availability date


	public static function get_localized_availability_date( $product, $none_text = '' ) {
		if ( '' === $none_text ) {
			$none_text = __( 'at a future date', 'woocommerce-shipping-date' );
		}

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$timestamp = self::get_localized_availability_datetime_timestamp( $product );

		if ( ! $timestamp ) {
			return $none_text;
		}

		return apply_filters( 'woocommerce_shipping_date_localized_availability_date', date_i18n( wc_date_format(), $timestamp ), $product, $none_text );
	}
     */
}
