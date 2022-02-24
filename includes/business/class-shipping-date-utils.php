<?php

if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

class Shipping_Date_Utils
{
    /*
    UPDATE `wp_postmeta` SET meta_key = '_wsd_product_shipping_datetime' WHERE meta_key = '_wc_shipping_date_datetime';
    UPDATE `wp_postmeta` SET meta_key = '_wsd_product_shipping_date_enabled' WHERE meta_key = '_wc_shipping_date_enabled';
    UPDATE `wp_postmeta` SET meta_key = '_wsd_order_shipping_datetime' WHERE meta_key = '_wc_shipping_date_order_shipping_datetime';
     */

    const PRODUCT_DATETIME_META_KEY = '_wsd_product_shipping_datetime';
    const PRODUCT_ENABLED_META_KEY = '_wsd_product_shipping_date_enabled';
    const ORDER_META_KEY = '_wsd_order_shipping_datetime';

    /**
     * Return readable date
     * @param int $timestamp
     * @return string
     * @since 1.0
     */
    static function format_date(int $timestamp):string
    {
        return date_i18n( wc_date_format(), $timestamp );
    }

    /**
     * Return order shipping date
     * @param $order WC_Order
     * @return mixed
     * @since 1.0
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
     * @since 1.0
     */
    static function add_order_shipping_date_timestamp(WC_Order $order, int $timestamp):void
    {
        $order->add_meta_data(self::ORDER_META_KEY, $timestamp);
        $order->save();
    }
}