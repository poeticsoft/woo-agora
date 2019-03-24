
<?php

	function poeticsoft_api_woo_families_categories_read_endpoint ($params){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Families categories readed';

		try { 

			$Relations = file_get_contents(__DIR__ . '/data/families-categories/data.json');
			$data->Data = json_decode($Relations, true);
	
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
			'woo-families-categories-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_families_categories_read_endpoint'
			)
		);
	});	