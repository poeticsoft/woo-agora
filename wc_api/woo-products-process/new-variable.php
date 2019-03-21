
<?php

function poeticsoft_api_woo_products_process_new_variable ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';

	$count = 0;

	foreach($Products as $Product) {

		$ExistentID = wc_get_product_id_by_sku($Product['sku']);

		if($ExistentID) continue;

		$NVP = new WC_Product_Variable();

		$NVP->set_name($Product['name']);
		$NVP->set_status("publish");
		$NVP->set_catalog_visibility('visible');
		$NVP->set_sku($Product['sku']);
		$NVP->set_regular_price(100); 
		$NVP->set_manage_stock(true);
		$NVP->set_category_ids($Product['category_ids']);

		$product_id = $NVP->save();

		$count++;
	}

	$Status->Message = $count . ' new variable product created';

	return $Status;	
} 