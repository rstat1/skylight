<?php
class Utils
{
	static $csvColumns = "";
	static $dataAsCSV = "";
	public static function returnColumnNames($data)
	{
		$keys = array_keys($data);
		self::$csvColumns = self::returnArrayAsCSV($keys, true);
		return self::$csvColumns;		
	}
	public static function returnArrayAsCSV($data, $columnNames = false)
	{	
		if ($columnNames == false) {echo "Normal Mode!";}
		else {echo "Columns Mode!";}
		self::$csvColumns = "";
		self::$dataAsCSV = "";
		if (is_array($data))
		{
			$finalArray = array_map(array("Utils", "makeCSVString"), $data, array($columnNames));
			$final = $finalArray[count($finalArray) - 1];;p;
			$data = str_split($final, strrpos($final, ","));
			array_pop($data);
			return $data[0];
		}
		else {throw new InvaildArgument("returnArrayAsCSV kind of requires an array. DUH!!");}
	}
	private static function makeCSVString($n, $m)
	{		
    	if (is_numeric($n)){self::$dataAsCSV .= $n. ",";}
        else{self::$dataAsCSV .= "'" .$n. "',";}
		return self::$dataAsCSV;
	}	
}