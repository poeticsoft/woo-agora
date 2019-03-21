
<?php

function poeticsoft_api_woo_products_process_deleted_simple ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';
	$Status->Message = 'Simple products deleted';

	return $Status;

	
} 