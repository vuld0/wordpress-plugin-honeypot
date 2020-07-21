<?php 

ini_set('display_errors', 1);

//defining the plugin directories and stuffs

if(!function_exists('wp_authenticate')){
	$options = get_option('login_attempt');
	$options['wp_authenticate_override'] = true;
	update_option('login_attempt',$options);


function wp_authenticate($username,$password)
{
	$username = sanitize_user($username);
	$password = trim($password);


$user = apply_filters('authenticate',null,$username,$password);
if($user == null){
	$user = new WP_Error('authentication_failed',_('<strong>ERROR</strong>: Invalid username or incorrect password.'));
}

$ignore_codes = array(
	'empty_username',
	'empty_password');

if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes)){

	$logname = get_option('login_attempt');
	$logname = $logname['log_name'];
	$logfile = fopen(plugin_dir_path(__FILE__).$logname, 'a') or die('could not open/create file');
	fwrite($logfile, sprintf("wp: %s - %s:/n", date('Y-m-d H:i:s'), $username, $password));
	fclose($logfile);
	do_action('wp-login-failed',$username);
}

return $user;
}
}
else {
	$options = get_option('login_attempt');
	$options['wp_authenticate_override'] = false;
	update_option('login_attempt',$options);
}
