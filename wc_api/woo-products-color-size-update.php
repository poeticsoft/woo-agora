
<?php

function poeticsoft_api_woo_products_color_size_update_endpoint ($request_data){

	$data = new stdClass();	
	$data->Data = [];
	$data->Status = new stdClass();	
	$data->Status->Code = 'OK';	
	$data->Status->Reason = '';
	$data->Status->Message = 'Product color size updated';

	try {

		$ProductsColorSize =  json_encode($request_data->get_params());
		
		$WroteBak = file_put_contents(
			__DIR__ . '/data/color-size/data' . date('_Y_m_d_H_i_s') . '.json',
			$ProductsColorSize
		);

		$Wrote = file_put_contents(
			__DIR__ . '/data/color-size/data.json',
			$ProductsColorSize
		);

		if(!$Wrote OR !$WroteBak) {

			$data->Status->Code = 'KO';	
			$data->Status->Reason = 'Unknow error writing list';
			$data->Status->Message = '';
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
		'woo-products-color-size-update', 
		array(

			'methods'  => 'POST',
			'callback' => 'poeticsoft_api_woo_products_color_size_update_endpoint'
		)
	);
});	