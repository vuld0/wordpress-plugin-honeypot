<?php

/**
Class Limit_Login_Attempts
*/

class Limit_Login_Attempts
{
	public $default_options = array(
		'');

	/**
	* Admin options page slug
	* @var string
	*/

	private $_page_options_slug = 'limit-login-attempts';

	/**
	* Errors message
	*
	* @var array
	*/

	public $_errors = array();

	/**
	* Additional login errors messages that we need to show
	*
	* @var array
	*/

	public $other_login_errors = array();

	/**
	* @var null
	*/

	private $use_local_options = null;

	public function __construct() {
		$this->hooks_init();
	}

	public function hooks_init() {
		add_action('plugins_loaded',array($this, 'setup'),9999);
	}
}