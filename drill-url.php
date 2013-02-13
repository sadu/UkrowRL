<?php
	$response = array("error" => "", "url" => "");
	if(isset($_POST['url']))
	{		
		$u = $_POST['url'];
		$max_seconds = isset($_POST['seconds'])?$_POST['seconds']:20;
		if(preg_match("|^https?://([^/]+)([^#]*)(?:#.*$)?|", $u))
		{
			$loc = array();
			$redirect = false;
			do
			{
				$loc[] = $u;			
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,            $u); 
				curl_setopt($ch, CURLOPT_HEADER,         true); 
				curl_setopt($ch, CURLOPT_NOBODY,         true); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($ch, CURLOPT_TIMEOUT,        $max_seconds); 		
				$b = curl_exec($ch);
				$e = curl_errno($ch);
				curl_close($ch);
				if($e)
					$response['error'] = "cURL error";
				else
				{
					$h =  parse_headers($b);
					if(($redirect = ($h['status'] == 301 || $h['status'] == 302)))
						$u = $h['location'];
				}				
			}while($redirect && !in_array($u, $loc));
			if($redirect)
				$response['error'] = "Redirection loop";
			else
				$response['url'] = $u;
			echo $response['url'];		// Might as well print URL here
		}
		else
			$response['error'] = "Malformed URL";
	}
	else
		$response['error'] = "Specify a URL";
		
	//debug
	//print_r($response);

	function parse_headers($h)
	{
		if(preg_match("|^(HTTP/\d.\d) (\d+) ([^\r\n]*).*?Location: ([^\r\n]*)|si", $h, $match))
			return array("mode" => $match[1], "status" => (int)$match[2], "message" => $match[3], "location" => $match[4]);
		else
			return NULL;
	}	
?>