
<?php

	/* **************************************************************************** */
	/* Products API CREATE UPDATE */

	// https://stackoverflow.com/questions/11503646/woocommerce-create-product-by-code
	// https://stackoverflow.com/questions/47518333/create-programmatically-a-variable-product-and-two-new-attributes-in-woocommerce
	// https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html

	require_once(dirname(__FILE__) . '/create-update/update.php');

	function poeticsoft_api_woo_products_create_update_endpoint ( $request_data ){

		$data = new stdClass();
		$data->Status = new stdClass();
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = '';

		$ProductNewData = $request_data->get_params();
		$ProductId = $ProductNewData['id'];
		$ProductType = $ProductNewData['type'];

		if(
			$ProductType == 'variable' ||
			$ProductType == 'simple'
		) {

			// CREATE UPDATE 1st level products

			if($ProductId) {

				$WCProduct = wc_get_product($ProductId);

				if($WCProduct) {

					//create-update/update.php

					updateWCProduct($WCProduct);

					$data->Data = $ProductNewData;

				} else {

					createWCProduct($ProductNewData, true);

					$data->Data['action'] = 'CREATE with ID ' + $ProductId;
				}
			} else {

				createWCProduct($ProductNewData, false);

				$data->Data['action'] = 'CREATE ' + $ProductId;
			}
		} elseif ($ProductType == 'variation') {


		}

		return ($data);
	} 
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-create-update', 
			array(
				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_products_create_update_endpoint'
			)
		);
	});	