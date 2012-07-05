<?PHP 
	/* --------------------------------------------------- */
	/* FUNCTION PROCESSES API URL CALLS BOTH POST AND GET  */
	/* --------------------------------------------------- */

	if ($_SERVER['REQUEST_METHOD']==='POST' && $_POST['url'] && $pageA[2]==='urls'){ 
		//LOOK FOR POST IF SO, PROCESS NEW URL
		$url = addslashes($_POST['url']); // MAKE SURE INSERTION SAFE
		$cl = $_POST['cl'];
		//SEE IF THERE IS A URL PROVIDED
		if (validURL($url) == true && validCl($cl)){
			$has = hashIn('','l_'); //GET HASH ID FOR LINK
			$sh = shortURL(); //GET SHORT URL
			$clint = $_POST['cl']; // GET CLIENT ID
			//MAKE SURE THE CURRENT URL HASN'T BEEN SUBMITTED BY THE CURRENT IP
			$c = "SELECT shorturl FROM urls WHERE (creatorIp = '{$_SERVER['REMOTE_ADDR']}' and fullurl = '{$url}') or (clientId='{$cl}' and fullurl='{$url}') LIMIT 1";
			$cSQL = mysql_query($c);
			if (mysql_num_rows($cSQL) < 1){
				//NOW INSERT THE INFORMATION AND PASS BACK TO BE COPIED
				$title = addSlashes(getPageTitle($url)); 
				$i = "INSERT INTO urls SET hId = '{$has}', fullurl = '{$url}', shorturl = '{$sh}', urlTitle = '{$title}', dateCreated = NOW(), creatorIp = '{$_SERVER['REMOTE_ADDR']}', clientId = '{$cl}'";
				$iSQL = mysql_query($i);
				$st = "200";
				$responseContent = array (
					"status_message"=>1,
					"url"=>"http://thekit.us/".$sh,
					"hash"=>$sh
				);
			}else{
				$sht = mysql_result($cSQL,0);
				$st = "200";
				$responseContent = array (
					"status_message"=>2,
					"url"=>"http://thekit.us/".$sht,
					"hash"=>$sht
				);
			}
		}else{
			$st = "404";
			$responseContent = array (
				"status_code"=>'404',
				"status_message"=>'Missing Variables'
			);
		}
	}else if ($pageA[3]){
		// GET LINKS AND START PROCESSING
		$u = "SELECT fullurl, urlTitle, hId, shorturl, DATE_FORMAT(dateCreated,'%Y-%m-%d %H:%i %p') as uDate FROM urls WHERE clientId = '{$pageA[3]}'";
		if ($pageA[5]){
			$u .= " and (fullURL like '%{$pageA[5]}%' or shortURL like '%{$pageA[5]}%' or urlTitle like '%{$pageA[5]}%' or hId like '%{$pageA[5]}%')";
			$stat = "201";
		}else{
			$stat = "200";
		}
		$u .= " ORDER BY dateCreated DESC";
		if ($pageA[4]){
			$u.= " LIMIT " .$pageA[4];
		}
		$uSQL = mysql_query($u);
		if (mysql_num_rows($uSQL) > 0){
			$sub=array();
			while($uOut=mysql_fetch_assoc($uSQL)){
				//BUILD SUB ARRAY
			$h = "SELECT shortURL FROM url_hits WHERE shortURL = '{$uOut['hId']}'";
			$hSQL = mysql_query($h);
			$hits = mysql_num_rows($hSQL);
				$sub[] = array(
					"page_title"=>cleanStringXML($uOut['urlTitle']),
					"link_details"=>"/api/hits/".$uOut['hId'],
					"hid"=>$uOut['hId'],
					"hash"=>$uOut['shorturl'],
					"short_url"=>"http://www.thekit.us/".$uOut['shorturl'],
					"date_mod"=>cleanStringXML($uOut['uDate']),
					"full_url"=>cleanStringXML($uOut['fullurl']),
					"total_hits"=>cleanStringXML($hits)
				);	
			}
		}
		$st = "200";
		$responseContent = array (
			"total_urls"=>mysql_num_rows($uSQL)
		);
	}else{
		$st = "404";
		$responseContent = array (
			"status_code"=>'404',
			"status_message"=>'Missing Variables'
		);
	}

?>