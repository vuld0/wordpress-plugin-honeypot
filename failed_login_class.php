<?php

class Failed_Login
{
	private static $initiated = false;

	public static function init()
	{
		if (! self::$initiated){
			self::init_hooks();
		}
	}
	public static function init_hooks()
	{
		self::$initiated = true;
		add_filter('authenticate',array('Failed_Login','auth_signon'),1337,3);	
	}


/**
 *
 * The Authentication for the page
 * 		
 * 
 */
	public static function auth_signon($user,$username, $password)
	{
		if(is_wp_error($user)){
			if(!empty($username) && !empty($password)){
			if(!username_exists($username)){
				$logname = get_option('honeypot');
				$logname = $logname['log_name'];
				$logfile = fopen(plugin_dir_path(__FILE__).$logname,'a') or die('could not open/create file');
				fwrite($logfile, sprintf("au: %s - %s:%s\n",date('Y-m-d H:i:s'), $username, $password));
				fclose($logfile);
			}
			}
		}
		return $user;
	}

	public static function option_init()
	{
		$random_log_file_name = $rand = substr(md5(microtime()),rand(0,17),14) . '.log';
		$array['log_name'] = $random_log_file_name;
		$array['wp_authenticate_override'] = false;
		update_option('honeypot',$array);
	}

}