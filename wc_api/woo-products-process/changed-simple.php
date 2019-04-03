
<?php

	function poeticsoft_api_woo_products_process_changed_simple ($Products){
		
		$Status = new stdClass();
		$Status->Code = 'OK';
	
		$Errors = '';
	
		foreach($Products as $Product) {
	
			$ID = wc_get_product_id_by_sku($Product['sku']);
			if(!$ID) {

				$Status->Code = 'KO';
				$Errors .= 'Product not exist';			
				continue;
			}	
	
			try {

				$CSP = wc_get_product($ID);
	
				$CSP->set_name($Product['name']);
				$CSP->set_regular_price($Product['regular_price']); 
				$CSP->set_category_ids($Product['category_ids']);

				$NewQuantity = wc_update_product_stock($CPV, intval($Product['stock_quantity']));
	
				/* Images */
	
				if($Product['image_id']) {
	
					$CSP->set_image_id($Product['image_id']);
				}
	
				if(count($Product['gallery_image_ids'] > 0)) {
	
					$CSP->set_gallery_image_ids($Product['gallery_image_ids']);
				}

				/* Attributes */

				$ProductAttributes = array();
				$Position = 0;

				foreach($Product['attributes'] as $name => $value) { 

					$taxonomy = 'pa_' . $name;
					if(!term_exists($value, $taxonomy)) {

						wp_insert_term($value, $taxonomy);
					}

					$ProductAttributes[$taxonomy] = array(
						'name' => 'pa_' . $name,
						'value' => $value,
						'position' => $Position,
						'is_visible' => true,
						'is_variation' => false,
						'is_taxonomy' => false
					);

					$Position++;
				}

				update_post_meta($ID,'_product_attributes', $ProductAttributes);

				// Save
	
				$CSP->save();	
				
			} catch (Exception $e) {
	
				$Status->Code = 'KO';
				$Errors = $Errors . ' - ' . $e->getMessage();
			}
		}
	
		$Status->Reason = $Errors;
	
		return $Status;	
	} 