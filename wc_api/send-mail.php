
<?php

	function poeticsoft_send_mail_handler() {

		$From = 'alberto.moral@kaldeera.com';		
		$headers = 'From: ' . 'alberto.moral@kaldeera.com';

		return mail(
			'alberto.moral@poeticsoft.com', 
			'[CRON TEST]', 
			'Message',
			 $headers, 
			 '-f ' . $From
		);
	}

	add_action('poeticsoft_send_mail', 'poeticsoft_send_mail_handler');