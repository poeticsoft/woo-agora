
<?php

	function poeticsoft_api_woo_products_categories_read_endpoint ($params){
		
		$Allowed_Categorie_Fields = [
			'term_id',
			'name'
		];
		
		$cat_args = array(
				'taxonomy' 	 => 'product_cat',
				'hide_empty' => false
		);

		$ProductCategories = get_terms($cat_args);

		$data = new stdClass();	
		$data->Data = [];
		$data->Status = new stdClass();	
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';
		$data->Status->Message = 'Web categories readed';
		
		foreach ($ProductCategories as $ProductCategorie) {

			array_push(
				$data->Data,
				array(
					'id' => $ProductCategorie->term_id,
					'parentId' => $ProductCategorie->parent,
					'name' => $ProductCategorie->name
				)
			);
		};

		return ($data);
	}	
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-categories-read', 
			array(

				'methods'  => 'GET',
				'callback' => 'poeticsoft_api_woo_products_categories_read_endpoint'
			)
		);
	});	