<?php

if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

class Shipping_Date_Utils
{
    const PRODUCT_DATETIME_META_KEY = '_wc_shipping_date_datetime';
    const PRODUCT_ENABLED_META_KEY = '_wc_shipping_date_enabled';
    const ORDER_META_KEY = '_wc_shipping_date_datetime';

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

    /**
     * Return max shipping date
     * @param array $items
     * @return int
     * @since 1.0
     */
    /*
    static function max_shipping_date(array $items):int
    {
        $max_time_stamp = 0;

        foreach($items as $item)
        {
            $product = $item['product_id'];
            $product_can_be_ordered = WC_Shipping_Date_Product::product_can_be_pre_ordered($product);

            if ($product_can_be_ordered) {
                $availability_timestamp = WC_Shipping_Date_Product::get_localized_availability_datetime_timestamp($product);
                if ($availability_timestamp > $max_time_stamp)
                    $max_time_stamp = $availability_timestamp;
            }
        }

        return $max_time_stamp;
    }
    */
}