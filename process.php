<?php 
	
	$ips = array(''); // Define what IP address can create new files
	$shortenerURL = ''; // The address of your shortener
	
	if (in_array($_SERVER['REMOTE_ADDR'],$ips)){
 		error_reporting(-1);
 		$cliID = 'cl_a0120114d2f6ed95f33d6.62251711';
	 }else{
		error_reporting(0); 
		//error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$cliID = '0';
	 }

	/* ------------------------------------------------------------------------------------------ */
	/* Script takes a short url and finds the real up, updates stats and sends to the other page. */
	/* ------------------------------------------------------------------------------------------ */

	//INCLUDE FUNCTIONS
	require_once(""); // DB Connection
	require_once("includes/functions.php");
	$error = 0; //error status
	
	// FIRST GET THE FOLDER, IE: shortURL, and IP ADDRESS
	$page = urldecode($_SERVER["REQUEST_URI"]);
	$ip = $_SERVER['REMOTE_ADDR'];
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$referer = getenv("HTTP_REFERER");
	
	// NOW CONVERT PAGE INTO ARRAY BASED ON / AFTER REMOVING THE HTTP://
	$page = str_replace("http://","",$page);
	$page = str_replace("https://","",$page);
	$page = str_replace(" ", "",$page); 
	$pageA = explode("/",$page); // array for short url or processing api
	
	// IF NO PAGE, SEND HOME
	if (!$page && in_array($_SERVER['REMOTE_ADDR'],$ips) === false && $_SERVER['HTTP_REFERER'] === $shortenerURL){
		header('location: http://www.kitportal.com');
		exit();
	}
	
	// log request
	$postIn = "";
	foreach($_POST as $pkey => $pvalue){
		if ($postIn){ $postIn .= ", "; }
		$postIn .= $pkey."=".$pvalue;
	}
	$getIn = "";
	foreach($_GET as $gkey => $gvalue){
		if ($getIn){ $getIn .= ", "; }
		$postIn .= $gkey."=".$gvalue;
	}
	
	// MAKE SURE $page VARIABLE IS NOT A SQL STATEMENT BY CHECKING THE LENGTH, NONE SHOULD BE OVER 10 CHARACTERS
	if ($pageA[1]!='api' && $page != "/"){ //PROCESS SHORT API
		$page = str_replace("/","",$page);
		if (strlen($page) > 10){
			$error = 1;
		}
		if ($error == 0){ require_once("includes/url_short_process.php"); }
	}else if ($pageA[1]=='api'){ //PROCESS API REQUEST
		require_once("includes/api_process.php");
	}else if (in_array($_SERVER['REMOTE_ADDR'],$ips) === true){
		require_once("includes/url_new.php");
	}else{
		$error=1;
	}
	
	//log response
	if ($loggedId){
		$logup = "UPDATE logging SET response = '{$xml}' WHERE logId = {$loggedId} LIMIT 1";
		$logupSQL = mysql_query($logup);
	}
		    
	if ($error > 0){
		// PROCESS ERRORS INCASE THERE ARE ANY
		require_once("includes/error_process.php");
	}
?>
