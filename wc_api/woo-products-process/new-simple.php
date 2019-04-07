
<?php

function poeticsoft_api_woo_products_process_new_simple ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';

	$Errors = '';

	foreach($Products as $Product) {

		$ExistentID = wc_get_product_id_by_sku($Product['sku']);

		if($ExistentID) {

			$Status->Code = 'KO';
			$Errors .= ' Id Exist';			
			continue;
		}

		try {

			$NSP = new WC_Product();
			
			$StockQuantity = $Product['export_stock_quantity'] ? $Product['export_stock_quantity'] : 0;

			$NSP->set_name($Product['name']);
			$NSP->set_status("publish");
			$NSP->set_catalog_visibility('visible');
			$NSP->set_sku($Product['sku']);
			$NSP->set_regular_price($Product['regular_price']); 
			$NSP->set_manage_stock(true);
			$NSP->set_category_ids($Product['category_ids']);
			$NSP->set_stock_quantity($StockQuantity);

			/* Images */

			if($Product['image_id']) {

				$NSP->set_image_id($Product['image_id']);
			}

			if(count($Product['gallery_image_ids'] > 0)) {

				$NSP->set_gallery_image_ids($Product['gallery_image_ids']);
			}

			/* Save */

			$ID = $NSP->save();

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

		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 