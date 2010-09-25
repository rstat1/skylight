<?php
abstract class ModuleBase
{
	public function init()
	{

		$methods = get_class_methods($this);
		$methods = array_combine($methods, $methods);
		
		if(method_exists($this, 'set_priorities')) 
		{
			$priorities = $this->set_priorities();

		}
		foreach($methods as $fn => $hooks)
		{
			foreach((array) $hooks as $hook)
			{
				if ((0 !== strpos($hook, "action_")) && (0 !== strpos($hook, "filter_")) && (0 !== strpos($hook, "theme_")))
				{					
					continue;
				}
				if (isset($priorities[$hook])) {$priority = $priorities[$hook];}
				else {$priority = 8;}
				$type = substr($hook,0, strpos($hook, "_"));
				$hook = substr($hook, strpos($hook,"_") + 1);
				Modules::register(array($this, $fn), $type, $hook, $priority);
			}
		}
	}	
}
?>