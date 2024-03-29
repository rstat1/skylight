<?php
class theme
{
	public static $vars = array();
	public static $vars_data = array();
	public static $body_html = array();
	public static $header_html = array();
	public static $footer_html = array();
	public static $toolbarItems = array();
	private static $page = "";
	private static $output = "";
	private static $selectedPart = "";

	public static function outputPiece($piece, $style)
	{
		global $config;
		self::addRequiredTags();
		switch($piece)
		{
			case "header":
         		$fileName = "style/" .$style. "/header.htm";	        
				self::$output = file_get_contents($fileName, FILE_USE_INCLUDE_PATH);
				self::$header_html[] = "\n\t". "<title>". $config['site-name']."</title>";
                self::$header_html[] = "\n\t". '<script type="text/javascript" src="js/common.js"></script>' . "\n";
                self::$header_html[] = "\n\t". '<script type="text/javascript">var path = "' .$config['base-path'] .'";</script>';
				self::$header_html[] = "\n\t". '<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />';
				if (count(self::$header_html) > 0) { foreach(self::$header_html as $head) {self::$output .= $head;} }
				self::$output .= "</head>\n<body>\n";
				self::$selectedPart = self::$output ;
			break;
			case "footer":
				self::$output= file_get_contents("style/" .$style. "/footer.htm", FILE_USE_INCLUDE_PATH);
				if (count(self::$footer_html) > 0) { foreach(self::$footer_html as $foot) {self::$output.= $foot;} }
				self::$output .= "\n</body>\n</html>";
				self::$selectedPart = self::$output;
			break;
		}
		self::$page = self::$selectedPart;
		$templateData = array_combine(self::$vars, self::$vars_data);
		return self::parse("cache/","template.htm", self::$vars, $templateData, self::$page, false, true);
	}
	public static function output($style)
	{
		global $config;
        
		$filepath = URL::scriptPath() . "/style/" .$style;
       
 	    $headerfile = file_get_contents($filepath. "/header.htm", FILE_USE_INCLUDE_PATH);
		$bodyfile = file_get_contents($filepath. "/body.htm", FILE_USE_INCLUDE_PATH);
		$footerfile = file_get_contents($filepath. "/footer.htm", FILE_USE_INCLUDE_PATH);

		self::$header_html[] = "\n\t". '<script type="text/javascript" src="js/common.js"></script>';
		self::$header_html[] = "\n\t". '<script type="text/javascript" src="style/' .$config['style']. '/js/index.js"></script>';
        self::$header_html[] = "\n\t". '<script type="text/javascript">var path = "' .$config['base-path'] .'";</script>';
		self::$header_html[] = "\n\t". '<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />';
		self::$header_html[] = "\n\t". "<title>". $config['site-name']."</title>";
		self::addRequiredTags();
		if (count(ErrorHandler::$debugmsgs) > 0) 
		{
			$msgs = '<div style="margin:20px;">';
			foreach (ErrorHandler::$debugmsgs as $msg) { $msgs .= $msg; } 
			self::$footer_html[] = $msgs;
		}
		
		if (count(self::$header_html) > 0) { foreach(self::$header_html as $head) {$headerfile .= $head;} }
		if (count(self::$body_html) > 0){ foreach(self::$body_html as $body) {$bodyfile .= $body;} }
		if (count(self::$footer_html) > 0) { foreach(self::$footer_html as $foot) {$footerfile .= $foot;} }
		$headerfile .= "</head>\n";
		$footerfile .= "\n</body>\n</html>";

		self::$page .= $headerfile . $bodyfile . $footerfile;

		$templateData = array_combine(self::$vars, self::$vars_data);
		Cache::putTemplateDataInCache(md5("template"), self::parse(URL::scriptPath(). "/cache/","template.htm", self::$vars, $templateData, self::$page, false, false));

		Compiler::compile_template("skylight", URL::scriptPath(). "/cache/", Cache::getCacheFileName("template"), URL::scriptPath(). "/cache/skylight-template.php", true, "" , true);
		include(URL::scriptPath() . "/cache/skylight-template.php");
	}
	public static function outputLoginBox()
	{
		global $config;
		$filepath = "style/" .$config['style'];
		self::addRequiredTags();
		$loginbox = file_get_contents($filepath.  "/login-style.htm", FILE_USE_INCLUDE_PATH);
		$templateData = array_combine(self::$vars, self::$vars_data);
		$parsedTemp = self::parse("", "", self::$vars, $templateData, $loginbox, false, false);
		if ($parsedTemp == NULL) {return $loginbox;}
		else {return $parsedTemp;}
	}
	public static function outputWith404($url)
	{
		trigger_error("The page you are looking for cannot be located or might be invaild. Never can tell.", E_USER_ERROR);
	}
	//$this->parse_template("includes/cache/", "cp-header.php", $tags, $contents, $template, true, true)
	public static function parse($temp_path, $temp_part, $tags, $replinfo, $toparse, $inc_output, $isfile)
	{		
		$content = "";					
		$filename = $temp_path . $temp_part;
		$contents = $toparse;
		foreach($tags as $tag)
		{			
			if (stristr($contents, $tag))
			{	
				if ($content == "")	{$content = str_replace($tag, $replinfo[$tag], $contents);}
				else {$content = str_replace($tag, $replinfo[$tag], $content);}
			}
		}		
		return $content;
	}
	private static function buildToolbarItemTagData()
	{
		if (count(self::$toolbarItems) > 0)
		{
			 foreach(self::$toolbarItems as $item) {$toolbar .= $item;}
			 return $toolbar;
		}
		else {return " ";}
	}
	private static function addRequiredTags()
	{
		global $config;
		$usage = memory_get_usage(true);
		
		self::$vars[] = "{#SITETITLE#}";
		self::$vars_data[] =  $config['site-name'];
		
        self::$vars[] = "{#VERSION#}";
		// if ($config['debug'] == true)
		// {
		// 	if (file_exists(root_path . "/skyCommitID")) 
		// 	{
		// 		$commitID = file_get_contents(root_path. "/skyCommitID");
		// 		if (strlen($commitID) > 12) {$commitID = substr($commitID, 0, 12);}
		// 	}	
		// }		
		// else {
			$commitID = $config['version'];
		// }
		self::$vars_data[] = $commitID;
        
		self::$vars[] = "{#TOOLBARITEMS#}";
		self::$vars_data[] = self::buildToolbarItemTagData();
		
		self::$vars[] = "{#BASEPATH#}";
		self::$vars_data[] =  URL::base();
			
		$use = round($usage / (1024), 2);
		self::$vars[] = "{#DEBUGMEMORY#}";
		self::$vars_data[] = "Memory Use: ". $usage / 1024 / 1024 ." MB";
	
		self::$vars[] = "{#USERNAME#}";
		if (isset($_COOKIE['sk_U'])){self::$vars_data[] = $_COOKIE['sk_U'];}
        else{self::$vars_data[] = "Guest";}
		
		self::$vars[] = "{#LOGINOUTTEXT#}";
		if (User::isUserLoggedIn() == false) {self::$vars_data[] = "Login";}
		else {self::$vars_data[] = "Logout";}
        
		self::$vars[] = "{#LOGINOUTLINK#}";
		self::$vars_data[] = "user/login/";
		
		self::$vars[] = "{#REGISTERTEXT#}";
		if (User::isUserLoggedIn() == false) {self::$vars_data[] = "Register!";}
		else {self::$vars_data[] = "";}
		
		self::$vars[] = "{#REGISTERLINK#}";
		self::$vars_data[] = "user/new/";		
		
		if (User::isAnAdmin() == true)
        {
            self::$vars[] = "{#ACPLINK#}";
		    self::$vars_data[] = '<a style="text-decoration:none;color:white;" href="admin/dashboard/">Administration</a>';
        }        
        
		self::$vars[] = "{#UCPLINK#}";
		self::$vars_data[] = 'User Settings';
		
		/*self:$vars[] = "{#VERSION#}";
		self::$vars_data[] = $config['version'];*/
	}
}
// <!-- IF PAGE is tag/firefox --> <!-- ENDIF -->
// if (stristr($_SERVER["REQUEST_URI"], "tag/firefox") != FALSE) {}
// <!-- IF PAGE is article --> <!-- ENDIF --> 
// if (NewsHelper::page == "article") {}
// $tplcomp->compile_template("cp", "includes/style/main/index/", "index-header", "includes/cache/cp-index-header.php", true);
?>