<?php
class AjaxHandler extends Action
{

	public function act_ajax()
	{
		$arguments = $this->args;       
		$moduleArgs = array("function" => $this->args[2], "data" => $this->args[3]);
		Modules::action($moduleArgs['function'], $moduleArgs);
	}	
	/*public function act()
	{		
		$arguments = explode("/", $this->args);			
		
		Modules::action("$this->action", $arguments);		
	}*/
}
?>