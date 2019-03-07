<?php

/**
 * Fired during plugin activation
 *
 * @link       http://luetkemj.github.io
 * @since      1.0.0
 *
 * @package    Altlab_Motherblog
 * @subpackage Altlab_Motherblog/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Altlab_Motherblog
 * @subpackage Altlab_Motherblog/includes
 * @author     Mark Luetke <luetkemj@gmail.com>
 */
class Altlab_Motherblog_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'feedwordpress_automatic_updates', 'init', true );//set FWP settings to run prior to page load if you want after page 'shutdown' rather than 'init'
	}

}
