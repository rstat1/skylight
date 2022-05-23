<?php
//TODO: Make database stuff easily extendable, and not depenedent on MySQL of any kind.
class Database
{
	static $numquerys = 0;
    static $CacheMisses = 0;
	static $CacheHits = 0;
 	static $dbquerys = " ";
	static $connect_id;		
	static $dataAsCSV = "";
	static $columns = "";
	public static function connect()
	{	
		global $config;
		if (!isset(self::$connect_id)) {self::$connect_id = mysqli_connect($config['db-server'], $config['db-user'], $config['db-pass']);}
		if(self::$connect_id)
		{
			$dbsel = mysqli_select_db(self::$connect_id, $config['db-name']);	
			if ($dbsel == true){
				echo "success";
				return self::$connect_id;
			}
			else{trigger_error(htmlentities(mysqli_error(self::$connect_id)), E_USER_ERROR);}
		}   
		else{trigger_error(htmlentities(mysqli_error(self::$connect_id)), E_USER_ERROR);} 
	}    
	public static function get($query, $cache = true, $table, $forceCacheUpdate = false)
	{
		global $config;
		$cacheHash = base64_encode($query);
		if ($table != NULL) {$filename = $table. "_$cacheHash.php";}
		else {$filename = "sql_$cacheHash.php";}
		if (Cache::inCache($filename) && $forceCacheUpdate == false)
		{
            self::$numquerys += 1;
			self::$CacheHits += 1;			
			return Cache::getDataFromCache($filename);
		}
		if ($query != NULL)
		{
			self::connect();
			$query_result = mysqli_query(self::$connect_id, $query);
			if(!$query_result){trigger_error(htmlentities(mysqli_error(self::$connect_id)), E_USER_ERROR);}
			else
			{
				$data = array(mysqli_num_rows($query_result), self::getResultAsArray($query_result));
				if ($cache && $config['data-cache']) {Cache::putDataInCache(base64_encode($query), $data, $table);}
                if ($forceCacheUpdate == false) {self::$CacheMisses += 1;}                
                self::$numquerys += 1;
				return $data;
			}
		}
		else{return '<b>MySQL Error</b>: Empty Query!';}
	}
	public static function ResultCount($queryData)
	{
		if (!is_array($queryData)) {trigger_error("ResultCount needs an array", E_USER_ERROR);}
		return $queryData[0];
	}
	public static function put($data, $table)
	{
		$finalQuery = "";
		$queryType = "";
		if ($data != NULL && $table != NULL)
		{			 
			if (is_array($data))
			{
				$dataToInsert = Utils::returnArrayAsCSV($data, false);
				$finalQuery = "INSERT INTO " . $table. " VALUES(" . $dataToInsert . ")";
				
				self::connect();
				$query_result = mysqli_query(self::$connect_id, $finalQuery);
				if ($query_result)
				{
					Utils::updateCachedData($table);
					return true;
				}
				else {trigger_error(mysqli_error(self::$connect_id));}
			}
			else {trigger_error("Function expects first argument to be an array.");}
		}
		else {trigger_error("This function requires data and table name.");}
	}
	public static function update($data, $table, $keyfield)
	{
        $finalQuery = "";
		$queryType = "";
		if ($data != NULL && $table != NULL)
		{			 
			if (is_array($data))
			{
		        self::$columns = Utils::returnColumnNames($data);
		        $sets = Utils::makeSets(explode(",",self::$columns), $data);
                $sets = trim($sets, ",");
                $finalQuery = "UPDATE $table SET $sets WHERE $keyfield[0] = ". $keyfield[1];
				self::connect();
				$query_result = mysqli_query(self::$connect_id, $finalQuery);	
				if ($query_result)
				{
					Utils::updateCachedData($table);
					return true;
				}
				else {trigger_error(mysqli_error(self::$connect_id));die();}
            }
			else {trigger_error("Function expects first argument to be an array.");}
		}
		else {trigger_error("This function requires data, a table name and a key field.");}
	}
    public static function remove($where, $table)
    {
        if ($where != NULL && $table != NULL)
		{
            if (is_array($where))
			{
                $finalQuery = "DELETE FROM $table WHERE $where[0] $where[1]  '$where[2]'";
                self::connect();                
				$query_result = mysql_query($finalQuery, self::$connect_id);	
				if ($query_result)
				{
					Utils::updateCachedData($table);
					return true;
				}
				else {trigger_error(mysql_error(). " " . $finalQuery);die();}
            }
        	else {trigger_error("Function expects first argument to be an array.");}
		}
		else {trigger_error("This function requires some form of criteria so it knows what to delete and a table name so it knows where to delete from.");}
    }
	public static function escape_string($str)
	{
		self::connect();
		return mysql_real_escape_string($str);
	}
	//borrowed from a page about a particular MySQL command on http://php.net
	public static function getResultAsArray($result)
	{
		$table_result=array();
		$r=0;
		while($row = mysqli_fetch_assoc($result))
		{
			$arr_row=array();
			$c=0;
			while ($c < mysql_num_fields($result)) 
			{  
				$col = mysql_fetch_field($result, $c);   
				$arr_row[$col -> name] = $row[$col -> name];           
				$c++;
			}   
			$table_result[$r] = $arr_row;
			$r++;
		}   
		return $table_result;
	}    
}