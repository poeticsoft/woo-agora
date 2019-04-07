
<?php

function poeticsoft_api_woo_products_process_new_variation ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';

	$Errors = '';

	foreach($Products as $Product) {
		
		$ParentId = wc_get_product_id_by_sku($Product['parent_sku']);
		if(!$ParentId) {

			$Status->Code = 'KO';
			$Errors .= 'Parent does not exist';			
			continue;
		}

		try {

			$VariationId = wc_get_product_id_by_sku($Product['sku']);
			if($VariationId) {

				wc_get_product($VariationId)->delete();
			}	
			
			$StockQuantity = $Product['export_stock_quantity'] ? $Product['export_stock_quantity'] : 0;

			$NPV = new WC_Product_Variation();
			$NPV->set_parent_id($ParentId);
			$NPV->set_sku($Product['sku']); 
			$NPV->set_regular_price($Product['regular_price']);
			$NPV->set_manage_stock(true);
			$NPV->set_stock_quantity($StockQuantity);
			$NPV->set_description($Product['name']);

			/* Images */

			if($Product['image_id']) {

				$NPV->set_image_id($Product['image_id']);
			}

			/* Attributes */

			$ProductAttributes = array();

			foreach($Product['attributes'] as $name => $value) { 

				$ProductAttributes['pa_' . $name] = wc_sanitize_taxonomy_name(stripslashes($value));
			}

			$NPV->set_attributes($ProductAttributes);
			
			/* Save */

			$ID = $NPV->save();
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 