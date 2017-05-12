<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.connectingelement.co.uk
 * @since             1.0.0
 * @package           CE-CSJS
 *
 * @wordpress-plugin
 * Plugin Name:       CE CSJS
 * Plugin URI:        https://github.com/ConnectingElement/wp-csjs
 * Description:       Connecting Element Central Signup JSON Service integration for Wordpress
 * Version:           1.0.5
 * Author:            Connecting Element
 * Author URI:        http://www.connectingelement.co.uk
 * Text Domain:       ce-csjs
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/ce-csjs-activator.php
 */
function activate_ce_csjs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/ce-csjs-activator.php';
	CE_CSJS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/ce-csjs-deactivator.php
 */
function deactivate_ce_csjs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/ce-csjs-deactivator.php';
	CE_CSJS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ce_csjs' );
register_deactivation_hook( __FILE__, 'deactivate_ce_csjs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/ce-csjs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ce_csjs() {

	$plugin = new CE_CSJS();
	$plugin->run();

}
run_ce_csjs();

/*
	Plugin Name: Smashing Plugin
	Description: This is for updating your Wordpress plugin.
	Version: 1.0.0
	Author: Matthew Ray
	Author URI: http://www.matthewray.com
*/
if (!class_exists('Smashing_Updater')){
	include_once( plugin_dir_path( __FILE__ ) . '/classes/SmashingUpdater.php');
}
$updater = new Smashing_Updater(__FILE__);
$updater->set_username('ConnectingElement');
$updater->set_repository('wp-csjs');
//$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
$updater->initialize();