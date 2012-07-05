<?php 
	/* ----------------------------------------------------- */
	/* FUNCTION GETS ALL THE HITS TO A SPECIFIC SHORTEN URL  */
	/* ----------------------------------------------------- */
	if ($pageA[3]){
		// GET HIT DETAILS
		$u = "SELECT userAgent, returner, referrer, shorturl, ipAddress, DATE_FORMAT(dateHit,'%m/%d/%Y %H:%i') as uDate, hId, countryCode, country, city, region, isp FROM url_hits WHERE returner = '{$pageA[3]}'";
		$u .=" ORDER BY dateHit DESC";
		if ($pageA[4]){
			$u .= " LIMIT ".$pageA[4];
		}
		$uSQL = mysql_query($u);
		
		//BUILD DATA TO RETURN
		if (mysql_num_rows($uSQL) > 0){
			$sub=array();
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
			"returner_cookie"=>$pageA[3],
			"hit_total"=>cleanStringXML(mysql_num_rows($uSQL)),
		);
	}else{
		$responseContent = array (
			"status_code"=>'404',
			"status_message"=>'Missing Variables'
		);
	}
	
?>