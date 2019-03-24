
<?php

	// https://github.com/verot/class.upload.php

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once(dirname(__FILE__) . '/utils/class.upload.php');

	function poeticsoft_api_woo_images_upload_endpoint ($request){

		$imgespath = WP_CONTENT_DIR . '/uploads/product-images/';
		$handle = new upload($_FILES['image']);

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Image uploaded ';

		if ($handle->uploaded) {

			$handle->image_convert = 'jpg';
			$handle->file_new_name_ext = 'jpg';
			$handle->jpeg_quality = 70;
			$handle->image_resize = true;
			$handle->image_ratio_y = true;
			$handle->image_x = 1600;
			$handle->file_overwrite = true;
			
			$handle->process($imgespath);
			if ($handle->processed) { 

				$AttachmentIdExists = attachment_url_to_postid(
					get_site_url() . 
					'/wp-content/uploads/product-images/' .
					$handle->file_dst_name 
				);

				$Attachment = array(
					'guid'           => $handle->file_dst_name, 
					'post_mime_type' => 'image/jpeg',
					'post_title'     => 'IMAGE',
					'post_content'   => '',
					'post_status'    => 'inherit'
					// Here we can add things
					// https://core.trac.wordpress.org/browser/tags/5.1.1/src/wp-admin/includes/media.php
				);

				if($AttachmentIdExists != 0) {

					$Attachment['ID'] = $AttachmentIdExists;

				} else {

					unset($Attachment['ID']);
				}

				$AttachmentId = wp_insert_attachment(
					$Attachment, 
					'product-images/' . $handle->file_dst_name
				);

				$AttachData = wp_generate_attachment_metadata(
					$AttachmentId,
					$handle->file_dst_pathname
				);
	
				wp_update_attachment_metadata(
					$AttachmentId, 
					$AttachData
				);

				$data->Status->Message = 'Image uploaded ' . $handle->log;

			}	else {	

				$data->Status->Code = 'KO';	
				$data->Status->Reason = $handle->error;
			}

			$handle-> clean();

		} else {				

			$data->Status->Code = 'KO';	
			$data->Status->Reason = $handle->error;
		}

		return $data;
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