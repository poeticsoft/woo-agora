<?php

	// https://stackoverflow.com/questions/11503646/woocommerce-create-product-by-code
	// https://stackoverflow.com/questions/47518333/create-programmatically-a-variable-product-and-two-new-attributes-in-woocommerce
	// https://docs.woocommerce.com/wc-apidocs/class-WC_Product.html
	// https://businessbloomer.com/woocommerce-easily-get-product-info-title-sku-desc-product-object/
	// https://www.sbloggers.com/add-a-woocommerce-product-using-custom-php-code-programmatically
	// https://stackoverflow.com/questions/47518333/create-programmatically-a-variable-product-and-two-new-attributes-in-woocommerce/47844054#47844054

		
	require_once(dirname(__FILE__) . '/woo-products-process/new-simple.php');
	require_once(dirname(__FILE__) . '/woo-products-process/new-variable.php');
	require_once(dirname(__FILE__) . '/woo-products-process/new-variation.php');

	require_once(dirname(__FILE__) . '/woo-products-process/deleted-variation.php');
	require_once(dirname(__FILE__) . '/woo-products-process/deleted-variable.php');
	require_once(dirname(__FILE__) . '/woo-products-process/deleted-simple.php');

	require_once(dirname(__FILE__) . '/woo-products-process/changed-simple.php');
	require_once(dirname(__FILE__) . '/woo-products-process/changed-variable.php');
	require_once(dirname(__FILE__) . '/woo-products-process/changed-variation.php');

	function poeticsoft_api_woo_products_process_endpoint ( $request_data ){

		$data = new stdClass();	
		$data->Status = new stdClass();
		$data->Status->Code = 'OK';	
		$data->Status->Reason = '';

		$ProcessData = $request_data->get_params();
		$ProcessMode = $ProcessData['mode'];
		$Code = explode('_', $ProcessMode);
		$Status = $Code[0];
		$Type = $Code[1];
		$ProcessChunk = $ProcessData['chunk'];
		$ProcessChunkCount = $ProcessData['count'];
		$Products = $ProcessData['products'];		
		$Action = 'poeticsoft_api_woo_products_process_' . $ProcessMode;		
		$Errors = '';

		$data->Status->Message = 'Processed block ' . 
															$ProcessChunk  .
														 '/' .
														  $ProcessChunkCount .
														 ' with ' .
														  sizeof($Products) . 
														 ' ' . 
														  $Status . 
														 ' ' . 
														  $Type . 
														 ' products...';
		try {

			$ProductDataJSON =  json_encode($ProcessData['products'], JSON_PRETTY_PRINT);
			$Wrote = file_put_contents(
				__DIR__ . '/data/process-parts/' . $ProcessMode . '_' . $ProcessChunk . '.json',
				$ProductDataJSON . ''
			);

			if(!$Wrote) {

				$data->Status->Code = 'KO';					
				$Errors = $Errors . 'Unknow error writing data';
			}

			$ActionResult = $Action($ProcessData['products']);
			if($ActionResult->Code == 'KO') {

				$data->Status->Code = 'KO';
				$Errors = $Errors . $ActionResult->Reason;
			}

			$data->Status->Reason = $Errors;

		} catch (Exception $e) {

			$data->Status->Code = 'KO';	
			$data->Status->Reason = $Errors . $e->getMessage();
		}

		return ($data);
	} 
 
	add_action( 'rest_api_init', function () {
            
		register_rest_route(
			'poeticsoft', 
			'woo-products-process', 
			array(
				'methods'  => 'POST',
				'callback' => 'poeticsoft_api_woo_products_process_endpoint'
			)
		);
	});	