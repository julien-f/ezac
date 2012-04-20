<?php

// $user="";
// $password="";
// $database="";
// $sql_server="localhost";

$db_link = mysql_connect($sql_server, $user, $password) or die("Impossible de se connecter a la base");
mysql_select_db($database, $db_link) or die( "Unable to select database");
