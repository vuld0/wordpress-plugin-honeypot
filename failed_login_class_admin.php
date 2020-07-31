<?php

class Failed_login_admin
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

		add_action('admin_menu', array('Failed_login_admin','plugin_admin_add_page'));
		add_action('admin_init',array('Failed_login_admin','plugin_admin_init'));
	}

/**
 *
 * Adding the plugin page
 * 		
 * 
 */
	public static function plugin_admin_add_page()
	{
		add_options_page('Honeypot','Honeypot','manage_options','honeypot',array('Failed_login_admin','plugin_options_page'));
	}

	public static function plugin_admin_init()
	{
		register_setting('honeypot_options',array('Failed_login_admin','honeypot_validate'));
	}

	public static function honeypot_validate($input)
	{
		return $input;
	}

	public static function plugin_options_page()
	{
		$options = get_option('honeypot');
		$logpath = plugin_dir_url(__FILE__). $options['log_name'];
	?>

	<div class="wrap">
		<?php if(!$options['wp_authenticate_override']){
			?>
			<h1> Warning: You have other plugins trying to override the same functions as we use, this plugin may or may not work.</h1>
		<?php } ?>
		<h2>Honeypot</h2>

		<p>
			Your log file is currently accessible via <a href="<?php echo $logpath; ?>"><code><?php echo $logpath; ?> </code></a>.
		</p>

		<form action="options.php" method="post">
			<?php 
			settings_fields('honeypot_options');
			?>
			<h3>Edit log name</h3>

			<input type="text" size="50" name="honeypot[log_name]" value="<?php echo $options['log_name']; ?>" />
			<br />
			<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
		<br>
		<br>
	</div>

	<?php
		}

	}
