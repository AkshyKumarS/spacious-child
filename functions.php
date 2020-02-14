<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

require (get_template_directory().'/inc/WC_my_shipping.php');

function register_my_method( $methods ) {

	$methods[ 'my_method' ] = 'WC_my_shipping';
	return $methods;

}
add_filter( 'woocommerce_shipping_methods', 'register_my_method' );

/**
* Apply a coupon for minimum cart total
*/

add_action( 'woocommerce_before_cart' , 'add_coupon_notice' );
add_action( 'woocommerce_before_checkout_form' , 'add_coupon_notice' );

function add_coupon_notice() {

	$cart_total = WC()->cart->get_subtotal();
	$minimum_amount = 50;
	$currency_code = get_woocommerce_currency();
	wc_clear_notices();

	if ( $cart_total < $minimum_amount ) {
			WC()->cart->remove_coupon( 'CNFWWQ8M' );
			wc_print_notice( "Get 50% off if you spend more than $minimum_amount $currency_code!", 'notice' );
	} else {
			WC()->cart->apply_coupon( 'CNFWWQ8M' );
			wc_print_notice( 'You just got 50% off your order!', 'notice' );
	}
		wc_clear_notices();
}

/**
 * Make the billing address fields wider
 */
add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields( $fields ) {

     $fields['billing_address_1']['class'] = array( 'form-row-wide' );
     $fields['billing_address_2']['class'] = array( 'form-row-wide' );

     return $fields;
}

/**
 * Override loop template and show quantities next to add to cart buttons
 */
add_filter( 'woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_woocommerce_loop_add_to_cart_link', 10, 2 );

function quantity_inputs_for_woocommerce_loop_add_to_cart_link( $html, $product ) {

	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {

		$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= woocommerce_quantity_input( array(), $product, false );
		$html .= '<button type="submit" class="button alt">' . esc_html( 'Add to Basket' ) . '</button>';
        $html .= '</form>';
        
	}
    return $html;
    
}

/**
 * Change the default state and country on the checkout page
 */
add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_billing_state', 'change_default_checkout_state' );

function change_default_checkout_country($country) {
    return 'BR'; // country code
}

function change_default_checkout_state() {
    return 'KL'; // state code
}


/**
 * Add custom sorting options (asc/desc)
 */
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );

function custom_woocommerce_get_catalog_ordering_args( $args ) {

  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'random_list' == $orderby_value ) {
		$args['orderby'] = 'rand';
		$args['order'] = '';
		$args['meta_key'] = '';
	}
    return $args;
    
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

function custom_woocommerce_catalog_orderby( $sortby ) {

	$sortby['random_list'] = 'Random';
    return $sortby;
    
}