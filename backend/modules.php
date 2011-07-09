<?php
class Modules
{	
	private static $modules = array();
	private static $hooks = array();
	private static $module_classes = array();	
	public static function get($paths)
	{
//		$classfile = strtolower($class_name . ".php");
		$files = array();
		$dirs = $paths;
//		print_r($dirs);
		foreach($dirs as $dir)
		{
			$glob = glob($dir. "/*.php");		
			$fnames = array_map(create_function('$a', 'return strtolower(basename($a));'), $glob);
			if (is_array($fnames) && count($fnames) > 0) {$files = @array_merge($files, array_combine($fnames, $glob));}
		}
		self::$modules = $files;
		return self::$modules;
	}
	public static function load($file)
	{
		$mod = self::get_class_name($file);
		$module = new $mod;
		$module->init();
	}
	public static function extends_module($class)
	{	
		$parents = class_parents($class);
		return in_array("Module", $parents);		
	}
	private static function get_class_name($file)
	{
		if (self::$module_classes == NULL) {self::get_module_classes();}
		foreach(self::$module_classes as $mod)
		{
			$file = str_replace("\\", "/", $file);
			$class = new ReflectionClass($mod);
			$class_file = str_replace("\\", "/", $class->getFileName());
			if ($class_file == $file) {return $mod;}
		}
	}
	private static function get_module_classes()
	{
		$classes = get_declared_classes();
		self::$module_classes = array_filter($classes, array("Modules", "extends_module"));
	}
	public static function register($fn, $type, $hook, $priority)
	{
		$index = array($type, $hook, $priority);
		$ref =& self::$hooks;
		
		foreach( $index as $bit ) {
	//		echo $bit;
		    if(!isset($ref["{$bit}"])) {
		    	$ref["{$bit}"] = array();
		    }
		    $ref =& $ref["{$bit}"];
		}

		$ref[] = $fn;
		ksort(self::$hooks[$type][$hook]);		
	}
	public static function action($action, $args = NULL)
	{		
		
		if (isset(self::$hooks['action'][$action]))
		{			
			foreach (self::$hooks['action'][$action] as $priority)
			{
				foreach($priority as $module)
				{					
					call_user_func($module, $args);
				}	
			}
		}
	}
}	
?>