<?PHP 
	/* ----------------------------- */
	/* FUNCTION PROCESSES SHORT URL  */
	/* ----------------------------- */
	
	// NOW CHECK THAT THE SHORT URL IS VALID
	$cs = "SELECT hId, fullurl, clientId FROM urls WHERE shorturl = '{$page}' LIMIT 1";
	$csSQL = mysql_query($cs) or die(mysql_error());
	
	if (mysql_num_rows($csSQL) < 1){ // no records so send error
		$error = 1;
	}else{ // yes records so process next steps
		
		//CHECK FOR COOKIE AND IF NOT ONE SET ONE
		if (isset($_COOKIE['mypassRet'])){
			$cookie = $_COOKIE['mypassRet'];
			setcookie("mypassRet", hashIn('','cook_'), time()+60*60*24*365*10);
		}else{
			$cookie = 0;
			setcookie("mypassRet", hashIn('','cook_'), time()+60*60*24*365*10);
		}
		
		
		//BUILD ADDRESS FOR IP LOOK UP AND REQUEST DATA
		$file= "http://www.ipgp.net/api/xml/".$ip; //request data
		include('includes/ip_lookup.php'); //process data

		// NOW INSERT A HIT
		$out = mysql_fetch_assoc($csSQL);
	
		// NOW MAKE SURE THIS IP IS NOT EXCLUDED
		$ipc = "SELECT ip FROM excl_ip WHERE clientId = '{$out['clientId']}' and ip = '{$ip}' LIMIT 1";
		$ipcSQL = mysql_query($ipc) or ($error=mysql_error());
		if (mysql_num_rows($ipcSQL) > 0){
			$error = 1;
		}else{
			$h = hashIn('','h_');
			$i = "INSERT INTO url_hits SET hId = '{$h}', dateHit = NOW(), ipAddress = '{$ip}', referrer = '{$referer}', shortURL = '{$out['hId']}', returner = '{$cookie}', userAgent = '{$userAgent}', countryCode = '{$iplookup['code']}', country = '{$iplookup['country']}', flag = '{$iplookup['flag']}', city = '{$iplookup['city']}', region = '{$iplookup['region']}', isp = '{$iplookup['isp']}', lati = '{$iplookup['latitude']}', longi = '{$iplookup['longitude']}'";
			//$i = "INSERT INTO url_hits SET hId = '{$h}', dateHit = NOW(), ipAddress = '{$ip}', referrer = '{$referer}', shortURL = '{$out['hId']}', returner = '{$cookie}', userAgent = '{$userAgent}'";
			$iSQL = mysql_query($i);
		}	
			// NOW SEND ON THEIR WAY
			$fullURL = str_replace("&amp;","&",$out['fullurl']);
			header('location: ' .urldecode($fullURL));
			exit();
		
	}

?>