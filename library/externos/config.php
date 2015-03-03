<?php
$servername = "ns8161.hostgator.com";
//$username = "infosurvey";
//$password = "klDd893qjm!@";

$username = "activa11_sistema";
$password = "sansolucionlurbana11";

$hostname = "192.185.21.196";
$dbname = "activa11_sistema_bck";

//These variable values need to be changed by you before deploying

//Connecting to your database
mysql_connect($hostname, $username, $password) OR DIE ("Unable to
connect to database! Please try again later.");
mysql_select_db($dbname);