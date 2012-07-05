<?php 
	/* ----------------------------------------------------- */
	/* FUNCTION GETS ALL THE HITS TO A SPECIFIC SHORTEN URL  */
	/* ----------------------------------------------------- */
	if ($pageA[3]){
		// GET HIT DETAILS
		$u = "SELECT userAgent, returner, referrer, shorturl, ipAddress, DATE_FORMAT(dateHit,'%m/%d/%Y %H:%i') as uDate, countryCode, country, city, region, isp FROM url_hits WHERE shortURL = '{$pageA[3]}' ORDER BY dateHit DESC";
		$uSQL = mysql_query($u) or die(mysql_error());
		// GET URL DETAILS
		$s = "SELECT fullurl, urlTitle, hId, shorturl, DATE_FORMAT(dateCreated,'%m/%d/%Y %h:%i %p') as uDate, hId FROM urls WHERE hId = '{$pageA[3]}' LIMIT 1"; 
		$sSQL = mysql_query($s);
		$sOut = mysql_fetch_assoc($sSQL);
		
		//BUILD DATA TO RETURN
		if (mysql_num_rows($uSQL) > 0){
			$sub=array();
			$st = "200";
			while($uOut=mysql_fetch_assoc($uSQL)){
			//BUILD SUB ARRAY
				$sub[] = array(
					"short_url"=>cleanStringXML($uOut['shorturl']),
					"ip_address"=>cleanStringXML($uOut['ipAddress']),
					"link_ip_details"=>"/api/hits_by_ipaddress/".cleanStringXML($uOut['ipAddress']),
					"hit_date"=>cleanStringXML($uOut['uDate']),
					"returner_cookie"=>cleanStringXML($uOut['returner']),
					"link_returner_details"=>"/api/hits_by_cookie/".cleanStringXML($uOut['returner']),
					"referrer"=>cleanStringXML($uOut['referrer']),
					"link_referrer_details"=>"/api/hits_by_referrer/".cleanStringXML($uOut['referrer']),
					"user_agent"=>cleanStringXML($uOut['userAgent']),
					"link_user_agent_details"=>"/api/hits_by_bot/".cleanStringXML($uOut['userAgent']),
					"country_code"=>cleanStringXML($uOut['countryCode']),
					"country"=>cleanStringXML($uOut['country']),
					"city"=>cleanStringXML($uOut['city']),
					"region"=>cleanStringXML($uOut['region']),
					"isp"=>cleanStringXML($uOut['isp'])
				);	
			}
		}
		$responseContent = array (
			"page_title"=>cleanStringXML($sOut['urlTitle']),
			"link_details"=>"/api/hits/".$sOut['hId'],
			"hid"=>$sOut['hId'],
			"hash"=>$sOut['shorturl'],
			"short_url"=>"http://www.thekit.us/".$sOut['shorturl'],
			"date_mod"=>cleanStringXML($sOut['uDate']),
			"full_url"=>cleanStringXML($sOut['fullurl']),
			"hit_total"=>cleanStringXML(mysql_num_rows($uSQL)),
		);
	}else{
		$responseContent .=  '
			<status_code>400</status_code>
		    <data>
		    	<status_message>Missing Variables</status_message>
		    </data>';
	}
	
?>