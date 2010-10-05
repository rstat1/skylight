<?php
class ThemeHandler extends Action
{
	public function act_displayPost()
	{
		
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