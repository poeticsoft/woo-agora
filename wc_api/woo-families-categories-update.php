
<?php

	function poeticsoft_api_woo_families_categories_update_endpoint ($request_data){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Families categories updated';

		$NewList =  json_encode($request_data->get_params());

		$fp = fopen(__DIR__ . '/data/families-categories.json', 'w');
		fwrite($fp, $NewList);
		fclose($fp);

		$data->Status->Reason = $wrote;

		return ($data); 
	}	
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-families-categories-update', 
			array(

				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_families_categories_update_endpoint'
			)
		);
	});	