<?php
	//error_reporting(-1);
	require("mysql.php");
	$url = $_POST['url'];
	$pass = $_POST['pass'];
	$nojs = isset($_POST['nojs'])? true : false;
	$hash = file('pass.hash');
	$hash = substr($hash[0], 0, -1);

	$isAuth = ( md5($hash) == md5($pass) );

	if($url != NULL){
		if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url) && $isAuth) {
			mysql_query('INSERT INTO urls (url) VALUES ("'.htmlentities($url).'")') or die(mysql_error());
			$id = mysql_insert_id();
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
		$getUrl = mysql_query("SELECT url FROM urls WHERE id = $id");

		if(mysql_num_rows($getUrl)) {
			$url = mysql_fetch_array($getUrl);
			$url = html_entity_decode($url['url']);
			if($isAuth)
				echo json_encode(array('message' => $url, 'type' => 'success'), true);
			else
				header("Location: $url");
		} else {
			echo "invalid link :(";
		}
	}
?>