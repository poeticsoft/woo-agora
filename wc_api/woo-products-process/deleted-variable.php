
<?php

function poeticsoft_api_woo_products_process_deleted_variable ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';
	$Status->Message = 'Variable products deleted';

	return $Status;

	
} 