<?php
class ThemeHandler extends Action
{
	public function act()
	{	
		switch($this->action)
		{
			case "home":
				Theme::output();
			break;
			case "404":
				Theme::outputWith404($this->args);
			break;
		}
	}
}
?>