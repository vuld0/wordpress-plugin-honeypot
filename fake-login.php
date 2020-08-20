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
            define('WPLF_LOGIN_FORM_SITE_URL',site_url());
            define('WPLF_LOGIN_FORM_URL', $this->plugin_url());
            define('WPLF_LOGIN_FORM_PATH', $this->plugin_path());
            $this->plugin_includes();
        }
        function plugin_includes()
        {
            if(is_admin( ) )
            {
                add_filter('plugin_action_links', array($this,'add_plugin_action_links'), 10, 2 );
            }
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('admin_menu', array($this, 'add_options_menu' ));
            add_shortcode('wp_login_form', 'wplf_login_form_handler');
            //allows shortcode execution in the widget, excerpt and content
            add_filter('widget_text', 'do_shortcode');
            add_filter('the_excerpt', 'do_shortcode', 11);
            add_filter('the_content', 'do_shortcode', 11);
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
    extract(shortcode_atts(array(
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
    ), $atts));
    
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
        $login_form .= wp_login_form($args);
        if(isset($lost_password) && $lost_password != "0"){
            $lost_password_link = '<a href="'.wp_lostpassword_url().'">'.__('Lost your password?', 'wp-login-form').'</a>';
            $login_form .= $lost_password_link;
        }
    }
    return $login_form;
}
