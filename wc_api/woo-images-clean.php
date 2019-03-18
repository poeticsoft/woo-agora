<?php	

	function poeticsoft_api_woo_images_clean_endpoint ($request){

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Images deleted';

		$imgesfiles = glob(WP_CONTENT_DIR . '/uploads/product-images/*');
		
		try {

			foreach($imgesfiles as $file){

				if(is_file($file)){
					
					unlink($file);
				}
			}

		} catch (Exception $e) {

			$data->Status->Code = 'KO';	
			$data->Status->Reason = $e->getMessage();
			$data->Status->Message = '';
		}

		return ($data);
	}	

	add_action(
		'rest_api_init',
		function () {
						
			register_rest_route(
				'poeticsoft', 
				'woo-images-clean', 
				array(

					'methods'  => 'GET',
					'callback' => 'poeticsoft_api_woo_images_clean_endpoint'
				)
			);
		}
	);	 