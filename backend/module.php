<?php
abstract class Module extends ModuleBase
{
	abstract public function info();
	abstract public function action_init();	
	abstract public function action_theme_init();
	private $toolbarItems = array();
	final public function __construct()
	{
	}
	function addToToolbar($item)
	{
		Theme::$toolbarItems[] = $item['html'];
	}
	function addToTemplate($data, $where)
	{
		switch($where)
		{
			case "header":
				Theme::$header_html[] = $data;
			break;
			case "body":
				Theme::$body_html[] = $data;				
			break;
			case "footer":
				Theme::$footer_html[] = $data;
			break;
		}		
	}
	function assignToVar($var, $data)		
	{
		Theme::$vars[] = $var;
		Theme::$vars_data[] = $data;
	}
	function addURLFilter($regex)
	{
		URL::addToURLList($regex);
	}
}
?>