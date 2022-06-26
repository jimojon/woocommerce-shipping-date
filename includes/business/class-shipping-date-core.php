<?php

if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

class Shipping_Date_Core
{
    /*
    Upgrade from 0.2:
    UPDATE `wp_postmeta` SET meta_key = '_wsd_product_shipping_datetime' WHERE meta_key = '_wc_shipping_date_datetime';
    UPDATE `wp_postmeta` SET meta_key = '_wsd_product_shipping_date_enabled' WHERE meta_key = '_wc_shipping_date_enabled';
    UPDATE `wp_postmeta` SET meta_key = '_wsd_order_shipping_datetime' WHERE meta_key = '_wc_shipping_date_order_shipping_datetime';
     */

    const PRODUCT_SHIPPING_DATE_TYPE = '_wsd_product_shipping_type';
    const PRODUCT_DATETIME_META_KEY = '_wsd_product_shipping_datetime';
    const PRODUCT_ENABLED_META_KEY = '_wsd_product_shipping_date_enabled';
    const ORDER_META_KEY = '_wsd_order_shipping_datetime';

    const PRODUCT_DELAY = '_wsd_product_shipping_delay';

    const TYPE_DATE = 'date';
    const TYPE_DELAY = 'delay';


    /**
     * Return readable date
     * @param int $timestamp
     * @return string
     * @since 0.1
     */
    static function get_type_options():array
    {
        return  array(
            self::TYPE_DATE => 'Date d\'expédition',
            self::TYPE_DELAY => 'Délai d\'expédition'
        );
    }

    /**
     * Return order shipping date
     * @param $order WC_Order
     * @return mixed
     * @since 0.1
     */
    static function get_order_shipping_date_timestamp(WC_Order $order)
    {
        return $order->get_meta(self::ORDER_META_KEY, true);
    }

    /**
     * Save shipping date in order
     * @param $order WC_Order
     * @param $timestamp int
     * @return void
     * @since 0.1
     */
    static function add_order_shipping_date_timestamp(WC_Order $order, int $timestamp):void
    {
        $order->add_meta_data(self::ORDER_META_KEY, $timestamp);
        $order->save();
    }

    /**
     * Return timestamp
     * @param int $timestamp
     * @return string
     * @since 0.3.3
     */
    static function calculate_timestamp( int $num_days ):int {
        $now = new DateTime( Time_Utils::get_wp_timezone_string() );
        $ms = $num_days * 24 * 60 * 60;
        return $now->getTimestamp() + $ms;
    }
}