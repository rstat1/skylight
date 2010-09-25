<?php
class ErrorHandler
{
	public static $debugmsgs = array();
	private static function baseURL()
	{
		$script_path = explode("/", $_SERVER['REQUEST_URI']);		
		return "http://" . $_SERVER['SERVER_NAME'] . "/" . $script_path[1] . "/";
	}
	public static function set()	
    {
		error_reporting(0);
        set_error_handler(array('ErrorHandler', 'displayError'));
		set_exception_handler(array('ErrorHandler', 'displayException'));
    }
	public static function displayError()
	{
		global $config;
		$args = func_get_args();
		echo '<link rel="stylesheet" href="' .  self::baseURL() . 'style/H2/css/messageboxes.css"/>';
		echo '<title>Whoops! Wasn\'t me :)</title></head><body><div class="warning-box"><div class="warning-box-header">';
		echo $args[1] .' on line: <b>'.  $args[3] .'</b> in file <b>'.$args[2] . '</b>';
		echo '</div></div></body></html>';
		return true;
		
	}
	public static function displayException()
	{
		ob_start();
		$args = func_get_args();
		echo '<html><head><title>Skylight Error</title><link rel="stylesheet" href="' . self::base(). 'style/css/messageboxes.css"/></head>';
		echo '<body><div class="warning-box"><div class="warning-box-header">'. $args[1] .' on line '.  $args[3] .' in file '.$args[2];
		echo '</div></div></body></html>';
		ob_end_flush(); 
	}
	
}
?>