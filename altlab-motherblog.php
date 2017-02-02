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
 * Description:       FeedWordPress extension for simple front end syndication. Example shortcode [altlab-motherblog category="some-category" sub_categories = "cat1, cat2, cat3, cat4"]
 * Version:           1.2.0
 * Author:            Mark Luetke
 * Author URI:        http://luetkemj.github.io
 * License:           WTFPL
 * License URI:       http://www.wtfpl.net/
 * Text Domain:       altlab-motherblog
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function motherblog_admin_script() {
           wp_enqueue_script( 'altlab-motherblog', plugin_dir_url( __FILE__ ) . 'admin/js/altlab-motherblog-admin.js', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'motherblog_admin_script' );

//warn if FWP isn't active from this stackoverflow question http://wordpress.stackexchange.com/questions/127818/how-to-make-a-plugin-require-another-plugin
add_action( 'admin_init', 'child_plugin_has_parent_plugin' );
function child_plugin_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'feedwordpress/feedwordpress.php' ) ) {
        add_action( 'admin_notices', 'child_plugin_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function child_plugin_notice(){
    ?><div class="modal" id="modal"> <button class="close-button" id="close-button" onclick="getEm()">Close this warning</button>
  		<div class="modal-guts">
  			<h2>Your Attention Please</h2>
  			<p>The ALT Lab MotherBlog plugin requires the FeedWordPress plugin to be installed and active. 
        I humbly ask you to activate FeedWordPress and then re-activate this plugin.</p>
        <p>FeedWordPress will be highlighted in <span class="fwp-focus">green</span> and after you close this window you'll probably automatically scroll down to it.</p>
  		</div>
  		<!--UGLY I know but it was late and the normal paths were not working-->
  		<script  type="text/javascript" charset="utf-8" >
  		
      var modal = document.querySelector("#modal");
      var modalOverlay = document.querySelector("#modal-overlay");
      var closeButton = document.querySelector("#close-button");
      var openButton = document.querySelector("#open-button");

        closeButton.addEventListener("click", function() {
        modal.classList.toggle("closed");
        modalOverlay.classList.toggle("closed");
        getEm();
        console.log('foo');
      });

        function getEm() {
            var b = document.getElementsByTagName("strong");         
            for (var i in b)
                if (b.hasOwnProperty(i)) {
                    var plugin = (b[i].innerHTML);
                    console.log(plugin);           
                    if (plugin === "FeedWordPress") {
                        b[i].setAttribute("id", "fwp-focus");

                    }
                }                
        }

        jQuery("#close-button").click(function() {
        jQuery('html, body').animate({
            scrollTop: jQuery("#fwp-focus").offset().top-200}, 2000);
        });

  		</script>
  	</div>
  	<?php
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
