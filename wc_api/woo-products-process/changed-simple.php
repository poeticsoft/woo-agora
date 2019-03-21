
<?php

	function poeticsoft_api_woo_products_process_changed_simple ($Products){

		$Status = new stdClass();
		$Status->Code = 'OK';
		$Status->Reason = '';
		$Status->Message = 'Simple products changed';
	
		return $Status;

		
	} 