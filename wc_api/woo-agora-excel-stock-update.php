
<?php

	function poeticsoft_api_woo_agora_excel_stock_update_endpoint($request_data){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Agora excel stock saved';
		
		$ExcelData = $request_data->get_params();
		$InventarioSheetData = $ExcelData['InventarioSheetData'];

		try {

			$InventarioSheet =  json_encode($InventarioSheetData);
			
			$WroteStockExcelBak = file_put_contents(
				__DIR__ . '/data/agora-stock/data' . date('_Y_m_d_H_i_s') . '.json',
				$InventarioSheet
			);

			$WroteStockExcel = file_put_contents(
				__DIR__ . '/data/agora-stock/data.json',
				$InventarioSheet
			);

			if(!$WroteStockExcelBak OR
				 !$WroteStockExcel
			) {

				$data->Status->Code = 'KO';	
				$data->Status->Reason = 'Unknow error writing data';
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
			'woo-agora-excel-stock-update', 
			array(

				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_agora_excel_stock_update_endpoint'
			)
		);
	});	