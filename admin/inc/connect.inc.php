<?php
$sql_host = "localhost";
$sql_db   = "jbaraona_web";
$sql_user = "jbaraona_web";
$sql_pass = "jbaraona_web";
$pre  = "web_";
$sql_conn = mysql_connect($sql_host, $sql_user, $sql_pass) or die (mysql_error());
mysql_select_db($sql_db, $sql_conn) or die (mysql_error());
?>