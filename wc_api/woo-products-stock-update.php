
<?php

function poeticsoft_api_woo_products_stock_update_endpoint ($request_data){

	$data = new stdClass();
	$data->Status = new stdClass();	
	$data->Status->Code = 'OK';	
	$data->Status->Reason = '';
	$data->Status->Message = 'Products stock saved';

	try {

		$ProductsStock =  json_encode($request_data->get_params());
		
		$WroteBak = file_put_contents(
			__DIR__ . '/data/stock/data' . date('_Y_m_d_H_i_s') . '.json',
			$ProductsStock
		);

		$Wrote = file_put_contents(
			__DIR__ . '/data/stock/data.json',
			$ProductsStock
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
		'woo-products-stock-update', 
		array(

			'methods'  => 'POST',
			'callback' => 'poeticsoft_api_woo_products_stock_update_endpoint'
		)
	);
});	