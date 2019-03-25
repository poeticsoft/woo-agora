<?php

	/**
	*
	* Plugin Name: poeticsoft-woo-agora 
	* Plugin URI: http://www.poeticsoft.com/plugins/woo-agora
	* Description: WordPress Woocommerce Agora communication by Poeticsoft
	* Version: 0.00
	* Author: Alberto Moral
	* Author URI: http://www.poeticsoft.com/albertomoral
	*
	**/	

	/* DEBUG
 */

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	

	/**
	 * ROUTES
	*/
	
	require_once(dirname(__FILE__) . '/wc_api/woo-products-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-process.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-remove-all.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-parent-sku-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-color-size-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-color-size-update.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-categories-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-families-categories-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-families-categories-update.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-agora-excel-data-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-agora-excel-data-update.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-images-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-images-upload.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-images-clean.php');
	require_once(dirname(__FILE__) . '/wc_api/send-mail.php');
	require_once(dirname(__FILE__) . '/wc_api/get-max-upload-size.php');
	require_once(dirname(__FILE__) . '/wc_api/get-agora-fields-footprint.php');

	/* **************************************************************************** */
	/* Poetic Utils Menu*/

	function poeticsoft_utils_menu() {
		
		global $poeticsoft_utils_menu_page;

		$poeticsoft_utils_menu_page = add_management_page(
			'Poetic Utils', 
			'Poetic Utils', 
			'manage_options', 
			'poeticsoft_utils', 
			'poeticsoft_utils_options'
		);
	} 

	function poeticsoft_utils_options() {

		if ( !current_user_can('manage_options') )  {

			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		echo '<poeticsoft-utils></poeticsoft-utils>';
	}

	add_action( 'admin_menu', 'poeticsoft_utils_menu' );

	function poeticsoft_utils_scripts($hook) {
		
		global $poeticsoft_utils_menu_page;
	 
		if( $hook != $poeticsoft_utils_menu_page ) 
			return;

		wp_enqueue_style( 'poeticsoft_utils_css', plugin_dir_url(__FILE__) . '/utils.css', array(), '20181203', 'all' ); 
		wp_enqueue_script( 'poeticsoft_utils_js', plugin_dir_url( __FILE__ ) . '/utils.js', array(), '20181203', true ); 
	}

	add_action('admin_enqueue_scripts', 'poeticsoft_utils_scripts');
?>