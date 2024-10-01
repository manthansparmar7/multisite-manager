<?php

/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0
 * @package           Multisite_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Multisite Manager
 * Description:       Multisite Manager gives Super Admins centralised control to manage subsites with data comparison, global updates, user management, settings synchronisation, reports, analytics, and maintenance across the network.
 * Version:           1.0
 * Author:            Manthan Parmar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       multisite-manager
 * Domain Path:       /languages
 * Network: true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MULTISITE_MANAGER_VERSION', '1.0.0' );

define( 'MULTISITE_MANAGEMENT_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_multisite_manager() {
	// Check if WordPress is running as a multisite network
	if ( ! is_multisite() ) {
		// Display an admin notice to inform the user
		add_action('admin_notices', function() {
			echo '<div class="notice notice-error"><p>' . 
				__('Multisite Manager can only be activated on a WordPress Multisite network.', 'multisite-manager') . 
				'</p></div>';
		});

		// Throw an error to stop activation
		wp_die(__('Multisite Manager can only be activated on a WordPress Multisite network.', 'multisite-manager'));
	}

	require_once MULTISITE_MANAGEMENT_DIR_PATH . 'includes/class-multisite-manager-activator.php';
	Multisite_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_multisite_manager() {
	require_once MULTISITE_MANAGEMENT_DIR_PATH . 'includes/class-multisite-manager-deactivator.php';
	Multisite_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_multisite_manager' );
register_deactivation_hook( __FILE__, 'deactivate_multisite_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MULTISITE_MANAGEMENT_DIR_PATH . 'includes/class-multisite-manager.php';

/* file that contains common functions used in the plugin */
require MULTISITE_MANAGEMENT_DIR_PATH . 'includes/multisite-manager-functions.php';

/**
 * Begins execution of the plugin.
 */
function run_multisite_manager() {
	$plugin = new Multisite_Manager();
	$plugin->run();
}

// Run the plugin
run_multisite_manager();