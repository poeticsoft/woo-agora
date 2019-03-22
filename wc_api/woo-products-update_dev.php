
<?php

	/* **************************************************************************** */
	/* Products API CREATE UPDATE */

	// https://stackoverflow.com/questions/11503646/woocommerce-create-product-by-code
	// https://stackoverflow.com/questions/47518333/create-programmatically-a-variable-product-and-two-new-attributes-in-woocommerce
	// https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html

	function poeticsoft_api_woo_products_update_dev_endpoint ( $request_data ){

		$data = new stdClass();
		$data->Status = new stdClass();
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = '';

		$NewData = file_get_contents(__DIR__ . '/data/web-products.json');
		$data->Data = json_decode($NewData, true);

		return ($data);
	} 
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-update-dev', 
			array(
				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_products_update_dev_endpoint'
			)
		);
	});	