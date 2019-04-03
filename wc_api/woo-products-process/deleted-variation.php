
<?php

function poeticsoft_api_woo_products_process_deleted_variation ($Products){
		
	$Status = new stdClass();
	$Status->Code = 'OK';

	$Errors = '';

	foreach($Products as $Product) {

		$VariationId = wc_get_product_id_by_sku($Product['sku']);
		if(!$VariationId) {

			$Status->Code = 'KO';
			$Errors .= 'Variation not exist';			
			continue;
		}	

		$CVP = wc_get_product($VariationId);

		try {

			$Deleted = wp_delete_post($VariationId);

			$DSP->delete();	
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
	
} 