<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://adamwills.io
 * @since      1.0.0
 *
 * @package    Know_Before_You_Throw
 * @subpackage Know_Before_You_Throw/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Know_Before_You_Throw
 * @subpackage Know_Before_You_Throw/includes
 * @author     Adam Wills <adam@adamwills.com>
 */
class Know_Before_You_Throw_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'know-before-you-throw',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
