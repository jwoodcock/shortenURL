<?php
		
	/* FUNCTION RETURNS A UNIQUE HASH VALUE FOR INDEXING */
	function hashIn($d='',$pre=''){
		//get current date
		if (!$d){ $date = date("d--F--m--Y"); }else{ $date = $d; }
		$dateA = explode("--",$date);
		//start building 
		$seg1 = getSeg1($dateA[1]);
		$hash = $pre.$seg1.$dateA[2].$dateA[3];
		return uniqid($hash, true);
	}
	/* FUNCTION THAT CONVERTS MONTH INTO ALPHA  */	
	function getSeg1($va){
		$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$alpha = array("a","b","c","d","e","f","g","h","i","j","k","l");
		$location = array_search($va,$month);
		return $alpha[$location];
	}
	
	/* FUNCTION THAT CONVERTS NUMBER INTO A LETTER */	
	function getSeg2($va){
		$num = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41","42","43","44","45","46","47","48","49","50","51","52","53","54","55","56","57","58","59","60");
		$alpha = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","aa","bb","cc","dd","ee","ff","gg","hh","ii");
		$location = array_search($va,$num);
		return $alpha[$location];
	}	
	
	/* FUNCTION CHECKS TO SEE IF A URL IS VALID OR NOT */	
	function validURL($ur){
		$val = true;
		if (strpos($ur," ") != false){
			$val = false;
		}else if (substr($ur,0,7) != "http://" && substr($ur,0,8) != "https://"){
			$val = false;	
		}
		return $val;
	}
	
	/* FUNCTION CHECKS TO SEE IF A CLINT ID IS VALID OR NOT */	
	function validCl($ur){
		$val = true;
		if (strpos($ur," ") != false){
			$val = false;
		}
		return $val;
	}
	
	function shortURL(){
		/* return short url made of second(a)/Month(a)/Day(a)/hour(a)/Year/Last2ofIP
		exampl http://thekit.us/fdea1165 */
		$su = "";
		//get current date and time
		$date = date("s--F--d--h--Y--u"); 
		$enip = substr($_SERVER['REMOTE_ADDR'],-2); //remote ip
		$dateA = explode("--",$date);
		$su = getSeg2($dateA[0]).getSeg2($dateA[1]).$enip.getSeg2($dateA[2]).getSeg2($dateA[3]).getSeg2($dateA[4]).substr($dateA[5],-2);
		return $su;
	}
	
	/* FUNCTION TO GET A REMOTE PAGE TITLE */
	function getPageTitle($page){
		
		$fp = fopen($page,'r'); //open remote page
		$content = "";
		while( !feof( $fp ) ) {
			$buffer = trim( fgets( $fp, 4096 ) );
			$content .= $buffer;
    	}
    	$start = '<title>';
    	$end = '<\/title>';
		preg_match("/$start(.*)$end/s", $content, $match );
		$title = $match[ 1 ]; 
		if ($title == ""){
			$title = 'na';
		}
		return $title;
	}
	
	/* FUNCTION TO TEST IF URL IS CALLING API */ 
	function isAPI($url){
		$apis = array("clients","urls","hits","hits_by_cookie","hits_by_ipaddress","hits_by_date","hits_by_bots");
		if (in_array($url,$apis)){
			return true;
		}else{
			return false;
		}
	}
	
	/* FUNCTION TO USE CURL TO GET REMOTE DATA */
	function useCurPost($address,$fields,$fieldNames,$method='',$api=""){
		$ch = curl_init(); 
		curl_setopt($ch,CURLOPT_URL,$address);
		if ($method=='post'){ curl_setopt($ch, CURLOPT_POST,1); }
		$f = explode(",",$fields);
		$fn = explode(",",$fieldNames);
		$vars = "";
		for($a=0;$a<count($f);$a++){
			if ($vars!=""){ $vars .= "&"; }
			$vars .= $fn[$a]."=".$f[$a];
		}
		if ($method=='post'){curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 4); 
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if ($api){ curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/xml','User: '.$api)); }
		// EXECUTE
		$result = curl_exec($ch); 
		// CHECK FOR ERRORS
		if (curl_errno($ch)) {
			$result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
		} else {
			$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
			switch($returnCode){
				case 404:
					$result = 'ERROR -> 404 Not Found';
					break;
				default:
					break;
			}
		}
		// CLOSE CONNECTION
		curl_close($ch);
		
		return $result;
	}
	
	/* FUNCTION TO CLEAN STRING BEFORE ADDING TO XML */
	function cleanStringXML($string){
		$sRtn = str_replace("&", "&amp;",$string);
		$sRtn = str_replace("<", "&lt;",$sRtn);
		$sRtn = str_replace(">", "&gt;",$sRtn);
		$sRtn = str_replace('""', "&quot;",$sRtn);
		$sRtn = str_replace("'", "&apos;",$sRtn);
		
		return $sRtn;
	}
	/* FUNCTION THAT GETS SECRET KEY, PASSED VALUE AND TESTS THEM */
	function testSecretKey($k,$v){
		
	}
	/* FUNCTION THAT TAKES A PASSWORD AND SECRET KEY AND GENERATES A PROCEESED VALUE */
	function procSecretKey($k,$v){
		$nk = sha1($k.$v);
		return $nk;
	}
?>