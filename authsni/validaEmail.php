<?php

$host = "localhost";
$usuario = "conricyt_4r3m070";//Cambiar el user por favor
$contrasena = "3zpr0x1blu3";//Cambiar el password por favor
$base = "conricyt_proxy"; //Cambiar el nombre de la base por favor

$conexion = mysql_connect($host,$usuario,$contrasena);
mysql_select_db($base,$conexion) or die('{"data":"error conexion"}');

if($_REQUEST["email1"]!=''){
	$query0	= "SELECT * FROM users WHERE md5(inst_email)='".MD5($_REQUEST["email1"])."' OR md5(comm_email)='".MD5($_REQUEST["email1"])."'";
	
	$result = mysql_query($query0,$conexion) or die('{"data":"error consulta"}');
	
	$num = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$num++;
	}
	
	if($num==0){
	
		if($_REQUEST["email2"]!=''){
			$query0	= "SELECT * FROM users WHERE md5(inst_email)='".MD5($_REQUEST["email2"])."' OR md5(comm_email)='".MD5($_REQUEST["email2"])."'";
		
			$result = mysql_query($query0,$conexion) or die('{"data":"error consulta"}');
			
			$num = 0;
			while ($row = mysql_fetch_assoc($result)) {
				$num++;
			}
			
			if($num==0){
				die("rep0");
			}else{
				die("rep2");
			}
		}else{
			die("rep0");		
		}
	}else{
		die("rep1");
	}
}else{
	die("rep0");
}

?>