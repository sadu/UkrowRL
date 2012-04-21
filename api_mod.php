<?php
	//error_reporting(-1);
	require("mysql.php");
	$url = $_POST['url'];
	$pass = $_POST['pass'];
	$custom = isset($_POST['custom'])? preg_replace("/[\W]/", "-", $_POST['custom']): NULL;
	$nojs = isset($_POST['nojs'])? true : false;
	$hash = file('pass.hash');
	$hash = substr($hash[0], 0, -1);

	$isAuth = ( md5($hash) == md5($pass) );

	if($url != NULL){		
		if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url) && $isAuth) {
			if($custom == NULL){
				do{
					$custom = getRandomKey(4);
					mysql_query("INSERT INTO `urls` VALUES('$custom', '$url')");
				}while(strpos(mysql_error(), "Duplicate entry") === TRUE);
			}
			else{				
				mysql_query('INSERT INTO urls (id, url) VALUES ("'.$custom.'","'.htmlentities($url).'")') or die(mysql_error());
			}
			$id = $custom;
			if($nojs == true)
				header("Location: http://u.krow.me/?u=$id");
			else
				echo json_encode(array('message' => "http://u.krow.me/$id", 'type' => 'success'), true);
				
		} else if(!$isAuth) {
			echo json_encode(array('message' => 'Error: wrong passphrase !', 'type' => 'error'), true);
		}
		else {
			echo json_encode(array('message' => 'Error: invalid request !', 'type' => 'error'), true);
		}
	}else if($_GET['id']){
		$id = htmlentities($_GET['id']);
		$getUrl = mysql_query("SELECT url FROM urls WHERE id = '$id'");
		
		if(mysql_num_rows($getUrl)) {
			$url = mysql_fetch_array($getUrl);
			$url = html_entity_decode($url['url']);
			if($isAuth)
				echo json_encode(array('message' => $url, 'type' => 'success'), true);
			else{
				 if(empty($url))
					echo "The link is empty.";
				 else if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == $_SERVER['PHP_SELF'])
					 echo "Nice try with the redirection loop. ;)";					 
				 else
					header("Location: $url");
			}
		} else {
			echo "invalid link :(";
		}
	}
	
	function getRandomKey($len){
		return substr(md5(time()),-len);
	}
?>