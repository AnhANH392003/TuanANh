<?php 
/**
 * EmallShop Extras Functions
 *
 * @package PressLayouts
 * @subpackage EmallShop
 * @since EmallShop 1.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* 	Check WooCommerce is activated
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/* 	Check Dokan is activated
/* --------------------------------------------------------------------- */
if ( ! function_exists( 'is_dokan_activated' ) ) {
	function is_dokan_activated() {
		return class_exists( 'WeDevs_Dokan' ) ? true : false;
	}
}

/*Check Visual Composer is activated
/* --------------------------------------------------------------------- */
if( ! function_exists( 'is_vc_activated' ) ) {
	function is_vc_activated() {
		return class_exists( 'WPBakeryVisualComposerAbstract' ) ? true : false;
	}
}

if ( ! function_exists( 'emallshop_get_option' ) ) {
	function emallshop_get_option($name, $default = '') {
		global $emallshop_options;
		if ( isset($emallshop_options[$name]) ) {
			return $emallshop_options[$name];
		}
		return $default;
	}
}