<?php
class cache
{
	public static function getcache($var_name)
	{

		if (self::in_cache($var_name) == true) 
		{	
			$path = root_path. "/includes/cache/cache-$var_name.php";	
			include($path);
			return self::data;
		}		
		else {return false;}
	}
	public static function in_cache($file)
	{	

		if (file_exists(root_path . $file)){return true;}	
		else {return false;}
	}
	public static function purge($path)
	{
		if (self::in_cache($path) == true) 
		{
			$path = root_path. $path;	
			unlink($path);
		}
	}	
	public static function put_in_cache_array($array, $value)
	{

		//$value = 'array_push(self::data,' . '"' . $value . '");' ."\n";
		if (self::in_cache($array) == true) 
		{
			$data = self::getcache($array);
			array_push($data, $value);
			self::savecache($array, $data);								
		}
	}
	public static function put_in_cache($value, $name, $ext)
	{ 
		try
		{
			//echo $value;
			$hand = fopen(root_path. "/cache/$name.$ext","w+");
			fwrite($hand,$value);
			fclose($hand);
		}
		catch (Exception $e)
		{
			echo "Hey I just screwed up!";
			echo $e->getMessage;
		}
	}
	public static function savecache($var_name, $var, $serialize = false)
	{	

		$hand = fopen(root_path. "/includes/cache/cache-$var_name.php","w+");
		if ($serialize == true) 
		{
			$cachevar = "<?php\n". 'self::data = unserialize('. var_export(serialize($var), true) . "); \n?>";
		}
		else 
		{	
			$cachevar = "<?php\n". 'self::data = '. var_export($var, true) . ";\n?>";
		}
		fwrite($hand, $cachevar);
		fclose($hand);
	}
}
?>
