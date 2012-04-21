<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>You Krow &amp; ME</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="main.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="noJS">
	<p id="krow">Krow.url</p>
<?php
	if(!isset($_GET['u'])){
?>
	<form action="api.php" method="post">
		<input id="url" name="url" type="text" value="*le url is the place where you input your shlang" onkeydown="this.size = this.value.length + 10;" size="50" /><br />
		<input id="pass" name="pass" type="password" value="password" size="10" /><br />
		<input id="submit" name=":O" type="submit" value="krow" />
		<input name="nojs" type="hidden" value="true" /></form>
<?php
	} else {
?>
	<div id="url">Your short url is: <a href="http://u.krow.me/<?php echo $_GET['u']; ?>" class="link" >http://u.krow.me/<?php echo $_GET['u']; ?></a>
	</div>
	<div><a href="http://u.krow.me/" class="link">back</a>
	</div>
<?php
	}
?>
</div>
<div id="yesJS">
</div>
</body>

</html>