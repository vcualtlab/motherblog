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
class Altlab_Motherblog_Admin
{
    
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
    public function __construct($plugin_name, $version) {
        
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
        
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/altlab-motherblog-admin.css', array(), $this->version, 'all');
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
        
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/altlab-motherblog-admin.js', array('jquery'), $this->version, false);
    }


public function altlab_motherblog_options(){


	add_action('admin_init', 'altlab_motherblog_init' );
	add_action('admin_menu', 'altlab_motherblog_add_page');

	// Init plugin options to white list our options
	function altlab_motherblog_init(){
		register_setting( 'altlab_motherblog_options', 'altlab_motherblog_options', 'altlab_motherblog_validate' );
	}

	// Add menu page
	function altlab_motherblog_add_page() {
		add_options_page('Ozh\'s Sample Options', 'Sample Options', 'manage_options', 'altlab_motherblog', 'altlab_motherblog_do_page');
	}

	// Draw the menu page itself
	function altlab_motherblog_do_page() {
		?>
		<div class="wrap">
			<h2>Ozh's Sample Options</h2>
			<form method="post" action="options.php">
				<?php settings_fields('altlab_motherblog_options'); ?>
				<?php $options = get_option('altlab_motherblog_options'); ?>
				<table class="form-table">
					<tr valign="top"><th scope="row">Network signup page URL.</th>
						<td><input type="text" name="altlab_motherblog_options[network-signup-url]" value="<?php echo $options['network-signup-url']; ?>" /></td>
					</tr>
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
		<?php	
	}

	// Sanitize and validate input. Accepts an array, return a sanitized array.
	function altlab_motherblog_validate($input) {
		// Our first value is either 0 or 1
		// $input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
		
		// Say our second option must be safe text with no HTML tags
		$input['network-signup-url'] =  wp_filter_nohtml_kses($input['network-signup-url']);
		
		return $input;
	}

}
    






    public function shortcodes() {
        
        function altlab_motherblog_func($atts) {
            $a = shortcode_atts(array('category' => 'uncategorized'), $atts);
            
            $feedwordpress_category = get_term_by('name', $a{'category'}, 'category');
            
            // check if selected category exists - if not create it
            if (!$feedwordpress_category) {
                wp_insert_term($a{'category'}, 'category');
                $feedwordpress_category = get_term_by('name', $a{'category'}, 'category');
            }
            
            function current_user_blogs() {
                
                $user_id = get_current_user_id();
                $user_blogs = get_blogs_of_user($user_id);
                
                $choices = '';
                
                foreach ($user_blogs as $user_blog) {
                    $choices.= "<option value='" . $user_blog->userblog_id . "'>" . $user_blog->blogname . "</option>";
                }
                
                return $choices;
            }
            
            function create_remote_category($feedwordpress_category) {
                
                if ($_POST['have-network'] === 'Yes') {
                    
                    // $feedwordpress_category = get_term_by('name', $a{'category'}, 'category');
                    
                    // Get blog id we will switch to for remote category creation
                    // $entry, 1 comes from form input where user selects blog.
                    $switch_to = $_POST['blog-select'];
                    
                    // https://codex.wordpress.org/WPMU_Functions/switch_to_blog
                    switch_to_blog($switch_to);
                    
                    // https://codex.wordpress.org/Function_Reference/wp_insert_term
                    wp_insert_term($feedwordpress_category->name, 'category');
                    
                    restore_current_blog();
                }
            }
            
            function add_current_user_to_mother_blog() {
                
                if ($_POST['have-network'] === 'Yes') {
                    
                    $user_id = get_current_user_id();
                    $blog_id = get_current_blog_id();
                    $role = 'subscriber';
                    
                    add_user_to_blog($blog_id, $user_id, $role);
                }
            }
            
            function create_fwp_link($feedwordpress_category) {
                if ($_POST['have-network'] === 'Yes') {
                    
                    // Switch to remote blog as selected by current user
                    $switch_to = $_POST['blog-select'];
                    switch_to_blog($switch_to);
                    
                    $remote_blog_url = get_site_url();
                    $remote_blog_name = get_bloginfo('name');
                    
                    // switch back to motherblog
                    restore_current_blog();
                    
                    $fwp_link_category = get_terms('link_category', $args = 'name__like=Contributors');
                    
                    $linkdata = array("link_url" => $remote_blog_url,
                    
                    // varchar, the URL the link points to
                    "link_name" => $remote_blog_name,
                    
                    // varchar, the title of the link
                    "link_rss" => $remote_blog_url . '/category/' . $feedwordpress_category->slug . '/feed',
                    
                    // varchar, a URL of an associated RSS feed
                    "link_category" => $fwp_link_category[0]->term_id
                    
                    // int, the term ID of the link category. if empty, uses default link category
                    );
                    
                    if (!function_exists('wp_insert_link')) {
                        include_once (ABSPATH . '/wp-admin/includes/bookmark.php');
                    }
                    
                    wp_insert_link($linkdata, true);
                }
            }
            
            function create_fwp_link_off_network() {
                if ($_POST['have-network'] === 'No' && $_POST['blog-feed']) {
                    
                    $fwp_link_category = get_terms('link_category', $args = 'name__like=Contributors');
                    
                    $linkdata = array("link_url" => $_POST['blog-feed'],
                    
                    // varchar, the URL the link points to
                    "link_name" => $_POST['blog-feed'],
                    
                    // varchar, the title of the link
                    "link_rss" => $_POST['blog-feed'],
                    
                    // varchar, a URL of an associated RSS feed
                    "link_category" => $fwp_link_category[0]->term_id);
                    
                    if (!function_exists('wp_insert_link')) {
                        include_once (ABSPATH . '/wp-admin/includes/bookmark.php');
                    }
                    
                    wp_insert_link($linkdata, true);
                }
            }
            
            function get_remote_blog_info() {
                $remote_blog = new stdClass;
                
                if ($_POST) {
                    $switch_to = $_POST['blog-select'];
                    
                    switch_to_blog($switch_to);
                    
                    $remote_blog->url = get_site_url();
                    $remote_blog->name = get_bloginfo('name');
                    
                    // switch back to motherblog
                    restore_current_blog();
                }
                
                return $remote_blog;
            }
            
            function get_network_name() {
                $network_id = get_blog_details()->site_id;
                $network_name = get_blog_details($network_id)->blogname;
                
                return $network_name;
            }

            function get_network_signup_url() {
            	$network_signup_url = get_option('altlab_motherblog_options');

            	return $network_signup_url['network-signup-url'];
            }
            
            if (is_user_logged_in()) {
                
                $blog_select = "
				<p>
					<label>Which of your blogs would you like to subscribe?</label><br/>
					<select id='blog-select' name='blog-select'>
				    	<option value=''>Select your blog</option>" . current_user_blogs() . "</select>
				</p>";
                $blog_select_login_prompt = "";
            } 
            else {
                $blog_select = "";
                $blog_select_login_prompt = "<p>Awesome! But you'll have to <a href='" . wp_login_url(get_the_permalink()) . "'>login</a> before we can take the next step.</p>";
            }
            
            $output = "
		    <form id='altlab-motherblog-subscribe' action='" . get_the_permalink() . "' method='post'>
			
				<fieldset id='fs1'>
					<div id='have-network'>
						<p>
							<label>Do you have a " . get_network_name() . " blog?</label><br/>
							<input type='radio' name='have-network' value='Yes'>Yes</input><br/>
							<input type='radio' name='have-network' value='No'>No</input>
						</p>
					</div>

					<div id='have-network-yes' style='display:none;'>
						" . $blog_select . "
						" . $blog_select_login_prompt . "
					</div>
				</fieldset>
				
				<fieldset id='fs2'>
					<div id='want-network' style='display:none;'>
						<p>
							<label>Do you want one?</label><br/>
							<input type='radio' name='want-network' value='Yes'>Yes</input><br/>
							<input type='radio' name='want-network' value='No'>No</input>
						</p>
					</div>

					<div id='want-network-yes' style='display:none;'>
						<p>Awesome! You can <a href='" . get_network_signup_url() . "'>get one here.</a></p>
					</div>
				</fieldset>

				<fieldset id='fs3'>
					<div id='have-blog' style='display:none;'>
						<p>
							<label>Do you have a blog somewhere else already?</label><br/>
							<input type='radio' name='have-blog' value='Yes'>Yes</input><br/>
							<input type='radio' name='have-blog' value='No'>No</input>
						</p>
					</div>

					<div id='have-blog-yes' style='display:none;'>
						<p>Cool, we just need the RSS feed from your site for the category you will use for this site.</p>
						<input id='blog-feed' type='text' name='blog-feed'></input>
						<small>If you aren't sure how to find your RSS feed, check out <a href='http://thoughtvectors.net/rss-stream/i-have-a-blog/specific/'>this page for common feed structures</a>.</small>
					</div>
					

					<div id='have-blog-no' style='display:none;'>
						<p>Sorry, but you have to have a blog somewhere to register.</p>

						<p>Fear not, you can <a href='".get_network_signup_url()."'>get a " . get_network_name() . " blog</a> for free! Just come back when you're ready. 😊</p>
					</div>
				</fieldset>

				<input id='email' type='text' name='email' size='25' value='' />

				<fieldset id='submit' style='display:none;'>
					<input type='hidden' name='submit' value='1'/>
					<input type='submit' value='Submit' />
				</fieldset>

			</form>
		    ";
            
            if (!is_admin() && $_POST) {
                
                if ($_POST['email']) {
                    die('<p>An error occurred. You have not been subscribed.</p>');
                } 
                else if (is_user_logged_in() && $_POST['blog-select'] && !$_POST['email']) {
                    create_remote_category($feedwordpress_category);
                    add_current_user_to_mother_blog();
                    create_fwp_link($feedwordpress_category);
                    
                    $output = '<p>The category "' . $a{'category'} . '" has been added to your blog "<strong>' . get_remote_blog_info()->name . '</strong>".</p>
                	<p>Only posts you create in the "' . $a{'category'} . '" category on your blog "<strong>' . get_remote_blog_info()->name . '</strong>" will appear on this site.</p>';
                } 
                else if ($_POST['blog-feed'] && !$_POST['email']) {
                    create_fwp_link_off_network();
                    
                    $output = "<p>You submitted the feed <a href='" . $_POST['blog-feed'] . "'>" . $_POST['blog-feed'] . "</a> to this site.<br/>
                	Only posts that show in the feed you submitted will appear on this site.</p>";
                } 
                else {
                    $output = "<p>An error occurred. You have not been subscribed.</p>";
                }
            }
            
            // print_r($_POST);
            
            // echo '<br/>spit out some vars <br/>';
            // echo $_POST['have-network'];
            
            return $output;
        }
        add_shortcode('altlab-motherblog', 'altlab_motherblog_func');
    }
}
