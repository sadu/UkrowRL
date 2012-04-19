<?php
        require("mysql.php");
        $url = $_GET['url'];
        $pass = $_POST['pass'];

        if($url != NULL){
                if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) {
                        mysql_query("INSERT INTO urls (url) VALUES ('".htmlentities($url)."')") or die(mysql_error());
                        $id = mysql_insert_id();
                        echo "http://u.krow.me/$id";
                } else {
                        echo "error messages goes here";
                }
        }else if($_GET['id']){
                $id = htmlentities($_GET['id']);
                $getUrl = mysql_query("SELECT url FROM urls WHERE id = $id");

                if(mysql_num_rows($getUrl)) {
                        $url = mysql_fetch_array($getUrl);
                        $url = html_entity_decode($url['url']);
                        header("location: ".$url);
                } else {
                        echo "error goes here";
                }
        }
?>
