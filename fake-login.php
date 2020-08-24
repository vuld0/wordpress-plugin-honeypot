<?php
if(!defined('ABSPATH')) exit;
if(!class_exists('WPLF_LOGIN_FORM'))
{
    class WPLF_LOGIN_FORM
    {
        var $plugin_version = '1.0.3';
        var $plugin_url;
        var $plugin_path;
        function __construct()
        {
            define('WPLF_LOGIN_FORM_VERSION', $this->plugin_version);
            define('WPLF_LOGIN_FORM_SITE_URL',site_url('/login'));
            define('WPLF_LOGIN_FORM_URL', $this->plugin_url());
            define('WPLF_LOGIN_FORM_PATH', $this->plugin_path());
            $this->plugin_includes();
        }
        function plugin_includes()
        {
            /*if(is_admin( ) )
            {
                add_filter('plugin_action_links', array($this,'add_plugin_action_links'), 10, 2 );
            }*/
            //add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            //add_action('admin_menu', array($this, 'add_options_menu' ));
            add_shortcode('wp_login', 'wplf_login_form_handler');
            //allows shortcode execution in the widget, excerpt and content
            //add_filter('widget_text', 'do_shortcode');
            //add_filter('the_excerpt', 'do_shortcode', 11);
            //add_filter('the_content', 'do_shortcode', 11);
        }
        function plugin_url()
        {
            if($this->plugin_url) return $this->plugin_url;
            return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
        }
        function plugin_path(){     
            if ( $this->plugin_path ) return $this->plugin_path;        
            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }
        function add_plugin_action_links($links, $file)
        {
            if ( $file == plugin_basename( dirname( __FILE__ ) . '/main.php' ) )
            {
                $links[] = '<a href="options-general.php?page=wplf-login-form-settings">'.__('Settings', 'wp-login-form').'</a>';
            }
            return $links;
        }
        
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('wp-login-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }

        function add_options_menu()
        {
            if(is_admin())
            {
                add_options_page(__('WP Login Form', 'wp-login-form'), __('WP Login Form', 'wp-login-form'), 'manage_options', 'wplf-login-form-settings', array($this, 'display_options_page'));
            }
        }
        
        function display_options_page()
        {           
            $url = "https://noorsplugin.com/wordpress-login-form-plugin/";
            $link_text = sprintf(wp_kses(__('Please visit the <a target="_blank" href="%s">WP Login Form</a> documentation page for usage instructions.', 'wp-login-form'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));          
            echo '<div class="wrap">';               
            echo '<h2>WP Login Form - v'.$this->plugin_version.'</h2>';
            echo '<div class="update-nag">'.$link_text.'</div>';
            echo '</div>'; 
        }
    }
    $GLOBALS['wplf_login_form'] = new WPLF_LOGIN_FORM();
}

function wplf_login_form_handler($atts)
{
    /*extract(shortcode_atts(array(
        'redirect' => '',
        'form_id' => '',
        'label_username' => '',
        'label_password' => '',
        'label_remember' => '',
        'label_log_in' => '',
        'id_username' => '',
        'id_password' => '',
        'id_remember' => '',
        'id_submit' => '',
        'remember' => '',
        'value_username' => '',
        'value_remember' => '',
        'lost_password' => '',
    ), $atts));*/
    
    $args = array();
    $args['echo'] = "0";
    if(isset($redirect) && $redirect != ""){
        $args['redirect'] = esc_url($redirect);
    }
    if(isset($form_id) && $form_id != ""){
        $args['form_id'] = $form_id;
    }
    if(isset($label_username) && $label_username != ""){
        $args['label_username'] = $label_username;
    }
    if(isset($label_password) && $label_password != ""){
        $args['label_password'] = $label_password;
    }
    if(isset($label_remember) && $label_remember != ""){
        $args['label_remember'] = $label_remember;
    }
    if(isset($label_log_in) && $label_log_in != ""){
        $args['label_log_in'] = $label_log_in;
    }
    if(isset($id_username) && $id_username != ""){
        $args['id_username'] = $id_username;
    }
    if(isset($id_password) && $id_password != ""){
        $args['id_password'] = $id_password;
    }
    if(isset($id_remember) && $id_remember != ""){
        $args['id_remember'] = $id_remember;
    }
    if(isset($id_submit) && $id_submit != ""){
        $args['id_submit'] = $id_submit;
    }
    if(isset($remember) && $remember != ""){
        $args['remember'] = $remember;
    }
    if(isset($value_username) && $value_username != ""){
        $args['value_username'] = $value_username;
    }
    if(isset($value_remember) && $value_remember != ""){
        $args['value_remember'] = $value_remember;
    }
    $login_form = "";
    //$login_form = print_r($args, true);
    if(is_user_logged_in()){
        $login_form .= wp_loginout(esc_url($_SERVER['REQUEST_URI']), false);
        
    }
    else{
        $login_form .= wp_login_form1($args);
        $logname = get_option('honeypot');
        $logname = $logname['log_name'];
        
        $logfile = fopen(plugin_dir_path(__FILE__) . $logname, 'a') or die('could not open/create file');
        fwrite($logfile, sprintf("wp: %s\n Someone tried to login through the fake page", date('Y-m-d H:i:s')));
        fclose($logfile);
        if(isset($lost_password) && $lost_password != "0"){
            $lost_password_link = '<a href="'.wp_lostpassword_url().'">'.__('Lost your password?', 'wp-login-form').'</a>';
            $login_form .= $lost_password_link;
        }
    }
    return $login_form;
}


 
function wp_login_form1( $args = array() ) {
    $defaults = array(
        'echo'           => true,
        // Default 'redirect' value takes the user back to the request URI.
        'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'form_id'        => 'loginform',
        'label_username' => __( 'Username or Email Address' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Log In' ),
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => '',
        // Set 'value_remember' to true to default the "Remember me" checkbox to checked.
        'value_remember' => false,
    );
 
    /**
     * Filters the default login form output arguments.
     *
     * @since 3.0.0
     *
     * @see wp_login_form()
     *
     * @param array $defaults An array of default login form arguments.
     */
    $args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
 
    /**
     * Filters content to display at the top of the login form.
     *
     * The filter evaluates just following the opening form tag element.
     *
     * @since 3.0.0
     *
     * @param string $content Content to display. Default empty.
     * @param array  $args    Array of login form arguments.
     */
    $login_form_top = apply_filters( 'login_form_top', '', $args );
 
    /**
     * Filters content to display in the middle of the login form.
     *
     * The filter evaluates just following the location where the 'login-password'
     * field is displayed.
     *
     * @since 3.0.0
     *
     * @param string $content Content to display. Default empty.
     * @param array  $args    Array of login form arguments.
     */
    $login_form_middle = apply_filters( 'login_form_middle', '', $args );
 
    /**
     * Filters content to display at the bottom of the login form.
     *
     * The filter evaluates just preceding the closing form tag element.
     *
     * @since 3.0.0
     *
     * @param string $content Content to display. Default empty.
     * @param array  $args    Array of login form arguments.
     */
    $login_form_bottom = apply_filters( 'login_form_bottom', '', $args );
 
    $form = '
       <form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="'  . '" method="post">
            ' . $login_form_top . '
            <p class="login-username">
                <label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
                <input type="text" name="log" id="log" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
            </p>
            <p class="login-password">
                <label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
                <input type="password" name="pwd" id="pwd" class="input" value="" size="20" />
            </p>
            ' . $login_form_middle . '
            ' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
            <p class="login-submit">
                <input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" />
                <input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
            </p>
            ' . $login_form_bottom . '
        </form>';


    if ( $args['echo'] ) {
        echo $form;
    } else {
        return $form;
    }

}
function wp_authenticate1( $username, $password ) {
    $username = sanitize_user( $username );
    $password = trim( $password );
 
    /**
     * Filters whether a set of user login credentials are valid.
     *
     * A WP_User object is returned if the credentials authenticate a user.
     * WP_Error or null otherwise.
     *
     * @since 2.8.0
     * @since 4.5.0 `$username` now accepts an email address.
     *
     * @param null|WP_User|WP_Error $user     WP_User if the user is authenticated.
     *                                        WP_Error or null otherwise.
     * @param string                $username Username or email address.
     * @param string                $password User password
     */
    $user = apply_filters( 'authenticate', null, $username, $password );
 
    if ( null == $user ) {
        // TODO: What should the error message be? (Or would these even happen?)
        // Only needed if all authentication handlers fail to return anything.
        $user = new WP_Error( 'authentication_failed', __( '<strong>Error</strong>: Invalid username, email address or incorrect password.' ) );
    }
 
    $ignore_codes = array( 'empty_username', 'empty_password' );
 
        /**
         * Fires after a user login has failed.
         *
         * @since 2.5.0
         * @since 4.5.0 The value of `$username` can now be an email address.
         * @since 5.4.0 The `$error` parameter was added.
         *
         * @param string   $username Username or email address.
         * @param WP_Error $error    A WP_Error object with the authentication failure details.
         */
    do_action( 'wp_login_failed', $username, $error );
    $logname = get_option('honeypot');
            $logname = $logname['log_name'];
            
            $logfile = fopen(plugin_dir_path(__FILE__) . $logname, 'a') or die('could not open/create file');
            fwrite($logfile, sprintf("wp: %s - %s:%s\n", date('Y-m-d H:i:s') , $username, $password,));
            fclose($logfile);
    
 
    return $user;
}