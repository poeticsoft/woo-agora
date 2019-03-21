
<?php

function poeticsoft_api_woo_products_process_deleted_variation ($Products){

	$Status = new stdClass();
	$Status->Code = 'OK';
	$Status->Reason = '';
	$Status->Message = 'Variations deleted';

	return $Status;

	
} 