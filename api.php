<?php	
	$DOMAIN_URL = "http://u.krow.me/";	
	$e = explode("//", $DOMAIN_URL, 2);
	$ROOT_PAGE = $e[1];

	function getRandomKey($length)
    {
        $default_arr = array("0123456789",
                             "abcdefghijklmnopqrstuvwxyz",
                             "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        $set = $default_arr[0].$default_arr[1].$default_arr[2];
        $random_string = "";
        for($i=0;$i<$length;$i++)
            $random_string .= $set[mt_rand(0,strlen($set)-1)];
        return $random_string;
    }
	
	if($_SERVER['REQUEST_METHOD'] === "POST")		// If url is requested
	{				
		$PARCEL = array("error" => "", "url" => "");
		$pat = "|^https?://(?:www\.)?(?:[\w-.]+)(?:/[/%&?=\w.-]+)?$|i";
		$no_js = isset($_POST['no-js'])? $_POST['no-js']: false;
		$custom = isset($_POST['custom'])? $_POST['custom'] : NULL;
		$url = isset($_POST['url']) && preg_match($pat, $_POST['url'])? htmlentities($_POST['url']): NULL;	
		if($url)
		{
			if(preg_match("|^https?://(www\.)?".$ROOT_PAGE."(?:[^?]*\?url=)?[\w]{4,}|", $_POST['url']))
			{
				$PARCEL['error'] =  "We do not allow redirection to redirection pages.";
			}
			else
			{
				$sqli = new mysqli("localhost", "nbaztec", "");
				if ($sqli->connect_error) 
					die('Connect Error ('.$sqli->connect_errno.') '.$sqli->connect_error);
				$sqli->select_db("test");
				
				$r = $sqli->query("SELECT id FROM `urls` WHERE url='$url'");			
				if($r->num_rows === 0)
				{
					$sqli->query("INSERT INTO `urls`(url) VALUES('$url')");				
						$id = $sqli->insert_id;
					if(!$r)
						die('Query Error ('.$sqli->connect_errno.') '.$sqli->connect_error);
				}
				else
				{
					$row = $r->fetch_assoc();				
					$id = $row['id'];
					$id = $row['id'];
				}
		
				// Insert into custom
				if($custom)
				{			
					if(preg_match("|[\w-]{4,}|", $custom))	
					{
						$sqli->query("INSERT INTO `mapping` VALUES('$custom', $id)");
						if($sqli->errno == 1062)
							$PARCEL['error'] = "Entry ".$custom." is already taken";
					}
					else
						$PARCEL['error'] = "Custom text must be atleast 4 characters long containing  only alphanumeric characters, dash and underscore.";
				}
				else
				{			
					do
					{
						$custom = getRandomKey(4);				
						$sqli->query("INSERT INTO `mapping` VALUES('$custom', $id)");
					}while($sqli->errno == 1062);
				}
				$PARCEL['url'] = $custom;
				$sqli->close();		
			}
		}
		else
		{
			$PARCEL['error'] = "Malformed URL";
		}
		if($no_js == "false")
			echo json_encode($PARCEL);		
		//print_r($PARCEL);
	}
	else if($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['key']))	// If redirection is requested
	{
		$PARCEL = array("error" => "", "url" => "");
		$custom = $_GET['key'];
				$sqli = new mysqli("localhost", "nbaztec", "");
		if ($sqli->connect_error) 
			die('Connect Error ('.$sqli->connect_errno.') '.$sqli->connect_error);
		$sqli->select_db("test");
		if($custom)
		{			
			$r = $sqli->query("SELECT url FROM `urls` AS u INNER JOIN `mapping` AS m ON u.id=m.id WHERE m.key='$custom'");
			if($sqli->errno === 0 && $r->num_rows)
			{
				$row = $r->fetch_assoc();
				$u = html_entity_decode($row['url']);
				$u = getTargetUrl($DOMAIN_URL, $u);			// Get final redirected URL/Redirection loop
				//echo "URL: ".$u;
				if($u)
				{
					header( "HTTP/1.1 301 Moved Permanently" ); 
					header("Location: ".$u);								
				}
				else
					die("Nice try with the infinite redirection. Here's a <a href='http://upload.wikimedia.org/wikipedia/commons/4/42/Cookie.gif'>cookie</a>.");
			}
			else
				die("No such url exists. Perhaps you wish to create one at <a href='$DOMAIN_URL'>u.krow.me</a>");
			//print_r($PARCEL);
		}		
	}	
	
	function getTargetUrl($domain, $url, $timeout_seconds=25)
	{
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $domain."drill-url.php"); 
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);		 
		curl_setopt($ch, CURLOPT_POSTFIELDS, "url=$url&seconds=$timeout_seconds");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$b = curl_exec($ch);
		$e = curl_errno($ch);
		curl_close($ch);
		if($e || empty($b))
			return NULL;
		else
			return $b;
	}
?>