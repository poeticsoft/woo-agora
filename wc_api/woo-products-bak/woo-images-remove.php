<?php	

	function poeticsoft_api_woo_images_remove_endpoint ($request){

		// $imgesfile = plugin_dir_path(__FILE__) . 'uploads';

		$data = new stdClass();	
		$data->Data = $request;
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Images deleted';

		return ($data);
	}	

	add_action(
		'rest_api_init',
		function () {
						
			register_rest_route(
				'poeticsoft', 
				'woo-images-remove', 
				array(

					'methods'  => 'GET',
					'callback' => 'poeticsoft_api_woo_images_remove_endpoint'
				)
			);
		}
	);	 