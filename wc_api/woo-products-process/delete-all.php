
<?php

	function poeticsoft_api_woo_products_process_delete_all ($Products){
		
		$args = array(
			'numberposts' => -1,
			'status' => array(
				'publish',
				'trashed'
			)
		); 

		$WooProducts = wc_get_products($args);

		$Response = new stdClass();
		$Response->Code = 'OK';	
		$Response->Reason = '';

		$ProductsDeleted = 0;

		foreach ($WooProducts as $WooProduct) {

			if($ProductType == 'variable') {
				
				$ProductVariations = $WooProduct->get_available_variations();

				foreach ($ProductVariations as $ProductVariation) {
					
					$ProductVariation->delete();

					$ProductsDeleted++;	
				}	
			}

			$WooProduct->delete();

			$ProductsDeleted++;	
		}
		
		$Response->Message = $ProductsDeleted . ' products deleted';

		return ($Response);
	}	