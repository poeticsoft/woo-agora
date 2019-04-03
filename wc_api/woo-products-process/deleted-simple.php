
<?php

function poeticsoft_api_woo_products_process_deleted_simple ($Products){
		
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
			$DSP->delete();	
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 