<?php
class AdminUI extends AdminCore
{
    public static function init()
    {
        Modules::action("admin_theme_init", NULL);
		Modules::action("admin_toolbar", NULL);
		self::makeTemplateVar();
        Theme::output("admin");
    }
	public static function addLink($imagePath, $functionName)
	{
		self::makeToolbarItem($imagePath, $functionName);
	}
}
?>