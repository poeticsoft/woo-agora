
<?php

	function poeticsoft_api_woo_products_remove_all_endpoint ($request_data){

		$params = $request_data->get_params();
		
		$args = array(
			'status' => $params['status'],			
			'numberposts' => -1
		); 

		$WooProducts = wc_get_products($args);

		$data = new stdClass();
		$data->Data = [];	
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = '';

		$DeletedList = '';

		foreach ($WooProducts as $WooProduct) {

			if($ProductType == 'variable') {
				
				$ProductVariations = $WooProduct->get_available_variations();

				foreach ($ProductVariations as $ProductVariation) {
					
					$DeletedList = $DeletedList . ' | ' . $ProductVariation->get_id();
					
					$ProductVariation->delete(true);
				}	
			}			
					
			$DeletedList = $DeletedList . ' | ' . $WooProduct->get_id();

			$WooProduct->delete(true);
		}

		$data->Status->Message = $params['status'] . $DeletedList;

		return ($data);
	}	
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-remove-all', 
			array(

				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_products_remove_all_endpoint'
			)
		);
	});	