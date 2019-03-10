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
        add_options_page('ALT Lab Mother Blog Options', 'Mother Blog Options', 'manage_options', 'altlab_motherblog', 'altlab_motherblog_do_page');
    }

    // Draw the menu page itself
    function altlab_motherblog_do_page() {
        ?>
        <div class="wrap">
            <h2>ALT Lab Mother Blog Options</h2>
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

        $input['network-signup-url'] =  wp_filter_nohtml_kses($input['network-signup-url']);
        
        return $input;
    }

}
    






    public function shortcodes() {
        
        function altlab_motherblog_func($atts) {
            $a = shortcode_atts(
                array(
                    'category' => 'uncategorized',
                    'sub_categories' => '',
                    'get_comments' => 'false'
                ), $atts
            );


            /**
             * List categories for output
             *
             * @since   1.1.0
             * @var     string  $category           $a{category} from shortcode args
             * @var     string  $sub_categories     $a{sub_categories} from shortcode args
             * 
             * @return  string  HTML list of sub categories from the shortcode args
             */
            function list_created_categories($category, $sub_categories){
                $category_list = "<ul><li>".$category;

                if ($sub_categories){

                    $sub_categories = str_replace(' ', '', $sub_categories);
                    $array = explode(',',$sub_categories);

                    $category_list .= "<ul>";
                        foreach ($array as $item) {
                            $category_list .= "<li>".$item."</li>";
                        }
                    $category_list .= "</ul>";
                    
                }

                $category_list .= "</li></ul>";

                return $category_list;

            }

            function create_category( $string ){
                $string = str_replace(' ', '-', $string);
                wp_insert_term( $string, 'category' );
                $category = get_term_by('name', $string, 'category');

                return $category;
            }

            function create_remote_category($string) {
                
                if ($_POST['have-network'] === 'Yes') {
                                        
                    // Get blog id we will switch to for remote category creation
                    // $entry, 1 comes from form input where user selects blog.
                    $switch_to = $_POST['blog-select'];
                    
                    // https://codex.wordpress.org/WPMU_Functions/switch_to_blog
                    switch_to_blog($switch_to);
                    
                        create_category( $string );
                        flush_rewrite_rules();
                    
                    restore_current_blog();
                }
            }


            function create_sub_categories($string, $category){
                
                if ($string){
                    
                    $string = str_replace(' ', '', $string);
                    $array = explode(',',$string);

                    foreach( $array as $item ){
                        
                        $the_sub_category = get_term_by('name', $item, 'category');
                     
                        if( !$the_sub_category || (int)$the_sub_category->parent != (int)$category->term_id){
                            //expanded if statement to deal with duplicate sub cats on destination blog 
                            $args = array(
                                'parent' => (int)$category->term_id
                            );

                            wp_insert_term( $item, 'category', $args );                           
                        }   
                    }
                }
            }

            function create_remote_sub_categories($string, $category) {
                
                if ($_POST['have-network'] === 'Yes') {
                                        
                    // Get blog id we will switch to for remote category creation
                    // $entry, 1 comes from form input where user selects blog.
                    $switch_to = $_POST['blog-select'];
                    
                    // https://codex.wordpress.org/WPMU_Functions/switch_to_blog
                    switch_to_blog($switch_to);

                        create_sub_categories($string, $category);
                    
                    restore_current_blog();
                }
            }

            
            function get_current_user_blogs() {
                
                $user_id = get_current_user_id();
                $user_blogs = get_blogs_of_user($user_id);
                
                $choices = '';
                
                foreach ($user_blogs as $user_blog) {
                    $choices.= "<option value='" . $user_blog->userblog_id . "'>" . $user_blog->blogname . "</option>";
                }
                
                return $choices;
            }

            function get_blogs_of_current_user_by_role() {

                $user_id = get_current_user_id();
                $role = 'administrator';

                $blogs = get_blogs_of_user( $user_id );

                foreach ( $blogs as $blog_id => $blog ) {

                    // Get the user object for the user for this blog.
                    $user = new WP_User( $user_id, '', $blog_id );

                    // Remove this blog from the list if the user doesn't have the role for it.
                    if ( ! in_array( $role, $user->roles ) ) {
                        unset( $blogs[ $blog_id ] );
                    }
                }

                return $blogs;
            }

            function create_blogs_dropdown($blogs){
                $choices = '';

                foreach ($blogs as $blog) {
                    $choices.= "<option value='" . $blog->userblog_id . "'>" . $blog->blogname . "</option>";
                }

                return $choices;
            }
    
            function add_current_user_to_mother_blog() {
                
                if ($_POST['have-network'] === 'Yes') {
                    
                    $blog_id = get_current_blog_id();
                    $user_id = get_current_user_id();
                    $role = 'subscriber';
                    
                    if(! is_user_member_of_blog( $user_id, $blog_id ) ){
                        add_user_to_blog($blog_id, $user_id, $role);
                    }
                    
                }
            }
            
            function create_fwp_link($mother_category) {
                if ($_POST['have-network'] === 'Yes') {
                    
                    // Switch to remote blog as selected by current user
                    $switch_to = $_POST['blog-select'];
                    switch_to_blog($switch_to);
                    
                    $remote_blog_url = get_site_url();
                    $remote_blog_name = get_bloginfo('name');
                    
                    // switch back to motherblog
                    restore_current_blog();
                    
                    $fwp_link_category = FeedWordPress::link_category_id();
                    
                    $linkdata = array("link_url" => $remote_blog_url,
                    

                    // varchar, the URL the link points to
                    "link_name" => $remote_blog_name,
                    
                    // varchar, the title of the link
                    "link_rss" => $remote_blog_url . '/category/' . $mother_category->slug . '/feed',
                    
                    // varchar, a URL of an associated RSS feed
                    "link_category" => $fwp_link_category

                    // int, the term ID of the link category. if empty, uses default link category
                    );
                    
                    if (!function_exists('wp_insert_link')) {
                        include_once (ABSPATH . '/wp-admin/includes/bookmark.php');
                    }
                    
                    wp_insert_link($linkdata, true);
                }

            }


            function create_fwp_comments_link($mother_category) {
                if ($_POST['have-network'] === 'Yes') {
                    
                    // Switch to remote blog as selected by current user
                    $switch_to = $_POST['blog-select'];
                    switch_to_blog($switch_to);
                    
                    $remote_category_id = get_cat_ID( $mother_category->slug );
                    $remote_blog_url = get_site_url();
                    $remote_blog_name = get_bloginfo('name');
                    
                    // switch back to motherblog
                    restore_current_blog();
                    
                    $fwp_link_category = get_terms('link_category', $args = 'name__like=Contributors');
                    
                    $linkdata = array("link_url" => $remote_blog_url,
                    
                    "link_name" => $remote_blog_name . ' comments',
                    "link_rss" => $remote_blog_url . '/comments/feed/?cat=' . $remote_category_id ,
                    "link_category" => $fwp_link_category[0]->term_id
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


            /**
             * Get details of remote blog
             *
             * @since 1.0.0
             * @var     $blogID     id of blog to switch to
             * @var     $category   term_object ( should be string? cause we don't yet even have an object to give it yet I don't think... )
             *
             * @return  array()  
             */
            
            function get_remote_blog_info( $blogID, $category ) {
                $remote_blog = new stdClass;
                
                switch_to_blog($blogID);
                    
                    $remote_blog->url = get_site_url();
                    $remote_blog->name = get_bloginfo('name');
                    $remote_blog->term_id = get_cat_ID( $category->slug );
                    $remote_blog->feed_url = get_site_url() . '/category/' . $category->slug . '/feed';
                    $remote_blog->comments_feed_url = get_site_url() . '/comments/feed/?cat=' . get_cat_ID( $category->slug );
                        
                // switch back to motherblog
                restore_current_blog();
                
                return $remote_blog;
            }
            
            function get_network_name() {
                $network_id = get_blog_details()->site_id;
                $network_name = get_blog_details($network_id)->blogname;
                
                return $network_name;
            }

            function get_network_signup_url() {
                $network_signup_url = get_option('altlab_motherblog_options');

                if ( !$network_signup_url && ( $_SERVER['HTTP_HOST'] === 'rampages.us' ) ){
                    $network_signup_url['network-signup-url'] = "http://rampages.us/vcu-wp-signup.php";
                }

                return $network_signup_url['network-signup-url'];
            }


            /**
             * Check if blog is already signed up
             *
             * @since 1.1.0
             * @var     $remote_blog    object containing details about the remote blog we are trying to signup
             *
             * @return  array()  
             */

            function check_for_dupes( $remote_blog ){
                $args = array(
                    'limit'          => -1, 
                    'category_name'  => 'Contributors'
                );

                $links = get_bookmarks( $args );

                foreach ( $links as $link ){
                    if ( $link->link_rss === $remote_blog->feed_url ){
                        die('<p>This blog is already connected.</p>');
                    }
                } 
            }








            // set $mother_category var
            if ( get_term_by('name', $a{'category'}, 'category') ){
                $mother_category = get_term_by('name', $a{'category'}, 'category');
            } else {
                $mother_category = create_category( $a{'category'} );
            }


            // set $sub_categories var
            $sub_categories = $a{'sub_categories'};

            // create sub cats if they don't exist already
            create_sub_categories($sub_categories, $mother_category);

            if (is_user_logged_in()) {
                
                $blog_select = "
                <p>
                    <label>Which of your blogs would you like to connect?</label><br/>
                    <select id='blog-select' name='blog-select'>
                        <option value=''>Select your blog</option>" . create_blogs_dropdown( get_blogs_of_current_user_by_role() ) . "</select>
                </p>";
                $blog_select_login_prompt = "";
            } 
            else {
                $blog_select = "";
                $blog_select_login_prompt = "<p>Awesome! But you'll have to <a href='" . wp_login_url(get_the_permalink()) . "'>login</a> before we can take the next step.</p>";
            }
            
            $form = "
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
                        <p>Awesome! You can <a href='" . wp_registration_url() . "'>get one here.</a></p>
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

                $form_response = "";
                
                if ($_POST['email']) {
                    die('<p>An error occurred. You have not been connected.</p>');
                } 
                else if (is_user_logged_in() && $_POST['blog-select'] && !$_POST['email']) {

                    create_remote_category( $a{'category'} );

                    $remote_blog = get_remote_blog_info( $_POST['blog-select'], $mother_category );

                    check_for_dupes( $remote_blog );

                    
                    
                    create_remote_sub_categories( $sub_categories, $remote_blog );
                    
                    add_current_user_to_mother_blog();
                    
                    create_fwp_link( $mother_category );

                    if( $a{'get_comments'} === 'true' ){
                        create_fwp_comments_link($mother_category);
                    }
                    
                    if ( $sub_categories ){
                       $form_response .= '<h2>SUCCESS!</h2>';
                       $form_response .= '<p>The following category and sub categories have been added your blog "<strong>' . $remote_blog->name . '</strong>".</p>';
                       $form_response .= list_created_categories( $a{'category'},$sub_categories );
                       $form_response .= '<p>Only posts you create in these categories on your blog "<strong>' . $remote_blog->name . '</strong>" will appear on this site.</p>';
                       $form_response .= '<a href="' . $remote_blog->url . '">Return to your site '.$remote_blog->name.'</a>';

                    } else {
                        $form_response .= '<h2>SUCCESS!</h2>';
                        $form_response .= '<p>The category "<strong>' . $a{'category'} . '</strong>" has been added to your blog "<strong>' . $remote_blog->name . '</strong>".</p>
                    <p>Only posts you create in the "' . $a{'category'} . '" category on your blog "<strong>' . $remote_blog->name . '</strong>" will appear on this site.</p>';
                        $form_response .= '<a href="' . $remote_blog->url . '">Return to your site '.$remote_blog->name.'</a>';
                    }

                    return $form_response;

                } 
                else if ($_POST['blog-feed'] && !$_POST['email']) {
                    create_fwp_link_off_network();
                    
                    $form_response .= '<h2>SUCCESS!</h2>';
                    $form_response .= "<p>You submitted the feed <a href='" . $_POST['blog-feed'] . "'>" . $_POST['blog-feed'] . "</a> to this site.<br/>
                    Only posts that appear in the feed you submitted will appear on this site.</p>";

                    return $form_response;
                } 
                else {
                    $form_response .= "<h2>CRUSHING DEFEAT!</h2>";
                    $form_response .= "<p>An error occurred. You have not been connected. But you should totally try again.</p>";
                    $form_response .= "<p>An error occurred. You have not been subscribed. But you should totally try again.</p>";
                    $form_response = "<p>An error occurred. You have not been connected.</p>";
                    $form_response .= "<h2>CRUSHING DEFEAT!</h2>";
                    $form_response .= "<p>An error occurred. You have not been subscribed. But you should totally try again.</p>";


                    return $form_response;
                }
            }
            
            return $form;
        }

        add_shortcode('altlab-motherblog', 'altlab_motherblog_func');
    }
}
