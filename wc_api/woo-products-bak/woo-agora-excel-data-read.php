
<?php

	function poeticsoft_api_woo_agora_excel_data_read_endpoint ($params){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Agora excel data readed';

		try {

			$Relations = file_get_contents(__DIR__ . '/data/agora-excel/data.json');
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
			'woo-agora-excel-data-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_agora_excel_data_read_endpoint'
			)
		);
	});	