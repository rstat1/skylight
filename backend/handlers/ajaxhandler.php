<?php
class AjaxHandler extends Action
{

	public function act_ajax()
	{
		$arguments = explode("/", $this->args);	
		//echo "\tUsing $this->action in handler named $this->name to handle the request with arguments $this->args";		
		Modules::action("$this->action", $arguments);
	}	
	/*public function act()
	{		
		$arguments = explode("/", $this->args);			
		
		Modules::action("$this->action", $arguments);		
	}*/
}
?>