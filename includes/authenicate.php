<?php 
	
	/* ----------------------------------------------------------------------- */
	/* FUNCTION PROCESSES REGERTING FOR AN API KEY AND VALIDATING AN EXISTING  */
	/* ----------------------------------------------------------------------- */
	if ($_SERVER['REQUEST_METHOD']==='POST' && $_POST['us'] && $_POST['pa']){ 
		// HANDLE VARIABLES
		$pass = mysql_real_escape_string($_POST['pa']);
		$us = mysql_real_escape_string($_POST['us']);
		
		// CHECK DATABASE
		$d = "SELECT clientId FROM api_logi WHERE apiUs = '{$us}' and apiPas = '{$pass}' LIMIT 1";
		$mes = $d;
		$dSQL = mysql_query($d);
		
		if (mysql_num_rows($dSQL) > 0){
			$cli = mysql_result($dSQL,0);
			$ip = $_SERVER['REMOTE_ADDR'];
			// NOW CHECK FOR ACTIVE API KEY BY CLIENT ID
			$t = "SELECT apiKey, expires FROM api_actives WHERE clientId = '{$cli}' and ipAddress = '{$ip}' and expires > NOW() LIMIT 1";
			$tSQL = mysql_query($t);
			
			if (mysql_num_rows($tSQL) < 1){
				//DELETE ANY OTHER KEYS FOR THIS CLIENT
				$d = "DELETE FROM api_actives WHERE clientId = '{$cli}'";
				$dSQL = mysql_query($d);
				//BUILD NEW KEY INSERT AND OUTPUT AS MESSAGE
				$st = "200";
				$apiK = hashIn('','api_');
				$ds = date('Y-m-d H:i:s', mktime(date('H')+3, date('i'), date('s'), date('m'), date('d'), date('Y')));
				//echo $ds;
				//create key
				$s = "INSERT INTO api_actives SET apiKey = '{$apiK}', ipAddress = '{$ip}', expires = '{$ds}', clientId = '{$cli}'";
				$sSQL = mysql_query($s) or die(mysql_error());
				$responseContent = array (
					"status_message"=>1,
					"api_key"=>$apiK,
					"expires"=>$ds
				);
			}else if (mysql_num_rows($tSQL) > 0 && $_POST['distroy'] == 'true'){
				$d = "DELETE FROM api_actives WHERE clientId = '{$cli}'";
				$dSQL = mysql_query($d) or $error = mysql_error();
				$responseContent = array (
					"status_message"=>4,
					"message"=>"api key has been distroyed "
				);
			}else{			
				$st = "403";
				$responseContent = array (
					"status_message"=>3,
					"message"=>"Already an active API key. Please use that on your requests."
				);
			}
		}else{
			$st = "401";
			$responseContent = array (
				"status_message"=>3,
				"message"=>"invalid login".$mes
			);
		}
	}else if ($pageA[3]){
		// TEST TO SEE IF PROVIDED KEY IS VALID
		$t = "SELECT apiKey FROM api_actives WHERE apiKey = '{$pageA[3]}' and expires > NOW() LIMIT 1";
		$tSQL = mysql_query($t);
		if (mysql_num_rows($tSQL)>0){ // IF VALID
			// UPDATE THE EXPIRATION DATE
			$ds = date('Y-m-d H:i:s', mktime(date('H')+3, date('i'), date('s'), date('m'), date('d'), date('Y')));
			$ds = strtotime($ds);
			$u = "UPDATE api_actives SET expires = '{$ds}' WHERE apiKey = '{$pageA[3]}' LIMIT 1";
			$uSQL = mysql_query($u);
			$responseContent = array (
				"status_message"=>1,
				"api_key"=>$pageA[3],
				"expires"=>$ds
			);
		}else{ // IF NOT VALID
			// DELETE PROVIDED API KEY IF THERE IS ONE
			$d = "DELETE FROM api_actives WHERE apiKey = '{$pageA[3]}' LIMIT 1";
			$dSQL = mysql_query($d);
			$responseContent = array (
				"status_message"=>2,
				"message"=>"api key has expired"
			);
		}
	}else{	
		$st = "401";
		$responseContent = array (
			"status_message"=>3,
			"message"=>"no variables passed"
		);		
	}
?>