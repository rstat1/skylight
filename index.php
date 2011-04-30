<?php
define("root_path", dirname(__FILE__));	 
include (root_path . "/backend/errorhandler.php");
ErrorHandler::set();
ob_start();
include (root_path . "/backend/config.php");
include (root_path . "/backend/constants.php");
Session::init();
function __autoload($class_name)
{
	$classfile = strtolower($class_name . ".php");
	$files = array();
	$dirs = array(root_path. "/backend", root_path. "/backend/handlers", root_path. "/modules/news", root_path. "/backend/database", 
                  root_path. "/backend/auth/handlers", root_path. "/backend/auth",root_path. "/backend/admin" );
	foreach($dirs as $dir)
	{		
		$glob = glob($dir. "/*.php");		
		$fnames = array_map(create_function('$a', 'return strtolower(basename($a));'), $glob);
		if (is_array($fnames) && is_array($glob) && count($fnames) > 0){$files = array_merge($files, array_combine($fnames, $glob));}
	}
	if(isset($files[$classfile])) {include $files[$classfile];}
}
$nummods = count(Modules::get(array(root_path . "/modules")));
if ($nummods > 0)
{
	foreach(Modules::get(array(root_path . "/modules")) as $mod){include_once($mod);}
	foreach(Modules::get(array(root_path . "/modules")) as $mod){Modules::load($mod);}	
	Modules::action("theme_init");
	Modules::action("init");	
}
else 
{
	echo '<link rel="stylesheet" href="style/H2/css/messageboxes.css"/>';
	echo '<title>In the year of 400 and 1 there lived a bean stalk. But then he got run over by a raindeer and became a flattened bean staulk useless to all.</title></head><body>';
	echo '<p style="text-align:center;font-style:italic;">In the year of 400 and 1 there lived a bean stalk. But then he got run over by a raindeer and became a flattened bean staulk useless to all.</i><div class="message-box"><div class="message-box-header">';
	echo "Skylight is useless to the average user without any modules to load. Please add a module of some sort to the modules folder. That is, unless you like staring at pages with green text on them";
	echo '</div></div></body></html>';
	die();	
}
if (isset($_COOKIE['sk_U']) && $_SERVER['REQUEST_URI'] == $config['base-path'])
{
    if (!isset($_SESSION['currUser']));
    {
        User::createUserVar();
        Session::attachUserId($_SESSION['currUser']['id']);
    }
}
URL::parse($_SERVER['REQUEST_URI']);
if ($config['debug'] && $_SERVER['REQUEST_URI'] == $config['base-path'])
{
    echo '<br/><p style="color:white;">Number of cache misses:'. Database::$CacheMisses . "</p>";
    echo '<p style="color:white;">Number of cache hits:'. Database::$CacheHits. "</p>";
    echo '<p style="color:white;">Total number of queries:'. Database::$numquerys . "</p>";
}
session_write_close();
ob_end_flush();
?>