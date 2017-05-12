<?php
$sLimesurveyFolder = realpath( dirname(__FILE__) . "../application");
define("BASEPATH", $sLimesurveyFolder);
$myX = (include '../application/config/config.php');
$stringConex = explode(";dbname=", $myX["components"]["db"]["connectionString"]);

$mysqli = new mysqli('localhost',$myX["components"]["db"]["username"],$myX["components"]["db"]["password"], substr($stringConex[1],0,-1));
	if ($mysqli->connect_errno):
		echo "Error al conectarse con MySQL debido al error".$mysqli->connect_error;
	endif;
?>