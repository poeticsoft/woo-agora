
<?php

	function poeticsoft_api_woo_products_raw_read_endpoint ($params){
		
		$args = array(
			'status' => 'publish' 
		); 

		$WooProducts = wc_get_products($args);

		$data = new stdClass();
		$data->Data = [];	
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Web published products raw readed';

		foreach ($WooProducts as $WooProduct) {

			$ProductData = $WooProduct->get_data();
			$ProductType = $WooProduct->get_type();

			array_push(
				$data->Data,
				$ProductData
			);

			if($ProductType == 'variable') {
				
				$ProductVariations = $WooProduct->get_available_variations();

				foreach ($ProductVariations as $ProductVariation) {
					
					array_push(
						$data->Data,
						$ProductVariation
					);
				}	
			}
		}

		return ($data);
	}	
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-raw-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_products_raw_read_endpoint'
			)
		);
	});	