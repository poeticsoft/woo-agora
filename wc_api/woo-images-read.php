<?php

	require_once(dirname(__FILE__) . '/format-size-units.php');

	class Imagen {
		public $name;
		public $size;
		public $date;
	}

	function poeticsoft_api_woo_images_read_endpoint ($request) {

		$imgespath = WP_CONTENT_DIR . '/product-images/thumb';		

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Images readed';

		if(is_dir($imgespath)) {

			foreach (new DirectoryIterator($imgespath) as $file) {
					
				if($file->isDot()) continue;
				
				$Date = date('m/d/Y h:i:s a', $file->getATime());
				$FileName = $file->getFileName();
				$BaseName = $file->getBasename('.jpg');
				$SeriesIndex = strrpos($BaseName, '-') ? strrpos($BaseName, '-') : strlen($BaseName);
				$SKU = substr($BaseName, 0, $SeriesIndex);
		
				$Image = new Imagen();
				$Image->name = $FileName;
				$Image->sku = $SKU;
				$Image->date = $Date;
		
				array_push($data->Data, $Image);
			}
		}

		return ($data);
	}	

	add_action(
		'rest_api_init',
		function () {
						
			register_rest_route(
				'poeticsoft', 
				'woo-images-read', 
				array(

					'methods'  => 'GET',
					'callback' => 'poeticsoft_api_woo_images_read_endpoint'
				)
			);
		}
	);	 