
<?php 

	function poeticsoft_api_woo_thumbnail_external_replace( $html, $post_id ){		

		return '<img src="/data/image/test.jpg" />';
	}

	add_filter( 'post_thumbnail_html', 'poeticsoft_api_woo_thumbnail_external_replace', 10, PHP_INT_MAX );
