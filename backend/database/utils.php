<?php
class Utils
{
	static $csvColumns = "";
	static $columnNames = "";
	static $dataAsCSV = "";
	public static function returnColumnNames($data)
	{
		$keys = array_keys($data);
		self::$columnNames = 'true';
		self::$csvColumns = self::returnArrayAsCSV($keys, true);
		return self::$csvColumns;		
	}
	public static function returnArrayAsCSV($data, $columnNames = '')
	{	
		self::$csvColumns = "";
		self::$dataAsCSV = "";
		if (is_array($data))
		{
			$finalArray = array_map(array("Utils", "makeCSVString"), $data, array(self::$columnNames));
			$final = $finalArray[count($finalArray) - 1];
			$data = str_split($final, strrpos($final, ","));
			array_pop($data);
			return $data[0];
		}
		else {throw new InvaildArgument("returnArrayAsCSV kind of requires an array. DUH!!");}
	}
	public static function makeSets($columns, $data)
	{
		$x = 0;
		$sets = NULL;
		$finalSets = array();
		foreach($columns as $value)
		{
			if (!is_numeric($data[$value])) {$finalSets[$value] = "'". $data[$value] . "'";}
			else {$finalSets[$value] = $data[$value];}
		}
		$i = count($finalSets);
		foreach($finalSets as $column => $data)
		{	
			$x += 1;
			if ($x < $i){$sets .= $column . "=" . $data .", ";}
			else{$sets .= $column . "=" . $data ."";}
        }
		return $sets;
	}
	public static function singleQuoteAString($string)
	{
		return "'". $string . "'";
	}
	public static function updateCachedData($table)
	{
		$glob = glob(root_path . "/cache/".$table."_*.php");
		foreach($glob as $file)
		{
			$fileparts = explode("_", $file);
			$query = base64_decode($fileparts[1]);
			if (strstr($query, ")†")){$query = str_replace(")†", "", $query);}
			Database::get($query, true, $fileparts[0], true);
		}
	}
	private static function makeCSVString($n, $m)
	{		
    	if (is_numeric($n)){self::$dataAsCSV .= $n. ",";}
        else
        {				
			if (self::$columnNames == 'true'){self::$dataAsCSV .= $n. ",";}
			else {self::$dataAsCSV .= "'" .$n. "',";}
        	
        }
		self::$columnNames = '';
		return self::$dataAsCSV;
	}	
}