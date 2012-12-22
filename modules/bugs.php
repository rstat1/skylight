<?php
class bugs extends Module
{
	public function info(){}
	public function set_priorities(){}
	public function action_shutdown(){}
	public function action_theme_init(){}
	public function action_admin_save_post() {}
	public function action_admin_newpost() {}
	public function action_admin_dialog()
	{
	}
	public function action_admin_theme_init()
	{
		Theme::$header_html[] = "\n\t". '<script type="text/javascript" src="modules/bugs/js/bugsUI.js"></script>';
	}
	public function action_admin_toolbar()
	{
		AdminUI::addModuleButton("modules/bugs/style/images/bug_icon.png", "bugsDialog()");
	}
	public function action_tag($args)
	{
	}
	public function action_article($args)
	{
	}
	public function action_init()
	{
	}
}
?>