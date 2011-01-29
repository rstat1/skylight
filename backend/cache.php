<?php
class cache
{	
	public static function getDataFromCache($name)
	{
		$cacheFile = fopen(root_path. "/cache/$name", "r+");
		$data = fread($cacheFile, filesize(root_path. "/cache/$name"));
		fclose($cacheFile);
		$usableData = unserialize(base64_decode($data));
		if ($usableData === false) {trigger_error(htmlentities("Hmm for some reason the cached data for $name is invaild."), E_USER_ERROR);}
		return $usableData;
	}
	
	public static function getTemplateDataFromCache($tplName)
	{	
		$name = md5($tplName);
		if (inCache($name) == true)
		{
			$cacheFile = fopen(root_path. "/cache/sql_$name.php", "r+");
			$data = fread($cacheFile, filesize(root_path. "/cache/sql_$name.php"));
			fclose($data);
			return unserialize($data);
		}
	}
	public static function getCacheFileName($nameToHash)
	{
		return "tpl_" . md5($nameToHash). ".php";
	}
	public static function putTemplateDataInCache($name, $data)
	{
		$cacheFile = fopen(root_path. "/cache/tpl_$name.php", "w+");
		if (is_array($data))
		{
			fwrite($cacheFile, var_export(serialize($data), true));
		}
		else {fwrite($cacheFile, $data);}
		fclose($cacheFile);
	}
	public static function putDataInCache($name, $data)
	{		
		$cacheFile = fopen(root_path. "/cache/sql_$name.php", "w+");
		if (is_array($data))
		{
			fwrite($cacheFile, base64_encode(serialize($data)));
		}
		else {fwrite($cacheFile, $data);}
		fclose($cacheFile);
	}
	public static function put_in_cache($value, $name, $ext)
    {
		self::putTemplateDataInCache(md5($name), $value);
    }
	public static function inCache($file)
	{	
		if (file_exists(root_path . "cache/" .$file)) {return true;}
	}
	public static function purge($path)
	{
		
	}		
}
?>