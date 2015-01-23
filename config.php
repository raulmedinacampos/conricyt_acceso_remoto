<?php
	$host	=	'127.0.0.1';
	$user	=	'root';
	$pass	=	'';
	$db		=	'conricyt_proxy';
	
	$link = mysql_connect($host, $user, $pass) or die('Imposible conectar al servidor: ' . mysql_error());

	mysql_select_db($db) or die('Imposible seleccionar la base de datos.' . mysql_error());
?>
