<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://luetkemj.github.io
 * @since             1.0.0
 * @package           Altlab_Motherblog
 *
 * @wordpress-plugin
 * Plugin Name:       ALT Lab MotherBlog
 * Plugin URI:        https://github.com/vcualtlab/motherblog
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Mark Luetke
 * Author URI:        http://luetkemj.github.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       altlab-motherblog
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-altlab-motherblog-activator.php
 */
function activate_altlab_motherblog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-altlab-motherblog-activator.php';
	Altlab_Motherblog_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-altlab-motherblog-deactivator.php
 */
function deactivate_altlab_motherblog() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-altlab-motherblog-deactivator.php';
	Altlab_Motherblog_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_altlab_motherblog' );
register_deactivation_hook( __FILE__, 'deactivate_altlab_motherblog' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-altlab-motherblog.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_altlab_motherblog() {

	$plugin = new Altlab_Motherblog();
	$plugin->run();

}
run_altlab_motherblog();
