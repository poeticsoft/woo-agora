
<?php

	function poeticsoft_api_woo_products_process_changed_simple ($Products){
		
		$Status = new stdClass();
		$Status->Code = 'OK';
	
		$Errors = '';
	
		foreach($Products as $Product) {
	
			$ProductID = wc_get_product_id_by_sku($Product['sku']);
	
			if(!$ProductID) continue;
	
			try {

				$SP = wc_get_product($ProductID);
	
				$SP->set_name($Product['name']);
				$SP->set_status("publish");
				$SP->set_catalog_visibility('visible');
				$SP->set_sku($Product['sku']);
				$SP->set_regular_price($Product['regular_price']); 
				$SP->set_manage_stock(true);
				$SP->set_category_ids($Product['category_ids']);
				$SP->set_stock_quantity($Product['stock_quantity']);
	
				/* Images */
	
				if($Product['image_id']) {
	
					$SP->set_image_id($Product['image_id']);
				}
	
				if(count($Product['gallery_image_ids'] > 0)) {
	
					$SP->set_gallery_image_ids($Product['gallery_image_ids']);
				}
	
				/* Attributes */
	
				$ProductAttributes = array();
				foreach($Product['attributes'] as $Name => $Value){
	
					$ProductAttributes['attribute_' . $Name] = $Value;
				}
	
				$SP->set_attributes($ProductAttributes);
	
				// Save
	
				$product_id = $SP->save();
				
			} catch (Exception $e) {
	
				$Status->Code = 'KO';
				$Errors = $Errors . ' - ' . $e->getMessage();
			}
		}
	
		$Status->Reason = $Errors;
	
		return $Status;	
	} 