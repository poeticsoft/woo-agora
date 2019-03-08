
<?php

	function poeticsoft_api_get_agora_fields_footprint_endpoint ($params){

		$data = new stdClass();	
		$data->Data = '';
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'This is the correct footprint for an agora excel';

		try {

			$footprint = file_get_contents(__DIR__ . '/data/agora-fields-footprint.json');

			if($footprint) {				

				$data->Data = json_decode($footprint, true);
				
			} else {

				$data->Status->Code = 'KO';	
				$data->Status->Reason = 'Error reading footprint file';
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
			'get-agora-fields-footprint', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_get_agora_fields_footprint_endpoint'
			)
		);
	});	 