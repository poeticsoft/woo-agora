
<?php

function poeticsoft_api_woo_products_process_changed_variable ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';

	$Errors = '';

	foreach($Products as $Product) {

		try {

			$ID = wc_get_product_id_by_sku($Product['sku']);
			if(!$ID) {

				$Status->Code = 'KO';
				$Errors .= 'Product not exist';			
				continue;
			}	

			$CVP = wc_get_product($ID);

			/* CHANGES */

			$CVP->set_name($Product['name']);
			$CVP->set_category_ids($Product['category_ids']);

			/* Images */

			if($Product['image_id']) {

				$CVP->set_image_id($Product['image_id']);
			}

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
			
			/* Save */

			$CVP->save();
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 