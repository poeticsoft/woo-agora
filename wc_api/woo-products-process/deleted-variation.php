
<?php

function poeticsoft_api_woo_products_process_deleted_variation ($Products){

	$Status = new stdClass();
	$Status->Code = 'KO';

	$Errors = '';

	foreach($Products as $Product) {

		try {

			$VariationId = wc_get_product_id_by_sku($Product['sku']);
			if(!$VariationId) {

				continue;
			}

			$VariationDeleted = wc_get_product($VariationId)->delete();

			$Errors = json_encode($VariationDeleted);
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;
	
} 