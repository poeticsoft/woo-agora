
<?php

	function poeticsoft_api_woo_products_read_endpoint ($params){

		$Allowed_Product_Fields = [
			'name',
			'sku',
			'sale_price',
			'stock_quantity',
			'attributes',
			'category_ids',
			'image_id',
			'gallery_image_ids',
			'image'
		];
		
		$args = array(
			'status' => 'publish' 
		); 

		function mapVariationGalleryImages($Image) {

			return $Image['image_id'];
		}

		$WooProducts = wc_get_products($args);

		$data = new stdClass();
		$data->Data = [];	
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Web products readed';

		foreach ($WooProducts as $WooProduct) {

			$ProductData = $WooProduct->get_data();
			$ProductType = $WooProduct->get_type();
			$ProductFiltered = array_filter(
				$ProductData,
				function($Field) use ($Allowed_Product_Fields) {

					return in_array($Field, $Allowed_Product_Fields);
				},
				ARRAY_FILTER_USE_KEY
			);
			
			$ProductFiltered['type'] = $ProductType;
			$ProductFiltered['parent_sku'] = null;

			if(
				$ProductType == 'simple' OR
				$ProductType == 'variable'
			) {
				
				$ProductFiltered['attributes']['attribute_color'] = $WooProduct->get_attribute('color');
				$ProductFiltered['attributes']['attribute_size'] = $WooProduct->get_attribute('size');
			}

			array_push(
				$data->Data,
				$ProductFiltered
			);

			if($ProductType == 'variable') {
				
				$ProductVariations = $WooProduct->get_available_variations();

				foreach ($ProductVariations as $ProductVariation) {
					
					$ProductVariationMapped = array(
						'type' => 'variation',
						'name' => $ProductVariation['variation_description'],
						'sku' => $ProductVariation['sku'],
						'parent_sku' => $ProductData['sku'],
						'sale_price' => $ProductVariation['display_regular_price'],
						'stock_quantity' => $ProductVariation['max_qty'],
						'attributes' => $ProductVariation['attributes'],
						'category_ids' => $ProductData['category_ids'],
						'image_id' => $ProductVariation['image_id'],
						'variation_gallery_images' => array_map('mapVariationGalleryImages', $ProductVariation['variation_gallery_images'])
					);

					array_push(
						$data->Data,
						$ProductVariationMapped
					);
				}	
			}
		}

		return ($data);
	}	
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_products_read_endpoint'
			)
		);
	});	