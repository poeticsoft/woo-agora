
<?php

	function poeticsoft_api_woo_products_read_endpoint ($params){

		$Allowed_Product_Fields = [
			'name',
			'sku',
			'regular_price',
			'stock_quantity',
			'attributes',
			'category_ids',
			'image_id',
			'gallery_image_ids',
			'image'
		];
		
		$args = array(			
			'numberposts' => -1,
			'status' => 'publish' 
		);

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

			if(
				$ProductType == 'simple'
			) {
				
				$ProductFiltered['attributes'] = array(
					'color' => $WooProduct->get_attribute('pa_color'),
					'size' => $WooProduct->get_attribute('pa_size')
				);
			}

			if(
				$ProductType == 'variable'
			) {
				
				$ProductFiltered['attributes'] = array(
					'color' => implode('|', explode(', ', $WooProduct->get_attribute('pa_color'))),
					'size' => implode('|', explode(', ', $WooProduct->get_attribute('pa_size')))
				);
			}

			array_push(
				$data->Data,
				$ProductFiltered
			);

			if($ProductType == 'variable') {
				
				$ProductVariations = $WooProduct->get_available_variations();

				foreach ($ProductVariations as $ProductVariation) {

					$Name = $ProductVariation['variation_description'];
					$Name = str_replace('<p>', '', $Name);
					$Name = str_replace('</p>', '', $Name);
					$Name = str_replace("\n", '', $Name);
					
					$ProductVariationMapped = array(
						'type' => 'variation',
						'name' => $Name,
						'sku' => $ProductVariation['sku'],
						'parent_sku' => $ProductData['sku'],
						'regular_price' => $ProductVariation['display_regular_price'],
						'stock_quantity' => $ProductVariation['max_qty'] ? $ProductVariation['max_qty'] : 0,
						'category_ids' => $ProductData['category_ids'],
						'image_id' => $ProductVariation['image_id']
					);
					$Attributes = $ProductVariation['attributes'];
					$ProductVariationMapped['attributes'] = array();

					if($Attributes['attribute_pa_color']) {

						$term = get_term_by('slug', $Attributes['attribute_pa_color'], 'pa_color')->name;						
						$ProductVariationMapped['attributes']['color'] = $term;
					}

					if($Attributes['attribute_pa_size']) {

						$term = get_term_by('slug', $Attributes['attribute_pa_size'], 'pa_size')->name;						
						$ProductVariationMapped['attributes']['size'] = $term;
					}

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