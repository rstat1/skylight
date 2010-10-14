<?php
class ThemeHandler extends Action
{
	public function act_displayLogin()
	{
		global $config;
		$bodyfile = file_get_contents("style/" .$config['style']. "/login-style.htm", FILE_USE_INCLUDE_PATH);
		echo Theme::outputPiece("header") . $bodyfile. Theme::outputPiece("footer");
	}
	public function act_home()
	{
		Theme::output();
	}
	public function act_display404()
	{
		Theme::outputWith404($this->args);
	}	
	/*public function act()
	{	
		switch($this->action)
		{
			case "home":
				Theme::output();
			break;
			case "404":
			
			break;
		}
	}*/
}
?>