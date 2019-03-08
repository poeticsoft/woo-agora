
<?php

 	require_once(dirname(__FILE__) . '/class.upload.php');	

	function poeticsoft_api_woo_images_upload_endpoint ($request){

		$imgespath = WP_CONTENT_DIR . '/product-images';
		$imagesviewpath = $imgespath . '/view';
		$imagesthumbpath = $imgespath . '/thumb';
		$handle = new upload($_FILES['image']);

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Image uploaded';

		if ($handle->uploaded) {

			$handle->image_convert = 'jpg';
			$handle->jpeg_quality = 70;
			$handle->image_resize = true;
			$handle->image_ratio_y = true;
			$handle->image_x = 300;
			$handle->file_overwrite = true;
			
			$handle->process($imagesthumbpath); 
			if ($handle->processed) { /* everything was fine ! */ } 
			else {	

				$data->Status->Code = 'KO';	
				$data->Status->Reason = $handle->error;
			}

			$handle->image_convert = 'jpg';
			$handle->jpeg_quality = 70;
			$handle->image_resize = true;
			$handle->image_ratio_y = true;
			$handle->image_x = 1600;
			$handle->file_overwrite = true;
			
			$handle->process($imagesviewpath);
			if ($handle->processed) { /* everything was fine ! */ } 
			else {	

				$data->Status->Code = 'KO';	
				$data->Status->Reason = $handle->error;
			}

			$handle-> clean();

		} else {				

			$data->Status->Code = 'KO';	
			$data->Status->Reason = $handle->error;
		}

		return ($data);
	}	

	add_action(
		'rest_api_init',
		function () {
						
			register_rest_route(
				'poeticsoft', 
				'woo-images-upload', 
				array(

					'methods'  => 'POST',
					'callback' => 'poeticsoft_api_woo_images_upload_endpoint'
				)
			);
		}
	);	 