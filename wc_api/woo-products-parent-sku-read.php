
<?php

	function poeticsoft_api_woo_products_parent_sku_read_endpoint ($params){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Product sku parent relations readed';

		try {

			$ProductsParentSKU = file_get_contents(__DIR__ . '/data/products-parent-sku.json');
			$data->Data = json_decode($ProductsParentSKU, true);

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
			'woo-products-parent-sku-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_products_parent_sku_read_endpoint'
			)
		);
	});	