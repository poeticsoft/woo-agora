
<?php

	function poeticsoft_api_woo_agora_excel_stock_read_endpoint ($params){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Agora stock data readed';

		try {

			$Stock = file_get_contents(__DIR__ . '/data/agora-stock/data.json');
			$data->Data = json_decode($Stock, true);
	
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
			'woo-agora-excel-stock-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_agora_excel_stock_read_endpoint'
			)
		);
	});	