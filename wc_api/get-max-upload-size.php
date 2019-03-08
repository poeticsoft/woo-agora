
<?php

	function poeticsoft_api_get_max_upload_size_endpoint ($params){

		function file_upload_max_size() {

			static $max_size = -1;
		
			if ($max_size < 0) {

				// Start with post_max_size.

				$post_max_size = parse_size(ini_get('post_max_size'));
				if ($post_max_size > 0) {

					$max_size = $post_max_size;
				}
		
				// If upload_max_size is less, then reduce. Except if upload_max_size is
				// zero, which indicates no limit.

				$upload_max = parse_size(ini_get('upload_max_filesize'));
				if ($upload_max > 0 && $upload_max < $max_size) {

					$max_size = $upload_max;
				}
			}
			return $max_size;
		}
		
		function parse_size($size) {

			$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
			$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.

			if ($unit) {

				// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
				return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
			}
			else {

				return round($size);
			}
		}

		$data = new stdClass();	
		$data->Data = new stdClass();
		$data->Data->MaxSize = file_upload_max_size();
		$data->Data->HMaxSize = formatSizeUnits(file_upload_max_size());
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'This is the maximun upload size';

		return ($data);
	}	

	add_action( 'rest_api_init', function () {
						
		register_rest_route(
			'poeticsoft', 
			'get-max-upload-size', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_get_max_upload_size_endpoint'
			)
		);
	});	 