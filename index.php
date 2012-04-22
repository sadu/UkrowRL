<?php
	include("api.php");
	//$ROOT_PAGE = "http://nbaztec.no-ip.org/nbaztec.co.in/bitcrypt/url-shortener/";
	$ROOT_PAGE = "http://u.krow.me/";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>You Krow &amp; ME</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
	<script type="text/javascript">var ROOT_PAGE = "<? echo $ROOT_PAGE; ?>";</script>    
    <script type="text/javascript" src="jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="main.js"></script>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="url-shortner">
	<p id="krow">Krow.url</p>
	<form action="index.php" method="post" class="form">
		<input id="url" name="url" type="text" value="<? echo isset($_POST['url'])? $_POST['url']: '*le url is the place where you input your shlang'; ?>" onkeydown="this.size = this.value.length+1;" size="48" class="url" /><br />
    	<label for="custom">Custom URL: </label><span class="custom-span"><? echo $ROOT_PAGE; ?></span>
        <input id="custom" name="custom" type="text" value="<? echo isset($_POST['url'])? $_POST['custom']: ''; ?>" onkeydown="" size="10" class="custom" />
		<input name=":O" type="submit" value="krow" class="submit" />
		<input id="no-js" name="no-js" type="hidden" value="true" />
    </form>
    <div id="status">
<?php
	if(isset($PARCEL))
	{
		if($PARCEL['error'] == "")
		{
	?>
			<div class="success">Your short url is: <a href="<? echo $ROOT_PAGE; ?>index.php?key=<? echo urlencode($PARCEL['url']); ?>" class="link" ><? echo $ROOT_PAGE; ?>index.php?key=<?php echo $PARCEL['url'];  ?></a></div>
	<?php
        }
        else
        {
    ?>
            <div class="fail">An error occured: <?php echo $PARCEL['error'];  ?></div>
    <?php
        }	
	}
?>
	</div>
	<div class="back"><a href="<? echo $ROOT_PAGE; ?>" class="link">Back</a></div>
</div>
</body>
</html>