
<?php

require_once(dirname(__FILE__) . '/pepare-product-attributes.php');

function poeticsoft_api_woo_products_process_new_simple ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';

	$Errors = '';

	foreach($Products as $Product) {

		$ExistentID = wc_get_product_id_by_sku($Product['sku']);

		if($ExistentID) continue;

		$Errors = '|' . $Errors . json_encode(wp_get_object_terms($ExistentID)) . '|';

		try {

			$NSP = new WC_Product();

			$NSP->set_name($Product['name']);
			$NSP->set_status("publish");
			$NSP->set_catalog_visibility('visible');
			$NSP->set_sku($Product['sku']);
			$NSP->set_regular_price($Product['regular_price']); 
			$NSP->set_manage_stock(true);
			$NSP->set_category_ids($Product['category_ids']);
			$NSP->set_stock_quantity($Product['stock_quantity']);

			/* Images */

			if($Product['image_id']) {

				$NSP->set_image_id($Product['image_id']);
			}

			if(count($Product['gallery_image_ids'] > 0)) {

				$NSP->set_gallery_image_ids($Product['gallery_image_ids']);
			}

			/* Attributes */
			// https://stackoverflow.com/questions/52937409/create-programmatically-a-product-using-crud-methods-in-woocommerce-3/52941994#52941994			

			$FormattedAttributes = array();
			foreach($Product['attributes'] as $Name => $Value) {

				$FormattedAttributes[$Name] = array(
					'term_names' => array($Value),
					'for_variation' => false
				);
			}
			$PreparedAttributes = wc_prepare_product_attributes($FormattedAttributes);

			$NSP->set_attributes($PreparedAttributes);

			// Save

			$product_id = $NSP->save();
			$Status->Code = 'KO';
			$Errors = $Errors . json_encode($PreparedAttributes);

		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 