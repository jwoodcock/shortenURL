<?php
	/* ------------------------- */
	/* FILE HANDLES API REQUESTS */
	/* ------------------------- */
	
	$sub=""; // variable that holds the guts 
	$st=""; // response status 
	$skip=false; // variable that determines to process request
	
	// CHECK TO SEE IF THERE IS AN API KEY IN HEADER
	if ($_SERVER && isset($_SERVER['HTTP_USER'])){
		$user_api = $_SERVER['HTTP_USER'];
		// TEST TO SEE IF PROVIDED KEY IS VALID
		$t = "SELECT apiKey FROM api_actives WHERE apiKey = '{$user_api}' and expires > NOW() LIMIT 1";
		$tSQL = mysql_query($t);
		if (mysql_num_rows($tSQL)>0){ // IF VALID
			// UPDATE THE EXPIRATION DATE
			$ds = date('Y-m-d H:i:s', mktime(date('H')+3, date('i'), date('s'), date('m'), date('d'), date('Y')));
			$u = "UPDATE api_actives SET expires = '{$ds}' WHERE apiKey = '{$user_api}' LIMIT 1";
			$uSQL = mysql_query($u);
		}else{ // IF NOT VALID
			// DELETE PROVIDED API KEY IF THERE IS ONE
			$d = "DELETE FROM api_actives WHERE apiKey = '{$pageA[3]}' LIMIT 1";
			$dSQL = mysql_query($d);
			$skip=true;
			$responseContent = array (
				"status_message"=>2,
				"message"=>"api key has expired"
			);
		}
	}else if ($pageA[2] != "authen" && $_SERVER['REQUEST_METHOD']!=='POST'){
		echo $_SERVER['HTTP_USER'];
		$st = "401";
		$skip = true;
		$responseContent = array (
			"status_message"=>401,
			"message"=>"invalid or no api key provided"
		);
	}
		
	if ($skip==false){
		// using $pageA array to check where api call is
		switch ($pageA[2]){
			case "urls":
				include("url_process.php");
				break;
			case "clients":
				include("clients_process.php");
				break;
			case "hits":
				include("hits.php");
				break;
			case "hits_by_cookie":
				include("hits_by_cookie.php");
				break;
			case "hits_by_ipaddress":
				include("hits_by_ipaddress.php");
				break;
			case "hits_by_date":
				include("hits_by_date.php");
				break;
			case "hits_by_bots":
				include("hits_by_bots.php");
				break;
			case "heartbeat":
				include("heartbeat.php");
				break;
			case "authen":
				include("authenicate.php");
				break;
			default:
				echo "error:";
		}
	}
	if ($responseContent){
		// NOW TAKE THE DATA AND FORMAT BASED ON REQUESTS AND FORMAT //
		if(false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/json')) {
			// return json
    		header('Content-Type: application/json; charset=utf-8');
		    echo json_encode($responseContent.$sub);
		}else{
			$xml='<?xml version="1.0" encoding="UTF-8"?><response><status_code>'.$st.'</status_code><data><hits><url_details>';
    		foreach($responseContent as $rk => $rv) {
	    		$xml .= "<".$rk.">".$rv."</".$rk.">";
    		}
    		$xml.='</url_details>';
	    	if ($sub && count($sub) > 0){
	    		for($c=0;$c<count($sub);$c++){    		
			   		$xml.='<entry>';
				    foreach($sub[$c] as $key => $value) {
				       //if (!$value){ $value = "-"; }
    				   $xml .= "<".$key.">".$value."</".$key.">";
		    		}
			   		$xml.='</entry>';
	   			}
		    }
			$xml.='</hits></data></response>';
		    header('Content-Type: application/xml; charset=utf-8');
		    header('Content-Length: '.strlen($xml));
		    
			echo trim($xml);
		}
	}
?>
