<?php
class AjaxHandler extends Action
{
	public function act()
	{		
		$arguments = explode("/", $this->args);			
		Modules::action("$this->action", $arguments);		
	}
}
?>