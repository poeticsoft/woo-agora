
<?php

function poeticsoft_api_woo_products_process_new_variation ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';

	$Errors = '';

	foreach($Products as $Product) {

		$VariationsQuery = array(
			'post_type'  => 'product_variation',
			'meta_query' => array(
				array(
						'key'   => '_sku',
						'value' => $Product['sku'],
				)
			)
		);
		$posts = get_posts($args);
		
		if(count($posts) > 0) {

			$Status->Code = 'KO';
			$Errors .= ' variation Exist ' . json_encode($posts);				
			continue;
		}

		try {

			// $ParentId = wc_get_product_id_by_sku($Product['parent_sku']);

			if(!$ParentId) {

				$Errors .= ' parent not exist Exist';
				
				continue;
			}

			$NPV = new WC_Product_Variation();
	
			$NPV->set_parent_id($ParentId);
			$NPV->set_regular_price($Product['regular_price']);
			$NPV->set_sku($Product['sku']); 
			$NPV->set_manage_stock(true);
			$NPV->set_stock_quantity($Product['stock_quantity']);

			/* Images */

			if($Product['image_id']) {

				$NPV->set_image_id($Product['image_id']);
			}

			/* Attributes */

			$ProductAttributes = array();

			foreach($Product['attributes'] as $name => $value) { 

				$ProductAttributes['pa_' . $name] = $value;
			}

			$NPV->set_attributes($ProductAttributes);

			// Save

			$ID = $NPV->save();

			$Status->Code = 'KO';
			// $Errors = 'jarl'; //json_encode($ProductAttributes);
			
		} catch (Exception $e) {

			$Status->Code = 'KO';
			$Errors = $Errors . ' - ' . $e->getMessage();
		}
	}

	$Status->Reason = $Errors;

	return $Status;	
} 