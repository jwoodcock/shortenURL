<?php 
	
	global $predata;
	
	function contents($parser, $data){
		global $predata;
		$predata[] = $data;
	}
	
	function startTag($parser, $data){
		echo "";
	}
	
	function endTag($parser, $data){
		echo "";
	}
	
	$key = 'a705946e2dcd1f7769af75e11fa8fab24f98949b4afd02a60a0cb07ce2700cdb';
	$url = "http://api.ipinfodb.com/v3/ip-city.php?key=".$key."&ip=".$ip;
	$result = useCurPost($url,'','',$method='',$api="true");
	$array = explode(";",$result);
	/*$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "startTag", "endTag");
	xml_set_character_data_handler($xml_parser, "contents");
	$fp = fopen($file, "r");
	$data = fread($fp, 80000);
	if(!(xml_parse($xml_parser, $data, feof($fp)))){
		die("Error on line " . xml_get_current_line_number($xml_parser));
	}

	xml_parser_free($xml_parser);
	fclose($fp);

	$iplookup['ip']=$predata[1];
	$iplookup['code']=$predata[3];
	$iplookup['country']=$predata[5];
	$iplookup['flag']=$predata[7];
	$iplookup['city']=$predata[9];
	$iplookup['region']=$predata[11];
	$iplookup['isp']=$predata[13];
	$iplookup['latitude']=$predata[15];
	$iplookup['longitude']=$predata[17];*/
	
	// service is down. need to reprogram. 
	
	$iplookup['ip']=$ip;
	$iplookup['code']=$array[3];
	$iplookup['country']=$array[4];
	$iplookup['flag']="";
	$iplookup['city']=$array[6];
	$iplookup['region']=$array[5];
	$iplookup['zip']=$array[7];
	$iplookup['isp']="";
	$iplookup['latitude']=$array[8];
	$iplookup['longitude']=$array[9];
?>