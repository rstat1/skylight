<?php
class URL
{
	private static $requestHandled;
	private static $knownURLs = array();
	private static $knownModUrls = array();

	public static function setSystemURLs()
	{		
		$knownURLs = array_push(self::$knownURLs,array("name" => "404", "matchto" => "Nothing", "handler" => "ThemeHandler", "action" => "display_404"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "home", "matchto" => "%/%", "handler" => "ThemeHandler", "action" => "display_home"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "login", "matchto" => "%login/%", "handler" => "ThemeHandler", "action" => "displayLogin"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "auth", "matchto" => "%auth/([A-Za-z0-9-]+)%mx", "handler" => "UserHandler", "action" => "(%page%)"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "user", "matchto" => "%user/([A-Za-z0-9-]+)%mx", "handler" => "UserHandler", "action" => "(%page%)"));
        $knownURLs = array_push(self::$knownURLs,array("name" => "ajax", "matchto" => "%ajax/([A-Za-z0-9-]+)%mx" , "handler" => "AjaxHandler", "action" => "ajax"));
        $knownURLs = array_push(self::$knownURLs,array("name" => "dash", "matchto" => "%admin/%" , "handler" => "AdminHandler", "action" => "admin"));
        $knownURLs = array_push(self::$knownURLs,array("name" => "admin", "matchto" => "%admin/([A-Za-z0-9-]+)%mx" , "handler" => "AdminHandler", "action" => "admin"));
		//$knownURLs = array_push(self::$knownURLs,array("name" => "home", "matchto" => "%/%", "handler" => "ThemeHandler", "action" => "displayHome"));
		//$knownURLs = array_push(self::$knownURLs,array("name" => "404", "matchto" => "Nothing", "handler" => "ThemeHandler", "action" => "display404"));
	}
	public static function base()
	{
        global $config;        
        return "http://" . $_SERVER['SERVER_NAME'] . $config['base-path'];
		/*$script_path = explode("/", $_SERVER['REQUEST_URI']);
       	if ($script_path[1] == ""){return "http://" . $_SERVER['SERVER_NAME'];}
	    else {return "http://" . $_SERVER['SERVER_NAME'] . "/" . $script_path[1] . "/";}*/
	}
	public static function scriptPath()
	{
        
        return root_path;
      /*$script_path = explode("/", $_SERVER['REQUEST_URI']);
        $spvarCount = count($script_path);
	    if (count > 4){return "/" . $script_path[1];}
		else {return "/";}*/
        
	}
	public static function parse($url)
	{
        global $config;
        self::setSystemURLs();
        $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
		$parsedURL = $_SERVER['REQUEST_URI'];
        $parsedURL = str_replace($path, "", $parsedURL);
		$actionArgs = explode("/", $parsedURL);		
		if (count($actionArgs) > 1 && $actionArgs[1] == "login")
		{
			self::activateHandler("ThemeHandler", "displayLogin", "");
			self::$requestHandled = true;
		}
        if ($parsedURL == "/" || $parsedURL == "" || $parsedURL == $config['base-path'])
		{
			self::activateHandler("ThemeHandler", "home", "");
			self::$requestHandled = true;
		}
        foreach (self::$knownURLs as $handler => $hand)
		{            
			if ($hand['name'] != "404" && $hand['name'] != "home" && self::$requestHandled == false)
			{                
				if (preg_match($hand['matchto'], $parsedURL))
				{  
					//print_r($hand);
					self::activateHandler($hand['handler'], $hand['action'], $actionArgs);
					self::$requestHandled = true;
				}
			}
		}
		if (self::$requestHandled == false)
		{
			$handler = self::getHandler("404");
			$han = new $handler();
			$han->args = "";
			$han->act("404");
		}
		else {return true;}
	}
	public static function registerModuleURL($type, $module, $action)
	{
		 array_push(self::$knownModURLs,array("type" => $type, "action" => $action, "moduleName" => $module));
	}
	public static function addToURLList($url)
	{
		self::$knownURLs[] = $url;
	}
	private static function getHandler($name)
	{
		foreach (self::$knownURLs as $handler => $hand)
		{
			if ($hand['name'] == $name) 
			{
				return $hand['handler'];
			}
		}
	}
	private static function activateHandler($handler, $action, $args)
	{
        $hand = new $handler;
		$hand->action = $action;
		$hand->args = $args;
		if ($action == "(%page%)") {$action = $args[2];}
		//print($action);
		$hand->act($action);
	}
/*	private static function activateHandler($name, $action, $args)
	{
		echo "activateHandler($name, $action, $args)\t";
		$handler = self::getHandler($name);
		$hand = new $handler();
		echo $handler;
		die();
		$hand->name = $name;
		$hand->action = $action;
		$hand->args = $args;
		$hand->act($name);
	}*/
}
?>
