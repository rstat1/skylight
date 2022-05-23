<?php
define("root_path", dirname(__FILE__));
spl_autoload_register("autoload");

include (root_path . "/backend/session.php");
include (root_path . "/backend/errorhandler.php");
ErrorHandler::set();
ob_start();
include (root_path . "/backend/config.php");
include (root_path . "/backend/constants.php");
// Session::init();


function autoload($class_name)
{
	$classfile = strtolower($class_name . ".php");
	$files = array();
	$dirs = array(root_path. "/backend", root_path. "/backend/handlers", root_path. "/modules/news", root_path. "/backend/database", 
                  root_path. "/backend/auth/handlers", root_path. "/backend/auth",root_path. "/backend/admin" );
	foreach($dirs as $dir)
	{		
		$glob = glob($dir. "/*.php");		
		$fnames = array_map(fn($a) => strtolower(basename($a)), $glob);
		if (is_array($fnames) && is_array($glob) && count($fnames) > 0){$files = array_merge($files, array_combine($fnames, $glob));}
	}
	if(isset($files[$classfile])) {include $files[$classfile];}
}
$mods = Modules::get(array(root_path . "/modules"));
foreach($mods as $mod){include_once($mod);}
foreach($mods as $mod){Modules::load($mod);}
Modules::action("theme_init");
Modules::action("init");
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
ob_end_flush();
?>