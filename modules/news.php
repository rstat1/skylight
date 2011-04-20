<?php
class news extends Module
{	
	public function info(){}
	public function set_priorities(){}
	public function action_shutdown(){}
	public function action_theme_init(){}
	public function action_admin_theme_init()
	{
		Theme::$header_html[] = "\n\t". '<script type="text/javascript" src="modules/news/js/newsAdminUI.js"></script>' . "\n";
	}
	public function action_admin_toolbar()
	{
		
	}
	public function action_tag($args)
	{	        
        global $config;	
		NewsHelper::Page("set", "tag/" . $args['data']);
        echo NewsHelper::getLatest5Articles($args['data'], $config['package-content-withL5A']);	
	}
	public function action_article($args)
	{
        echo NewsHelper::getArticleById($args['data']);
	}
	public function action_init()
	{
		global $config;
		$this->addURLFilter(array("name" => "show-article", "matchto" => "%article/([0-9-]+)%i" , "handler" => "AjaxHandler", "action" => "ajax"));
		$this->assignToVar("{#TAGS#}", NewsHelper::getTags());
		$this->assignToVar("{#LATESTARTICLES#}", NewsHelper::getLatest5Articles("home", $config['package-content-withL5A']));		
		$this->assignToVar("{#SITETITLE#}", $config['site-name']);
		$this->assignToVar("{#BASEPATH#}", URL::base());
		$request = NewsHelper::Page();		
		switch($request['Page'])
		{	
			case "ajax-load":
				echo NewsHelper::getNews($request['Args']);						
			break;
			case "article":
			break;
			default:								
		}	
		
	}	
}	
?>