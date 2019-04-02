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
	require_once(dirname(__FILE__) . '/wc_api/woo-products-raw-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-process.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-remove-all.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-parent-sku-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-color-size-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-color-size-update.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-categories-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-stock-read.php');
	require_once(dirname(__FILE__) . '/wc_api/woo-products-stock-update.php');
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

	function poeticsoft_woo_agora_menu() {
		
		global $poeticsoft_woo_agora_menu_page;

		$poeticsoft_woo_agora_menu_page = add_management_page(
			'WooAgora', 
			'WooAgora', 
			'manage_options', 
			'poeticsoft_woo_agora', 
			'poeticsoft_woo_agora_options'
		);
	} 

	function poeticsoft_woo_agora_options() {

		if ( !current_user_can('manage_options') )  {

			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		echo '<poeticsoft-woo-agora-main></poeticsoft-woo-agora-main>';
	}

	add_action( 'admin_menu', 'poeticsoft_woo_agora_menu' );

	function poeticsoft_woo_agora_scripts($hook) {
		
		global $poeticsoft_woo_agora_menu_page;
	 
		if( $hook != $poeticsoft_woo_agora_menu_page ) 
			return;

		wp_enqueue_style( 'poeticsoft_woo_agora_css', plugin_dir_url(__FILE__) . '/main.css', array(), '20181203', 'all' ); 
		wp_enqueue_script( 'poeticsoft_woo_agora_js', plugin_dir_url( __FILE__ ) . '/main.js', array(), '20181203', true ); 
	}

	add_action('admin_enqueue_scripts', 'poeticsoft_woo_agora_scripts');
?>