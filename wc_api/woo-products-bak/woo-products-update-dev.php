
<?php

	/* **************************************************************************** */
	/* Products API CREATE UPDATE */

	// https://stackoverflow.com/questions/11503646/woocommerce-create-product-by-code
	// https://stackoverflow.com/questions/47518333/create-programmatically-a-variable-product-and-two-new-attributes-in-woocommerce
	// https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html
	// https://businessbloomer.com/woocommerce-easily-get-product-info-title-sku-desc-product-object/
	// https://www.sbloggers.com/add-a-woocommerce-product-using-custom-php-code-programmatically

	function poeticsoft_api_woo_products_update_dev_endpoint ( $request_data ){

		$data = new stdClass();
		$data->Data = new stdClass();
		$data->Status = new stdClass();
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = '';

		$NewData = file_get_contents(__DIR__ . '/data/web-products.json');
		$NewData = json_decode($NewData, true);	

		/* Remove */
		
		$data->Data->simpledeleted = 0;
		
		$DeletedSimpleProducts = array_filter(
			$NewData,
			function($Product) {

				return $Product['status'] == 'deleted' AND
							 $Product['type'] == 'simple';;
			}
		);
		
		foreach($DeletedProducts as $DeletedProduct) {

			$ExistentID = wc_get_product_id_by_sku($DeletedProduct['sku']);

			if(!$ExistentID) continue;

			// $DP = wc_get_product($ExistentID);

			// if(!$DP) continue;

			// $DP.delete(true);

			$data->Data->simpledeleted++;			
		}

		/* Create */

		$NewSimpleProducts = array_filter(
			$NewData,
			function($Product) {

				return $Product['status'] == 'new' AND
							 $Product['type'] == 'simple';
			}
		);
		
		// https://www.sbloggers.com/add-a-woocommerce-product-using-custom-php-code-programmatically

		$data->Data->newsimplecreated = 0;

		foreach($NewSimpleProducts as $NewSimpleProduct) {

			$ExistentID = wc_get_product_id_by_sku($NewSimpleProduct['sku']);

			if($ExistentID) continue;

			$NSP = new WC_Product();

			$NSP->set_name($NewSimpleProduct['name']);
			$NSP->set_status("publish");
			$NSP->set_catalog_visibility('visible');
			$NSP->set_sku($NewSimpleProduct['sku']);
			$NSP->set_regular_price(100); 
			$NSP->set_manage_stock(true);
			$NSP->set_category_ids($NewSimpleProduct['category_ids']);

			$product_id = $NSP->save();

			$data->Data->newsimplecreated++;
		}

		/* update */
		
		$ChangedProducts = array_filter(
			$NewData,
			function($Product) {

				return $Product['status'] == 'changed';
			}
		);

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