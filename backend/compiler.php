<?php
class compiler
{
	private static $hasnot = false;
	//private static $contains_quote = "";
	private static function convert_to($source, $target_encoding)
    {
		// detect the character encoding of the incoming file
		$encoding = mb_detect_encoding( $source, "auto" );
      
		// escape all of the question marks so we can remove artifacts from
		// the unicode conversion process
		$target = str_replace( "  ?	", "?", $source );
      
		// convert the string to the target encoding
		$target = mb_convert_encoding( $target, $target_encoding, $encoding);

		// remove any question marks that have been introduced because of illegal characters
		$target = str_replace( "?", "?", $target );
      
		// replace the token string "[question_mark]" with the symbol "?"
		$target = str_replace( "[question_mark]", "?", $target );
  
		return $target;
    }
	public static function tpl_write_cache($final_code, $tpl_name, $part, $cache_path, $nocache = "")
	{		
        if (!function_exists("putTemplateDataInCache"))
        {
		    global $config;
    		if ($cache_path == "") 
	    	{
		    	if (strstr($part, "login"))
			    {
				    $tplcachenm = "includes/templates/cache/login-". $part. ".php";
    			}
	    		else
		    	{
			    	$tplcachenm = "includes/templates/cache/". $tpl_name . "-" . $part . ".php";			
			    }	
		    }
		    else 
		    {
    			$tplcachenm = $cache_path;
		    }	
		    $handle = fopen($tplcachenm, "w+");
		    $final_code = str_replace(" ?	", "", $final_code);
		    fwrite($handle,$final_code);			
		    fclose($handle);
       }
       else {Cache::putTemplateDataInCache(Cache::getTemplateName($part), $final_code);
	}	
	public static function compile_template($template, $path, $part, $cpath, $nocache = "", $ext = "", $noext = false)
	{
		global $compiledtemp, $sid, $isecho, $isHTMLecho, $conelseec, $contains_quote,$user;
		if ($noext == false)
		{
			if ($ext == "") {$filename = $path . $part . ".htm";}
			else {$filename = $path . $part . ".$ext";}
		}
		else {$filename = $path . $part;}
		//$handle = fopen($filename, "r");
		if (file_exists($filename))
		{
			$code = file_get_contents($filename);											
			//echo $code;			
		}
		else
		{
			trigger_error("<b>Compile Failed:</b> File " .$filename. " could not be found.", E_USER_ERROR);
		}
		preg_match_all('#<!-- ([^<].*?) (.*?)? ?-->#', $code, $blocks, PREG_SET_ORDER);
		$text_blocks = preg_split('#<!-- [^<].*? (?:.*?)? ?-->#', $code);		
		$compiledtemp = array();		
		for ($curr_tb = 0, $tb_size = sizeof($blocks); $curr_tb < $tb_size; $curr_tb++)
		{			
			$block_val = &$blocks[$curr_tb];			
			switch($block_val[1])
			{
				case "INCLUDE":
					$cq = strstr($cqcontrol,"&quot;");
					if ($cq != NULL)
					{
						$contains_quote = true;						
					}
					else
					{
						$contains_quote = false;
					}
					$compiledtemp[] = "<?php include('" .$block_val[2]. "');";
				break;
				case "LOOP":
					$compiledtemp[] = self::compile_loop($block_val[2]);
				break;	
				case "ENDLOOP":
					$compiledtemp[] = "<?php } ?>";
				break;								
				case "IF":						
					$compiledtemp[] = "<?php " .self::compile_if($block_val[2], $contains_quote);
				break;				
				case "CURRENTUSER":
					/*print_r($user->data['username']);
					die();*/
					$compiledtemp[] =  $user->data['username'];
				break;
				case "SID":
					/*print_r($user->data['username']);
					die();*/
					$compiledtemp[] =  "sid=" . $sid;
				break;
				case "ECHOHTML":										
					$cq = stristr($text_blocks[$curr_tb],'"');					
					//echo "Hi!";
					if ($cq != FALSE)
					{
						$contains_quote = true;						
					}
					else
					{
						$contains_quote = false;		
					}				
					if ($contains_quote == true) {echo "Set";}
					//else {echo "Not set";}
					$compiledtemp[] = self::compile_htmlecho($block_val[2], true);					
					$isHTMLecho = true;
					$isecho = true;
				break;
				case "ECHO":
					//echo self::compile_echo($block_val[2]);
					$compiledtemp[] = self::compile_echo($block_val[2]);					
					$isecho = true;
				break;
				case "ELSE":
					$compiledtemp[] = "<?php ;} else { ?>";
				break;
				case "ELSEECHO":			
					if ($isecho == true) 
					{
						$compiledtemp[] = "';} else { echo '";
					}
					else {$compiledtemp[] = "';} else { echo '";}
					$conelseec = true;
					//print("Else Var = " .$block_val[1] . ' $conelsec = ' . $conelseec . " ");					
				break;
				case "ENDIF":
					if ($conelseec == true) 
					{						
						$compiledtemp[] = "' ;} ?>";
						$conelseec = false;
						break;
					}
					if ($isecho == true)
					{
						if ($isHTMLecho == true)
						{
							$compiledtemp[] = "';} ?>";
							$isHTMLecho = false;
							$isecho = false;
							break;					
						}
						$compiledtemp[] = " ';} ?>";
						$isecho = false;
						break;
					}
					else {$compiledtemp[] =  "<?php ;} ?>"; break;}
					if ($contains_quote = true)	
					{
						if ($conelseec == false or $conelseec == NULL) {$compiledtemp[] = "';} ?>";}
					}
					else
					{ 
						if ($conelseec == false or $conelseec == NULL)
						{
							$compiledtemp[] = " ';} ?>";
						}	
					}
				break;	
			}			
		}	
		$final_temp = '';		
		for ($i = 0, $size = sizeof($text_blocks); $i < $size; $i++)
		{
			$final_temp .= $text_blocks[$i] . $compiledtemp[$i];
		}
		self::tpl_write_cache($final_temp, $template, $part, $cpath, $nocache);
		//echo $final_temp;	
		//die();		
	}
	public static function checkForQuote($current_text)
	{
		
	}
	public static function compile_htmlecho($contents, $fixforquote)
	{
		global $isHTMLecho;	
		$isHTMLecho= true;
		
		if ($fixforquote) {$cecho = "'". str_replace("HTML", "" , $contents);}
		else {$cecho = '"'. str_replace("HTML", "" , $contents);}
		return "<?php echo ". $cecho;
	}
	public static function compile_loop($numiters)
	{
		$var_contents = explode(" ", $numiters);
		return "<?php for ($" .$var_contents[0]. " = 1; $" .$var_contents[0]. " <= " .$var_contents[2]. "; $" .$var_contents[0]. "++) { ?>";
	}
	public static function compile_echo($contents)
	{
		switch ($contents)
		{
			case "LOGGEDINUSER":
				$cecho = '$username';
			break;
			case "LOGOUTLINK":
				$cehco = '$logout';
			break;
			case "HTML":
			
			break;
			case "NAPPCONVALUE":
				if ($config['enewsap'] == "Y")
				{
					$cecho = 'checked="checked"';
				}	
				else
				{
					$cecho = '';
				}
			break;
		}
		return "echo ". $cecho;
	}
	public static function compile_if($var, $contains_quotes)	
	{		
		self::$hasnot = NULL;
		preg_match_all('/(?:
			"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"         |
			\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'     |
			[(),]                                  |
			[^\s(),]+)/x',$var, $matches);
		$tokens = $matches[0];
		//print_r($tokens);
		for ($i = 0, $size = sizeof($tokens); $i < $size; $i++)
		{
			$token = &$tokens[$i];
			if (strstr($token, "not")) {self::$hasnot = true; $token = $tokens[1];}
			if (strstr($token, "and")) {$hasand = true; $token = $tokens[1];}
			if (preg_match('#^((?:[a-z0-9\-_]+\.)+)?(\$)?(?=[A-Z])([A-Z0-9\-_]+)#s', $token, $varrefs))
			{			
				if (strstr($var, "EVEN") || strstr($var, "ODD")) 
				{
					$loopvar = explode(" ", $var);
					if ($loopvar[2] == "EVEN")
					{
						$compiledif = "if (fmod($" .$loopvar[0]. ", 2) == 0) { ?>";
						return $compiledif;
					}
					elseif ($loopvar[3] == "EVEN")
					{
						$compiledif = "if (fmod($" .$loopvar[1]. ", 2) != 0) { ?>";
						return $compiledif;
					}
					if ($loopvar[2] == "ODD")
					{
						$compiledif = "if (fmod($" .$loopvar[0]. ", 2) == 1) { ?>";
						return $compiledif;
					}
					elseif ($loopvar[3] == "ODD")
					{
						$compiledif = "if (fmod($" .$loopvar[1]. ", 2) != 1) { ?>";
						return $compiledif;
					}
				}				
				if ($token == "U_AUTH")
				{
					if (self::$hasnot == true) {$compiledif = 'if ($tplfuncs->check_perm("' .$tokens[1]. '") != true)' . " { ?>";}
					else {$compiledif = 'if ($tplfuncs->check_perm("' .implode(' ', $tokens). '") == true)' . " { ?>";}										
					return $compiledif;
				}
				if (strstr($token, "PAGE"))
				{	
					if (self::$hasnot == false){$compiledif = 'if (stristr($_SERVER["REQUEST_URI"],"'. $tokens[2]. '") != FALSE)  { ?>';	}	
					else{$compiledif = 'if (stristr($_SERVER["REQUEST_URI"],"'. $tokens[3]. '") == FALSE)  { ?>';}
					return $compiledif;
				}
				if (strstr($token, "INCLUDE"))
				{
					$compiledif = 'include('. $tokens[1]. ')';
					return $compiledif;
				}
				if (strstr($token, "R_"))				
				{
					$act_var = str_replace("R_", "", $token);
					$act_var = strtolower($act_var);
					if (self::$hasnot == true) {$tchk = $tokens[2];$valvar = $tokens[3];$andchk = $tokens[4]; $andval = $tokens[5];}
					else {$tchk = $tokens[1];$valvar = $tokens[2];$andchk = $tokens[3];$andval = $tokens[4];}
					if ($tchk == "not") 
					{
						if ($valvar == "NULL")
						{
	  						if (self::$hasnot == true) {$compiledif = 'if (!$_REQUEST["' . $act_var. '"] != ' .strtoupper($valvar). ')' . "{ ?>"; return $compiledif;}
							else{$compiledif = 'if ($_REQUEST["' . $act_var. '"] != ' .strtoupper($valvar). ')' . "{ ?>"; return $compiledif;}				
						}
						if (self::$hasnot == true) {$compiledif = 'if (!$_REQUEST["' . $act_var. '"] != "' .$valvar. '")' . "{ ?>"; return $compiledif;}
						else{$compiledif = 'if ($_REQUEST["' . $act_var. '"] != "' .$valvar. '")' . "{ ?>";return $compiledif;}
					}
					if ($andchk == "and")
					{						
						if ($tokens[4] == "not")
						{
							if (strstr($tokens[5], "P_") || strstr($tokens[5], "U") && $tokens[5] !== "U_AUTH" )						
							{
								$transvar = '$tplfuncs->check_perm("' .$tokens[5]. '") == false';
								$andvar = ""; //'$tplfuncs->check_perm("' .$tokens[4]. '") == true';
							}
							if ($tokens[3] == "and")
							{
								if($valvar == 'NULL') {$compiledif = 'if ($_REQUEST["' . $act_var. '"] == ' .$valvar. ' && '. $transvar  . ')' . "{ ?>";}
								else {$compiledif = 'if ($_REQUEST["' . $act_var. '"] == "' .$valvar. '"  && '. $transvar  . ')' . "{  ?>";}
								return $compiledif;
							}	
						}						
						else
						{	
							if (strstr($tokens[4], "P_") || strstr($tokens[4], "U") && $tokens[4] !== "U_AUTH" )						
							{
								$transvar = '$tplfuncs->check_perm("' .$tokens[4]. '") == true';
							}			
							if($valvar == 'NULL') {$compiledif = 'if ($_REQUEST["' . $act_var. '"] == ' .$valvar. ' && '. $transvar  . ")" . "{ ?>";}
							else {$compiledif = 'if ($_REQUEST["' . $act_var. '"] == "' .$valvar. '" && '. $transvar  . ")" . "{ ?> ";}
							return $compiledif;
						}
					}
					
					if ($valvar == "NULL")
					{
						if (self::$hasnot == true) {$compiledif = 'if ($_REQUEST["' . $act_var. '"] == ' .strtoupper($valvar). ')' . "{ ?>"; return $compiledif;}
						else{$compiledif = 'if ($_REQUEST["' . $act_var. '"] == ' .strtoupper($valvar). ')' . "{ ?>"; return $compiledif;}					
					}
					if (self::$hasnot == true) {$compiledif = 'if (!$_REQUEST["' . $act_var. '"] == "' .$valvar. '")' . "{ ?>";return $compiledif;}
					else{$compiledif = 'if ($_REQUEST["' . $act_var. '"] == "' .$valvar. '")' . "{ ?>";return $compiledif;}					
				}
				if (strstr($token, "C_"))
				{				
					if (self::$hasnot == true) {$compiledif = 'if (!$tplfuncs->check_config("' .$tokens[1]. '") == true)' . " {echo " . "'";}
					if (self::$hasnot == false) {$compiledif = 'if ($tplfuncs->check_config("' .implode(' ', $tokens). '") == true)' . " {echo " . "'";}
				}
				if (strstr($token, "P") || strstr($token, "U") && $token !== "U_AUTH" )						
				{						
					if (self::$hasnot == true && $hasand == false)  {$compiledif = 'if (!$tplfuncs->check_perm("' .$tokens[1]. '") == true)' . " { ?>";}
					if (self::$hasnot == false && $hasand == false) {$compiledif = 'if ($tplfuncs->check_perm("' .implode(' ', $tokens). '") == true)' . " { ?>";}	
				}			
			}
			return $compiledif;
		}	
	}
}
?>
