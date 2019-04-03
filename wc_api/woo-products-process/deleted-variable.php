
<?php

function poeticsoft_api_woo_products_process_deleted_variable ($Products){
		
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

			$DSP = wc_get_product($ID);				
			$ProductVariations = $DSP->get_available_variations();

			foreach ($ProductVariations as $ProductVariation) {

				$VariationId = $ProductVariation['variation_id'];				
				$Deleted = wp_delete_post($VariationId);
			}	

			$DSP->delete();	
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 