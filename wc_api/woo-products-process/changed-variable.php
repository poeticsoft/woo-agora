
<?php

function poeticsoft_api_woo_products_process_changed_variable ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';
	$Status->Message = 'Variable products changed';

	return $Status;

	
} 