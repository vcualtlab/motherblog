<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://luetkemj.github.io
 * @since      1.0.0
 *
 * @package    Altlab_Motherblog
 * @subpackage Altlab_Motherblog/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Altlab_Motherblog
 * @subpackage Altlab_Motherblog/admin
 * @author     Mark Luetke <luetkemj@gmail.com>
 */
class Altlab_Motherblog_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Altlab_Motherblog_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Altlab_Motherblog_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/altlab-motherblog-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Altlab_Motherblog_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Altlab_Motherblog_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/altlab-motherblog-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function shortcodes(){
	// 	// [bartag foo="foo-value"]
		function bartag_func( $atts ) {
		    $a = shortcode_atts( array(
		        'syncat' => 'uncategorized'
		    ), $atts );

		    $output = "
		    <form action='/login' method='post'>
			
				<fieldset id='fs1'>
					<div id='have-rampages'>
						<label>Do you have a Rampages blog?</label><br/>
						<input type='radio' name='have-rampages' value='Yes'>Yes</input>
						<input type='radio' name='have-rampages' value='No'>No</input>
					</div>

					<div id='have-rampages-yes' style='display:none;'>
						<p>
							<label>Which of your blogs would you like to subscribe?</label><br/>
							<select id='blog-select' name='blog-select'>
						    	<option value='my-blog'>My Blog</option>
							</select>
						</p>

						<p>Awesome! But you'll have to login before we can take the next step.</p>
					</div>
				</fieldset>
				
				<fieldset id='fs2'>
					<div id='want-rampages' style='display:none;'>
						<p>
							<label>Do you want one?</label>
							<input type='radio' name='want-rampages' value='Yes'>Yes</input>
							<input type='radio' name='want-rampages' value='No'>No</input>
						</p>
					</div>

					<div id='want-rampages-yes' style='display:none;'>
						<p>Awesome! You can <a href='http://rampages.us/vcu-wp-signup.php'>get one here.</a></p>
					</div>
				</fieldset>

				<fieldset id='fs3'>
					<div id='have-blog' style='display:none;'>
						<p>Do you have a blog somewhere else already?</p>
						<input type='radio' name='have-blog' value='Yes'>Yes</input>
						<input type='radio' name='have-blog' value='No'>No</input>
					</div>

					<div id='have-blog-yes' style='display:none;'>
						<p>Cool, we just need the RSS feed from your site for the category you will use for this site.</p>
						<input type='text'></input>
						<small>If you aren't sure how to find your RSS feed, check out <a href='http://thoughtvectors.net/rss-stream/i-have-a-blog/specific/'>this page for common feed structures</a>.</small>
					</div>
					

					<div id='have-blog-no' style='display:none;'>
						<p>Sorry, but you have to have a blog somewhere to register.</p>

						<p>Fear not, you can <a href='http://rampages.us/vcu-wp-signup.php'>get a Rampages blog</a> for free! Just come back when you're ready. 😊</p>
					</div>
				</fieldset>

			</form>
		    ";

		    return $output;
		}
		add_shortcode( 'bartag', 'bartag_func' );
	}

}
