
<?php

function poeticsoft_api_woo_products_process_new_variable ($Products){


	$Status = new stdClass();
	$Status->Code = 'OK';

	$Errors = '';

	foreach($Products as $Product) {

		$ExistentID = wc_get_product_id_by_sku($Product['sku']);

		if($ExistentID) {

			$Status->Code = 'KO';
			$Errors .= ' Id Exist';			
			continue;
		}

		try {

			$NVP = new WC_Product_Variable();

			$NVP->set_name($Product['name']);
			$NVP->set_status("publish");
			$NVP->set_catalog_visibility('visible');
			$NVP->set_sku($Product['sku']);
			$NVP->set_regular_price($Product['regular_price']); 
			$NVP->set_manage_stock(false);
			$NVP->set_category_ids($Product['category_ids']);

			/* Image */

			if($Product['image_id']) {

				$NVP->set_image_id($Product['image_id']);
			}	

			// Save

			$ID = $NVP->save();	

			/* Attributes */

			$ProductAttributes = array();
			$Position = 0;

			foreach($Product['attributes'] as $name => $value) { 

				$taxonomy = 'pa_' . $name;
				$terms = explode('|', $value);

				foreach($terms as $term) {	

					if(!term_exists($value, $term)) {
											
						wp_insert_term($value, $term);
					}				

					wp_set_object_terms($ID, $term, $taxonomy, true);
				}

				$ProductAttributes[$taxonomy] = array(
					'name' => $taxonomy,
					'value' => $terms,
					'position' => $Position,
					'is_visible' => 1,
					'is_variation' => 1,
					'is_taxonomy' => 1
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