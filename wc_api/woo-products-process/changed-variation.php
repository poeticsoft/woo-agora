
<?php

function poeticsoft_api_woo_products_process_changed_variation ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';

	$Errors = '';

	foreach($Products as $Product) {

		try {

			$VariationId = wc_get_product_id_by_sku($Product['sku']);
			if(!$VariationId) {

				$Status->Code = 'KO';
				$Errors .= 'Variation not exist';			
				continue;
			}	

			$CPV = wc_get_product($VariationId);

			/* CHANGES */

			$CPV->set_regular_price($Product['regular_price']);
			$CPV->set_description($Product['name']);

			$NewQuantity = wc_update_product_stock($CPV, intval($Product['stock_quantity']));

			/* Images */

			if($Product['image_id']) {

				$CPV->set_image_id($Product['image_id']);
			}

			/* Attributes */

			$ProductAttributes = array();

			foreach($Product['attributes'] as $name => $value) { 

				$ProductAttributes['pa_' . $name] = wc_sanitize_taxonomy_name(stripslashes($value));
			}

			$CPV->set_attributes($ProductAttributes);
			
			/* Save */

			$CPV->save();
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 