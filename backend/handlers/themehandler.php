<?php
class ThemeHandler extends Action
{
	public function act_displayLogin()
    {
		global $config;
		$bodyfile = file_get_contents("style/" .$config['style']. "/login-style.htm", FILE_USE_INCLUDE_PATH);
		echo Theme::outputPiece("header", $config['style']) . $bodyfile. Theme::outputPiece("footer", $config['style']);
    }
	public function act_displayRegister()
	{
		global $config;
		$bodyfile = file_get_contents("style/" .$config['style']. "/register-style.htm", FILE_USE_INCLUDE_PATH);
		echo Theme::outputPiece("header", $config['style']) . $bodyfile. Theme::outputPiece("footer", $config['style']);
	}
	public function act_home()
	{
        global $config;
		Theme::output($config['style']);
	}
	public function act_display404()
    {
		Theme::outputWith404($this->args);
	}	
}
?>