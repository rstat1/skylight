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
		if ($args[0] != 8)
		{
			echo '<link rel="stylesheet" href="' .  self::baseURL() . 'style/H2/css/messageboxes.css"/>';
			echo '<title>Whoops! Wasn\'t me :)</title></head><body><div class="warning-box"><div class="warning-box-header">';
			echo $args[1] .' on line: <b>'.  $args[3] .'</b> in file <b>'.$args[2] . '</b>';
			echo '</div></div></body></html>';
            if ($config['debug'] == true)
            {
               //print_r(array_walk( debug_backtrace(), create_function( '$a,$b', 'print "<br /><b>". basename( $a[\'file\'] ). "</b> &nbsp; <font color=\"red\">{$a[\'line\']}</font> &nbsp; <font color=\"green\">{$a[\'function\']} ()</font> &nbsp; -- ". dirname( $a[\'file\'] ). "/";' ) ));
               self::printBackTrace();
               //print_r(debug_backtrace());
            }    
		}	        
		return false;		
	}
    private static function printBackTrace()
    {
        $bt = debug_backtrace();
        for($i = 0; $i < count($bt) ; $i++)
        {    
            $class = ""; 
            $line = ""; 
            $file = ""; 
            $function = "";
            $function = $bt[$i]['function'];
            if (isset($bt[$i]['class'])) {$class = $bt[$i]['class'];}
            if (isset($bt[$i]['line'])) {$line = $bt[$i]['line'];}
            if (isset($bt[$i]['file'])) {$file = $bt[$i]['file'];}
            if ($class != "ErrorHandler" && $function != "trigger_error") 
            {
                //echo "at " .$class. "::" . $function. " on line " . $line . "<br />";
                echo 'at <font color="red">' . $class . "::" . $function . '</font> called on line<font color="red"> '. $line. "</font> in " . $file. "<br />";
            }            
        }
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