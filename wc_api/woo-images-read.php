<?php

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once(dirname(__FILE__) . '/format-size-units.php');	

	class Imagen {
		public $name;
		public $size;
		public $date;
	}

	function poeticsoft_api_woo_images_read_endpoint ($request) {

		$imgespath = WP_CONTENT_DIR . '/uploads/product-images';
		$ImgThumbSize = '300';	
		$ImgThumbDescriptor = '-' . $ImgThumbSize . 'x' . $ImgThumbSize;	

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Images readed'; 
 
		if(is_dir($imgespath)) {

			foreach (new DirectoryIterator($imgespath) as $file) {

				$BaseName = $file->getBasename('.jpg');
					
				if(
					$file->isDot() OR
					!strpos($BaseName, $ImgThumbDescriptor)
				) continue;				

				$BaseNameClean = str_replace($ImgThumbDescriptor, '', $BaseName);
				$FileNameClean = str_replace($ImgThumbDescriptor, '', $file->getFileName());
				
				$AttachmentId = attachment_url_to_postid(
					get_site_url() . 
					'/wp-content/uploads/product-images/' .
					$FileNameClean
				);
				
				$Date = date('m/d/Y h:i:s a', $file->getATime());
				$SeriesIndex = strpos($BaseNameClean, '-');
				$SeriesIndex = $SeriesIndex ? $SeriesIndex : strlen($BaseNameClean);
				$SKU = substr($BaseNameClean, 0, $SeriesIndex);
				$OrderName = $BaseNameClean == $SKU ? $BaseNameClean . '-0' : $BaseNameClean;
		
				$Image = new Imagen();
				$Image->ordername = $OrderName;
				$Image->name = $FileNameClean;
				$Image->sku = $SKU;
				$Image->date = $Date;
				$Image->attid = $AttachmentId;
		
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