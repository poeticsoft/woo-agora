
<?php

	function poeticsoft_api_woo_families_categories_update_endpoint ($request_data){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Families categories updated';

		try {

			$NewList =  json_encode($request_data->get_params());
			$Wrote = file_put_contents(
				__DIR__ . '/data/families-categories.json',
				$NewList
			);

			if(!$Wrote) {

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
			'woo-families-categories-update', 
			array(

				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_families_categories_update_endpoint'
			)
		);
	});	