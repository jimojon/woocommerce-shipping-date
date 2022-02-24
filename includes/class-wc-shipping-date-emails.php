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

    var $siteHeaderText;
    var $downloadText;
    var $shippingLaterText;
    var $shippingReadyText;
    var $shippingCommonText;
    var $siteFooterText;

	/**
	 * Adds needed hooks / filters
	 *
	 * @since 0.1
	 */
	public function __construct()
    {
        add_action('woocommerce_email_processing_text', array( $this, 'set_email_processing_text'), 10, 2);
        add_filter('woocommerce_thankyou_order_received_text', array( $this, 'set_thankyou_order_received_text'), 10, 2);

        $this->siteHeaderText = '<strong>M.E.R.C.I</strong><br/><br/>❤ ❤ ❤';
        $this->downloadText = '<strong>Vos téléchargements sont disponibles ci-dessous</strong> et restent accessibles <a href="/my-account/downloads/">sur votre compte</a>.';
        $this->shippingLaterText = '<strong>Votre commande est en cours d\'approvisionnement et sera expédiée à partir du %SHIPPING_DATE%.</strong>';
        $this->shippingReadyText = '<strong>Nous allons bientôt préparer votre commande !</strong>';
        $this->shippingCommonText = 'Rendez-vous sur la page <a href="https://boutique.my365.fr/livraison">Livraison</a> pour plus d\'informations sur les modalités d\'expédition de votre colis et par ici <a href="/my-account/orders/">pour suivre le statut de votre commande</a>.';
        $this->siteFooterText = '❤ ❤ ❤<br/><br/>Pour suivre toutes nos aventures, retrouvez-nous sur <a href="https://www.instagram.com/MyAgenda365/" target="_blank">Instagram</a>, <a href="https://www.facebook.com/MyAgenda365/" target="_blank">Facebook</a>, <a href="https://twitter.com/myAgenda365" target="_blank">Twitter</a> ou <a href="https://tiktok.com/@myAgenda365" target="_blank">TikTok</a>, et inscrivez-vous à <a href="https://shop.my365.fr/newsletter">notre newsletter</a>.';
    }

    /**
     * @param $order
     * @param $email
     * @return void
     * @since 0.1
     */
    public function set_email_processing_text(WC_Order $order, $email)
    {
        echo $this->getText($order, 'email');
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

        echo $this->getText($order, 'page');
    }

    private function getText(WC_Order $order, string $version):string
    {
        $datetime = Shipping_Date_Utils::get_order_shipping_date_timestamp($order);

        $shippingInfos = new Shipping_Infos($order);

        $shipping_date = null;
        if(!empty($datetime))
            $shipping_date = Shipping_Date_Utils::format_date($datetime);

        $text = '';
        if($version == 'page')
            $text .= get_option('shipping_date_thank_you').'<br><br>';

        // Shippable
        if($shippingInfos->isShippable())
        {
            if(isset($shipping_date))
                $text .= str_replace('%SHIPPING_DATE%', $shipping_date, $this->shippingLaterText).' ';
            else{
                $text .= $this->shippingReadyText.' ';
            }

            $text .= '<br/>'.$this->shippingCommonText.'<br/><br/>';
        }

        // Downloadable
        if($shippingInfos->isDownloadable()){
            $text .= $this->downloadText.'<br/><br/>';
        }

        if($version == 'page')
            $text .= $this->siteFooterText;

        return $text;
    }
}
