<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://adamwills.io
 * @since             1.0.0
 * @package           Know_Before_You_Throw
 *
 * @wordpress-plugin
 * Plugin Name:       Know Before You Throw
 * Plugin URI:        https://norfolkcounty.ca
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Adam Wills
 * Author URI:        https://adamwills.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       know-before-you-throw
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-know-before-you-throw-activator.php
 */
function activate_know_before_you_throw() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-know-before-you-throw-activator.php';
	Know_Before_You_Throw_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-know-before-you-throw-deactivator.php
 */
function deactivate_know_before_you_throw() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-know-before-you-throw-deactivator.php';
	Know_Before_You_Throw_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_know_before_you_throw' );
register_deactivation_hook( __FILE__, 'deactivate_know_before_you_throw' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-know-before-you-throw.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_know_before_you_throw() {

	$plugin = new Know_Before_You_Throw();
	$plugin->run();

}
run_know_before_you_throw();
