<?php 
	
	echo '<html>
		<head>
			<title>MyPass: URL Shortener with Tracking</title>
			<style type="text/css" media="all">
				@import "style.css";
			</style>
			<script language="javascript" src="js/surl.js"></script>
		</head>
		<body>
			<div class="greenHolder">
				<input type="textfield" name="furl" id="furl" class="bigText" /> 
				<input type="button" value="SHORTEN" class="butTan" onmousedown="procFurl(\''.$cliID.'\');" />
				<div id="urRef"></div>
				<div id="urEr"></div>
			</div>
			</body>
		</html>';
		//}else if ($_POST == true && $_SERVER['HTTP_REFERER'] == "http://www.thekit.us/process.php?newurl=true" || $_POST == true && $_SERVER['HTTP_REFERER'] == "http://thekit.us/process.php?newurl=true"){
?>