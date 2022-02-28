<?php

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}

/**
 * Shipping Date Emails class
 *
 * Customizes emails
 *
 * @since 0.1
 */
class WC_Shipping_Date_Emails {

    var $introText;
    var $downloadText;
    var $shippingLaterText;
    var $shippingReadyText;
    var $shippingCommonText;
    var $outroText;

	/**
	 * Adds needed hooks / filters
	 *
	 * @since 0.1
	 */
	public function __construct()
    {
        add_action('woocommerce_email_order_details', array( $this, 'set_email_processing_text'), 10, 4);
        add_filter('woocommerce_thankyou_order_received_text', array( $this, 'set_thankyou_order_received_text'), 10, 2);
    }

    /**
     * @param $order WC_Order
     * @param $sent_to_admin bool
     * @param $plain_text bool
     * @param $email string
     * @return void
     * @since 0.3.2
     */
    public function set_email_processing_text(WC_Order $order, $sent_to_admin, $plain_text, $email)
    {
        if($sent_to_admin)
            return;

        if(isset($order))
            echo $this->getText($order, 'email');
        else
            echo $email;
    }

    /**
     * @param $text
     * @param $order
     * @return void
     * @since 0.1
     */
    public function set_thankyou_order_received_text($text, ?WC_Order $order)
    {
        if(!isset($order))
        {
            echo '<h2>Oups !</h2>Vous ne pouvez pas accéder à cette page.<br>Pour retrouver toutes vos commandes, <a href="/my-account/orders/">c\'est par ici</a>.';
            return;
        }

        echo $this->getText($order, 'page', $text);
    }

    private function getText(WC_Order $order, string $version, string $original_text = ''):string {

        if( !isset( $this->introText ) ) {
            $this->introText = get_option('wsd_order_received_intro');
            $this->downloadText = get_option('wsd_order_received_download_text');
            $this->shippingLaterText = get_option('wsd_order_received_shipping_later_text');
            $this->shippingReadyText = get_option('wsd_order_received_shipping_ready_text');
            $this->shippingCommonText = get_option('wsd_order_received_shipping_text');
            $this->outroText = get_option('wsd_order_received_outro');
        }

        $datetime = Shipping_Date_Utils::get_order_shipping_date_timestamp($order);

        $shippingInfos = new Shipping_Infos($order);

        $shipping_date = null;
        if( !empty( $datetime ) )
            $shipping_date = Shipping_Date_Utils::format_date($datetime);

        $text = '';

        // Intro
        if( 'page' === $version )
            if( !empty( $this->introText ) )
                $text .= $this->introText.'<br/><br/>';
            else
                $text = $original_text.'<br/><br/>';

        // Shippable
        if( $shippingInfos->isShippable() ) {

            // With shipping date
            if ( isset( $shipping_date )) {
                if( !empty( $this->shippingLaterText ) )
                    $text .= str_replace( '%SHIPPING_DATE%', $shipping_date, $this->shippingLaterText ).'<br/><br/>';

                // Without shipping date
            } else if( !empty ( $this->shippingReadyText ) ) {
                $text .= $this->shippingReadyText.'<br/><br/>';
            }

            // Common text
            if(!empty( $this->shippingCommonText ))
                $text .= $this->shippingCommonText.'<br/><br/>';
        }

        // Downloadable
        if($shippingInfos->isDownloadable() && !empty( $this->downloadText ) ) {
            $text .= $this->downloadText.'<br/><br/>';
        }

        // Outro
        if( 'page' === $version && !empty( $this->outroText ) )
            $text .= $this->outroText;

        return $text;
    }
}
