<?php
class URL
{
	private static $requestHandled;
	private static $knownURLs = array();

	public static function setSystemURLs()
	{
		$knownURLs = array_push(self::$knownURLs,array("name" => "404", "matchto" => "Nothing", "handler" => "ThemeHandler", "action" => "display_404"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "home", "matchto" => "%/%", "handler" => "ThemeHandler", "action" => "display_home"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "loginbox", "matchto" => "%login/%", "handler" => "ThemeHandler", "action" => "displayLogin"));
		$knownURLs = array_push(self::$knownURLs,array("name" => "user", "matchto" => "%auth/([A-Za-z0-9-]+)%mx", "handler" => "UserHandler", "action" => "authenticate"));
		//$knownURLs = array_push(self::$knownURLs,array("name" => "home", "matchto" => "%/%", "handler" => "ThemeHandler", "action" => "displayHome"));
		//$knownURLs = array_push(self::$knownURLs,array("name" => "404", "matchto" => "Nothing", "handler" => "ThemeHandler", "action" => "display404"));
	}
	public static function base()
	{
		$script_path = explode("/", $_SERVER['REQUEST_URI']);
        	if ($script_path[1] == ""){return "http://" . $_SERVER['SERVER_NAME'];}
	        else {return "http://" . $_SERVER['SERVER_NAME'] . "/" . $script_path[1] . "/";}
	}
	public static function scriptPath()
	{
		$script_path = explode("/", $_SERVER['REQUEST_URI']);
		if ($script_path[1] == ""){return "/";}
		else {return "/" . $script_path[1];}
	}
	public static function parse($url)
	{
		self::setSystemURLs();
		$parsedURL = str_replace(self::scriptPath(), "", $url);
		if ($parsedURL == "") 
		{
			$parsedURL = self::scriptPath();

		}
		//else {$parsedURL = self::scriptPath();}
		$actionArgs = explode("/", $parsedURL);
		if (count($actionArgs) > 1 && $actionArgs[1] == "login")
		{
			self::activateHandler("ThemeHandler", "displayLogin", "");
			self::$requestHandled = true;
		}
		if ($parsedURL == "/" || $parsedURL = "")
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
