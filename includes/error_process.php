<?php 
	if ($error == 1){
		echo "<div style='width:800px;margin:0px auto;'><a href='http://www.kitportal.com/'><img src='http://kitportal.com/images/top_logo.png' border='0' align='right' /></a><h1>Bad URL</h1><p>You have reached a bad url.<br />Please inform the person you received this link from that they have an incorrect url.</p><p>Thanks you,<br />theKit<br /><a href='http://www.kitportal.com/'>www.kitportal.com</a></p></div>";
		exit();
	}else{	
		echo "<html><head><meta http-equiv='REFRESH' content='0;url=http://www.the-domain-you-want-to-redirect-to.com'><META NAME='ROBOTS' CONTENT='NOINDEX, NOFOLLOW'>
</head><body></body></html>";
	}
?>