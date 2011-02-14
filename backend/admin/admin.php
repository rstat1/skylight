<?php
abstract class Admin
{
    function display()
    {
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