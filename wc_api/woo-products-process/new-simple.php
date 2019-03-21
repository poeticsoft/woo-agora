
<?php

function poeticsoft_api_woo_products_process_new_simple ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';

	$count = 0;

	foreach($Products as $Product) {

		$ExistentID = wc_get_product_id_by_sku($Product['sku']);

		if($ExistentID) continue;

		$NSP = new WC_Product();

		$NSP->set_name($Product['name']);
		$NSP->set_status("publish");
		$NSP->set_catalog_visibility('visible');
		$NSP->set_sku($Product['sku']);
		$NSP->set_regular_price(100); 
		$NSP->set_manage_stock(true);
		$NSP->set_category_ids($Product['category_ids']);

		$product_id = $NSP->save();

		$count++;
	}

	$Status->Message = $count . ' new simple product created';

	return $Status;	
} 