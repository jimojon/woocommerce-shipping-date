<?php

if(!defined( 'ABSPATH')){
    exit;
}

/**
 * Class WC_Shipping_Date_Admin_Settings_Messages
 *
 * TODO: put translations in languages/
 * TODO: global shipping date
 *
 */
class WC_Shipping_Date_Admin_Settings_Messages extends Admin_Settings
{

	public function __construct() {

        parent::__construct('shipping_message', 'Messages', 'woocommerce-shipping-date');
	}

    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public function get_settings() {

        $settings = array(

            'section_title' => array(
                'name'      => __( 'Confirmation de commande', 'woocommerce-shipping-date' ),
                'type'      => 'title',
                'desc'      => '',
                'id'        => 'wsd_order_received_section_title'
            ),

            'intro' => array(
                'name'      => __( 'Introduction', 'woocommerce-shipping-date' ),
                'type'      => 'textarea',
                'desc'      => __( 'Affiché sur la page de confirmation de commande seulement', 'woocommerce-shipping-date' ),
                'id'        => 'wsd_order_received_intro'
            ),

            'download_text' => array(
                'name'      => __( 'Texte spécifique aux commandes avec téléchargements', 'woocommerce-shipping-date' ),
                'type'      => 'textarea',
                 'id'       => 'wsd_order_received_download_text'
            ),

            'shipping_later_text' => array(
                'name' => __( 'Texte spécifique aux commande AVEC date d\'expédition', 'woocommerce-shipping-date' ),
                'type' => 'textarea',
                'desc' => 'Affichez la date d\'expédition en utilisant la balise %SHIPPING_DATE%',
                'id'   => 'wsd_order_received_shipping_later_text'
            ),

            'shipping_ready_text' => array(
                'name' => __( 'Texte spécifique aux commande SANS date d\'expédition', 'woocommerce-shipping-date' ),
                'type' => 'textarea',
                'id'   => 'wsd_order_received_shipping_ready_text'
            ),

            'shipping_common_text' => array(
                'name' => __( 'Texte commun aux commandes nécessitant une expéditions', 'woocommerce-shipping-date' ),
                'type' => 'textarea',
                'id'   => 'wsd_order_received_shipping_text'
            ),

            'confirm_outro' => array(
                'name' => __( 'Conclusion', 'woocommerce-shipping-date' ),
                'type' => 'textarea',
                'desc' => __( 'Affiché sur la page de confirmation de commande seulement', 'woocommerce-shipping-date' ),
                'id'   => 'wsd_order_received_outro'
            ),

            /**
            'section_title' => array(
                'name'     => __( 'Global shipping date', 'woocommerce-shipping-date' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'shipping_date_section_title'
            ),
            'activated' => array(
                'name' => __( 'Activer la date d\'expédition globale', 'woocommerce-shipping-date' ),
                'type' => 'checkbox',
                'desc' => __( 'Ce réglage s\'applique sur toute la boutique', 'woocommerce-shipping-date' ),
                'id'   => 'shipping_date_global_activated'
            ),
            'undefined' => array(
                'name' => __( 'La date d\'expédition est indéfinie', 'woocommerce-shipping-date' ),
                'type' => 'checkbox',
                'desc' => __( 'Ce réglage s\'applique sur toute la boutique', 'woocommerce-shipping-date' ),
                'id'   => 'shipping_date_global_is_undefined'
            ),
            'message' => array(
                'name' => __( 'Message', 'woocommerce-shipping-date' ),
                'type' => 'text',
                'desc' => __( 'Message affiché sur toutes les fiches produits', 'woocommerce-shipping-date' ),
                'id'   => 'shipping_date_global_message'
            ),

             */

            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'shipping_date_section_end'
            )
        );

        return apply_filters( 'woocommerce_shipping_date', $settings );
    }
}

new WC_Shipping_Date_Admin_Settings_Messages();
