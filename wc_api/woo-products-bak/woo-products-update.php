
<?php

	/* **************************************************************************** */
	/* Products API CREATE UPDATE */

	// https://stackoverflow.com/questions/11503646/woocommerce-create-product-by-code
	// https://stackoverflow.com/questions/47518333/create-programmatically-a-variable-product-and-two-new-attributes-in-woocommerce
	// https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html

	require_once(dirname(__FILE__) . '/class.woo.products.update.php');	

	function poeticsoft_api_woo_products_update_endpoint ( $request_data ){

		$data = new stdClass();
		$data->Status = new stdClass();
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Products data updated';

		$ProductData = $request_data->get_params();

		try {

			$ProductDataJSON =  json_encode($ProductData);
			$Wrote = file_put_contents(
				__DIR__ . '/data/web-products.json',
				$ProductDataJSON
			);

			if(!$Wrote) {

				$data->Status->Code = 'KO';	
				$data->Status->Reason = 'Unknow error writing data';
				$data->Status->Message = '';
			} else {

				$UpdateProducts = new WooProductsUpdate($ProductData);
			}

		} catch (Exception $e) {

			$data->Status->Code = 'KO';	
			$data->Status->Reason = $e->getMessage();
			$data->Status->Message = '';
		}

		return ($data);
	} 
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-update', 
			array(
				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_products_update_endpoint'
			)
		);
	});	