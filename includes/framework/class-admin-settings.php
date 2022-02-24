<?php

if(!defined( 'ABSPATH')){
    exit;
}

/**
 * Class Admin_Settings
 *
 * @author Jonas
 * @ref https://www.speakinginbytes.com/2014/07/woocommerce-settings-tab/
 */
class Admin_Settings
{
    var $tab_id;
    var $tab_title;
    var $domain;

	function __construct($tab_id, $tab_title, $domain) {

	    $this->tab_id = $tab_id;
	    $this->tab_title = $tab_title;
	    $this->domain = $domain;

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50);
        add_action( 'woocommerce_settings_'.$tab_id, array( $this, 'create_settings_form' ));
        add_action( 'woocommerce_settings_'.$tab_id, array( $this, 'update_settings' ));
	}

    /**
    * Add a new settings tab to the WooCommerce settings tabs array.
    *
    * @param array $settings_tabs Array
    * @return array $settings_tabs Array
    */
    public function add_settings_tab(array $settings_tabs) {

        $settings_tabs[$this->tab_id] = __( $this->tab_title, $this->domain );
        return $settings_tabs;
    }

    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public function get_settings() {

        throw new Exception('Method must be overriden');

        $settings = [];
        return apply_filters( 'plugin_id', $settings );
    }

    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    function create_settings_form() {
        woocommerce_admin_fields($this->get_settings() );
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public function update_settings() {
        woocommerce_update_options( $this->get_settings() );
    }
}
