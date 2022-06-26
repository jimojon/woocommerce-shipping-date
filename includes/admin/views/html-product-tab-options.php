<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $woocommerce;

?>

<div id="wc_shipping_date_data" class="panel woocommerce_options_panel">
	<div class="options_group">
		<?php

		do_action( 'woocommerce_shipping_date_product_options_start' );

        woocommerce_wp_checkbox(
            array(
                'id'          => Shipping_Date_Core::PRODUCT_ENABLED_META_KEY,
                'label'       =>  'Activer la date' //__( 'Activate shipping date', 'woocommerce-shipping-date' )
            )
        );

        woocommerce_wp_select(
            array(
                'id'          => Shipping_Date_Core::PRODUCT_SHIPPING_DATE_TYPE,
                'label'       => 'Type', //__( '', 'woocommerce-shipping-date-type' ),
                'options'     => Shipping_Date_Core::get_type_options()
            )
        );

        woocommerce_wp_text_input(
            array(
                'id'          => Shipping_Date_Core::PRODUCT_DELAY,
                'label'       => 'Délai d\'expédition', //__( '', 'woocommerce-shipping-delay' ),
                'description' => 'jours ouvrés',
                'type' 		  => 'number',
                'custom_attributes'	=> array(
                    'required' => 'required',
                    'min'	=> '1',
                    'step'	=> '1',
                ),
            )
        );

		$availability_timestamp = WC_Shipping_Date_Product::get_product_shipping_timestamp( $post->ID );
        $availability_date = esc_attr( ( 0 === $availability_timestamp ) ? '' : date( 'Y-m-d', $availability_timestamp ).'T'.date( 'H:i', $availability_timestamp ) );
		?>
        <p class="form-field">
			<label for="<?php echo Shipping_Date_Core::PRODUCT_DATETIME_META_KEY ?>"><?php _e( 'Shipping date', 'woocommerce-shipping-date' ); ?></label>
			<input type="datetime-local"
                   class="short"
                   name="<?php echo Shipping_Date_Core::PRODUCT_DATETIME_META_KEY ?>"
                   id="<?php echo Shipping_Date_Core::PRODUCT_DATETIME_META_KEY ?>"
                   value="<?php echo $availability_date ?>"
                   placeholder="YYYY-MM-DD HH:MM"
                   required="required" />
        </p>

		<?php

		do_action( 'woocommerce_shipping_date_product_options_end' );

        /*
        https://woocommerce.github.io/code-reference/files/woocommerce-includes-admin-wc-meta-box-functions.html
        woocommerce_wp_text_input(
            array(
                'id'          => 'shipping_delay_message',
                'label'       => __( 'Message', 'woocommerce-shipping-date' ),
                'placeholder' => __( 'Message affiché sur la fiche produit', 'woocommerce-shipping-date' ),
            )
        );
        */
	?>
	</div>
</div>
