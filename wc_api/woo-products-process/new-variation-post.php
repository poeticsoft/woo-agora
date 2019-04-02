
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

		$Status->Code = 'KO';
		$Errors .= count($posts);
	}

	$Status->Reason = $Errors;

	return $Status;	
} 