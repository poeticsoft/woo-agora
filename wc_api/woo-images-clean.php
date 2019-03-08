<?php

	function delTree($dir) { 

		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 

			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
		} 
		return rmdir($dir); 
	} 	

	function poeticsoft_api_woo_images_clean_endpoint ($request){

		$imgespath = WP_CONTENT_DIR . '/product-images';

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Images deleted';

		delTree($imgespath);

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