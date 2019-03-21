
<?php

function poeticsoft_api_woo_products_process_new_variation ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';

	$count = 0;

	foreach($Products as $Product) {

		$ExistentID = wc_get_product_id_by_sku($Product['sku']);

		if($ExistentID) continue;

		$ParentId = wc_get_product_id_by_sku($Product['parent_sku']);

		$NVP = new WC_Product_Variation();
 
		$NVP->set_parent_id($ParentId);
		$NVP->set_regular_price(100);
		$NVP->set_sku($Product['sku']);

		$NVP->save();

		$count++;
	}

	$Status->Message = $count . ' new variations created';

	return $Status;
} 