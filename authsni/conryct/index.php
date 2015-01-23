<?php
require_once('config.php');
require_once('header.php');


$db = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Could not connect: ' . mysql_error());


mysql_select_db(DB_NAME);
 $thisip = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
 echo $thisip;




// Performing SQL query

$groupsql = "SELECT inst.group FROM ips LEFT JOIN inst on ips.inst_ip = inst.id WHERE " . $thisip . " >= start_ip AND " . $thisip . " <= end_ip LIMIT 1;" ;

$groupresult = mysql_query($groupsql) or die('Query failed: ' . mysql_error());

$groupdata = mysql_fetch_assoc($groupresult);


if ($groupdata['group'] == 1){
header("Location: http://conricyt1.summon.serialssolutions.com");
}
elseif ($groupdata['group'] == 2) {
//	echo $groupsql;
	
	//echo $groupresult;
	//echo ("<SCRIPT LANGUAGE='JavaScript'>

      //  window.location.href='http://conricyt2.summon.serialssolutions.com'
		
		//window.onload=alert('Su IP esta registrado con CONRICYT. Su IP es '+ip);   ;
		
        
  		
header("Location: http://conricyt2.summon.serialssolutions.com") ;
}
elseif ($groupdata['group'] == 3){
header("Location: http://conricyt3.summon.serialssolutions.com");
}
elseif ($groupdata['group'] == 4){
header("Location: http://conricyt4.summon.serialssolutions.com");
}
elseif ($groupdata['group'] == 5){
header("Location: http://conricyt5.summon.serialssolutions.com");
} else {
	//	echo $groupsql;
	
	//echo $groupresult;
	
	echo ("<script type='text/javascript' language='JavaScript'>

        window.location.href='http://etechwebsite.com/conricyt/conricyt-select/remote.php'
		
		window.onload=alert('Su IP no esta registrado con CONRICYT.  Por favor haga clic para continuar y sera llevado a la pagina de acceso remoto . Su IP es '+ip);
        
        </script>");


}

?>
</body>
</html>