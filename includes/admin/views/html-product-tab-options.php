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

		$availability_timestamp = WC_Shipping_Date_Product::get_localized_shipping_datetime_timestamp( $post->ID );
        $availability_date = esc_attr( ( 0 === $availability_timestamp ) ? '' : date( 'Y-m-d', $availability_timestamp ).'T'.date( 'H:i', $availability_timestamp ) );
		?><p class="form-field">
			<label for="<?php echo Shipping_Date_Utils::PRODUCT_DATETIME_META_KEY ?>"><?php _e( 'Shipping date', 'woocommerce-shipping-date' ); ?></label>
			<input type="datetime-local" class="short" name="<?php echo Shipping_Date_Utils::PRODUCT_DATETIME_META_KEY ?>" id="<?php echo Shipping_Date_Utils::PRODUCT_DATETIME_META_KEY ?>" value="<?php echo $availability_date ?>" placeholder="YYYY-MM-DD HH:MM"  />
        </p>
		<?php

        woocommerce_wp_checkbox(
            array(
                'id'          => Shipping_Date_Utils::PRODUCT_ENABLED_META_KEY,
                'label'       => __( '', 'woocommerce-shipping-date' ),
                'description' => __( 'Activate shipping date', 'woocommerce-shipping-date' ),
            )
        );

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
