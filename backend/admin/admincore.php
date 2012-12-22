<?php
abstract class AdminCore
{
	public static $adminToolbarItems = array();
	public static $urlFilters = array();	
	static function registerURLFilter($filterby, $className)
	{
		$arr = array("filterby" => $filterby, "className" => $className);
		array_push(self::$urlFilters, $arr);
	}
    protected static function addToTemplate($data, $where)
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
	protected static function makeToolbarItem($imgPath, $jsFunctionName)
	{
		$arr = array("image" => $imgPath, "jsFunc" => $jsFunctionName);
		array_push(self::$adminToolbarItems, $arr);
	}
	protected static function makeTemplateVar()
	{
		$final = "";
		$topMargin = 2;
		if (isset(self::$adminToolbarItems) && count(self::$adminToolbarItems) > 0)
		{
			$final = "<span>";
			for($i = 0; $i < count(self::$adminToolbarItems); $i++)
			{
				$topMargin = $topMargin + 8;
				$img = '<img id="" style="margin-left:7px;" src="' .self::$adminToolbarItems[$i]['image']. '" onclick="'.self::$adminToolbarItems[$i]['jsFunc']. '"/>';
				$final .= $img;
			}
			$final .= "</span>";
			self::assignToVar("{#ADMINLINKS#}", $final);
		}
		else {self::assignToVar("{#ADMINLINKS#}", "");}
	}
	protected static function assignToVar($var, $data)		
	{
		Theme::$vars[] = $var;
		Theme::$vars_data[] = $data;
	}
	protected static function addURLFilter($regex)
	{
		URL::addToURLList($regex);
	}
}
?>