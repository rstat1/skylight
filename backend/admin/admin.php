<?php
abstract class Admin
{
	public $adminToolbarItems = array();
    function display()
    {
		Modules::action("admin_theme_init", NULL);
		Modules::action("admin_toolbar", NULL);
        Theme::output("admin");
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
	function makeTemplateVar()
	{
		$final = "";
		for($i = 0; $i < array_count_values($this->$adminToolbarItems); $i++)
		{
			$final .= $this->$adminToolbarItems[i];
		}
		$this->assignToVar("{#ADMINTOOLBAR#}", $final);
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