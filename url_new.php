<?php 
	if ($_SERVER['HTTP_HOST'] === 'thekit.us' && $_POST['cl'] && $_POST['url'] || $_SERVER['HTTP_HOST'] === 'www.thekit.us' && $_POST['cl'] && $_POST['url']){
		include("includes/functions.php");
		$address = "http://www.thekit.us/api/urls/"; // address for API
		$result = useCurPost($address,$_POST['cl'].",".$_POST['url'],"cl,url","post"); // use curl function to send data and get result
		//PROCESS THE RESULT
		$dom = new SimpleXMLElement($result);
		// XML Structure 
		// status_code
		// data
			// status_message
			// url
			// hash
		$urls = $dom->xpath('//url_details');
		foreach ($urls as $url){
			if ($dom->{'status_code'} == "200" || $dom->{'status_code'} == "201" ){ // first check status code to make sure it's not an error
				//now output url status message and url and hash
				echo $url->status_message."-".$url->url."-".$url->hash;
			}else{
				echo "4-".$url->status_code;
			}
		}
		//script is done
	}else{
		echo "4-";
	}
	
?>