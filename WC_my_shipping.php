<?php 
/**
 * WC_my_shipping class.
 *
 * @class 		WC_my_shipping
 * @version		1.0.0
 * @package		Shipping-for-WooCommerce/Classes
 * @category	Class
 * @author 		Tyche Softwares
 */
class WC_my_shipping extends WC_Shipping_Method {

	/**
	 * Constructor. The instance ID is passed to this.
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id                    = 'my_method';
		$this->instance_id           = absint( $instance_id );
		$this->method_title          = __( 'My Shipping Method' );
		$this->method_description    = __( 'My Shipping method for demonstration purposes.' );
		$this->supports              = array(
			'shipping-zones',
			'instance-settings',
		);
		$this->instance_form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Enable/Disable' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable this shipping method' ),
				'default' 		=> 'yes',
			),
			'title' => array(
				'title' 		=> __( 'Method Title' ),
				'type' 			=> 'text',
				'description' 	=> __( 'This controls the title which the user sees during checkout.' ),
				'default'		=> __( 'Test Method' ),
				'desc_tip'		=> true
			)
		);
		$this->enabled              = $this->get_option( 'enabled' );
		$this->title                = $this->get_option( 'title' );

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}
}