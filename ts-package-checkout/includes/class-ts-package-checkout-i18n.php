<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://techosolution.com
 * @since      1.0.0
 *
 * @package    Ts_Package_Checkout
 * @subpackage Ts_Package_Checkout/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ts_Package_Checkout
 * @subpackage Ts_Package_Checkout/includes
 * @author     Hafiz Hamza Javed <hafizhamza810@gmail.com>
 */
class Ts_Package_Checkout_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ts-package-checkout',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
