
<head>
<meta http-equiv="Content-Language" content="es-mx">
<title>Usuarios registrados</title>
<script type="text/javascript" src="javascript.js"></script>

<STYLE>
<!--
.menu A:link    {color:BLACK; text-decoration:none;}
.menu A:visited {color:BLACK; text-decoration:none;}
.menu A:active  {color:BLACK; text-decoration:none;}
.menu A:hover   {	padding-bottom: 1px;
					border-bottom: 2px solid #0000ff;
					background: transparent;
					color: #0000ff;
					text-decoration: none; }

.normal A:link		{color:BLACK; text-decoration:none;}
.normal A:visited	{color:BLACK; text-decoration:none;}
.normal A:active	{color:BLACK; text-decoration:none;}
.normal A:hover		{color:BLACK; text-decoration:underline;}

A:link		{color:BLACK; text-decoration:none;}
A:visited	{color:BLACK; text-decoration:none;}
A:active	{color:BLACK; text-decoration:none;}
A:hover	{color:BLUE; text-decoration:underline;}

td {
	font-family: Verdana, Geneva, sans-serif;
	font-size:12px;
}
-->
</STYLE>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script type="text/JavaScript">
<!--
function timedRefresh(timeoutPeriod) {
	setTimeout("location.reload(true);",timeoutPeriod);
}
//   -->
</script>

</head>

<!-- <body onLoad="JavaScript:timedRefresh(300000);"> -->
<body>

<p align="center"><img src="http://www.conricyt.mx/conremoto/auth/images/header-summon-background-01.png" width="858" height="200" alt="Conricyt">
		

<?PHP
if (isset($_POST['sort']))
{
          $sort = $_POST["sort"];
}

date_default_timezone_set('Mexico_City');

$link = mysql_connect('localhost','conricyt_4r3m070','3zpr0x1blu3')
    or die('Imposible conectar al servidor: ' . mysql_error());

mysql_select_db('conricyt_proxy') or die('Imposible seleccionar la base de datos.' . mysql_error());

$query = "SELECT COUNT(DISTINCT institution) FROM users";
$result = mysql_query($query) or die('Fallo al contar registros: ' . mysql_error());
$institutions = mysql_fetch_row($result);						

$fecha=date('j \- F \- Y');
print"<br><div align='center'><font face='Verdana' size='3'>USUARIOS REGISTRADOS AL $fecha</font></div><br>
<div align='center'>
<table border='1' cellpadding='3'>
  <tr>
    <td valign='top'>
		<table border='0' id='table1' cellspacing='5' cellpadding='0'>
			<tr>
				<td valign='top'><b>INSTITUCIÓN</b></td>
				<td>&nbsp;</td>
				<td>
					<form ACTION='index.php' name='insta' METHOD='post'>
					<input type='hidden' value='ORDER BY inst_name' name='sort'>
					<a href='javascript:document.insta.submit()'>
					<img border=0 src='sort_ascending.png'>
					</form>
				</td>
				<td>
					<form ACTION='index.php' name='instd' METHOD='post'>
					<input type='hidden' value='ORDER BY inst_name DESC' name='sort'>
					<a href='javascript:document.instd.submit()'>
					<img border=0 src='sort_descending.png'>
					</form>
				</td>
			</tr>
		</table>
	</td>
	<td valign='top' align='center'><b>CA</b></td>
	<td valign='top' align)'center'><b>CD</b></td>
    <td align='center' valign='top'>
	<table border='0' id='table1' cellspacing='5' cellpadding='0'>
			<tr>
				<td valign='top'><b>Registros</b></td>
				<td>&nbsp;</td>
				<td>
					<form ACTION='index.php' name='instasc' METHOD='post'>
					<input type='hidden' value='ORDER BY usr_total' name='sort'>
					<a href='javascript:document.instasc.submit()'>
					<img border=0 src='sort_ascending.png'>
					</form>
				</td>
				<td>
					<form ACTION='index.php' name='instdes' METHOD='post'>
					<input type='hidden' value='ORDER BY usr_total DESC' name='sort'>
					<a href='javascript:document.instdes.submit()'>
					<img border=0 src='sort_descending.png'>
					</form>
				</td>
			</tr>
		</table>
	</td>
    <td align='center' valign='top'>Técnico</td>
    <td align='center' valign='top'>Estudiante de<br />Especialidad</td>
    <td align='center' valign='top'>Estudiante de<br />Licenciatura</td>
    <td align='center' valign='top'>Estudiante de<br />Maestría</td>
    <td align='center' valign='top'>Estudiante de<br />Doctorado</td>
    <td align='center' valign='top'>Académico</td>
    <td align='center' valign='top'>Bibliotecario</td>
    <td align='center' valign='top'>Investigador</td>
    <td align='center' valign='top'>Referencista</td>
    <td align='center' valign='top'>Otro</td>
  </tr>";

$total=0;
$consulta = "SELECT inst_name, institution AS ins_total, COUNT(institution) AS usr_total FROM inst, users WHERE inst.id = institution GROUP BY institution $sort";
//$consulta = "SELECT inst_name, institution AS ins_total, COUNT(institution) AS usr_total FROM inst, users WHERE inst.id = institution AND inst.status=1 GROUP BY institution ORDER BY usr_total";
$resulta = mysql_query($consulta) or die('Error: ' . mysql_error());
while ($inst = mysql_fetch_row($resulta)) {

print"
  <tr>
	<td><font face='Verdana' size='2'>$inst[0]</font></td>";

$cons = "SELECT count(used) FROM preset_users WHERE institution=$inst[1] AND used=1;";
$res = mysql_query($cons) or die('Error: ' . mysql_error());
$cuentas = mysql_fetch_row($res);
print"	<td align='center'>$cuentas[0]</td>";

$cons = "SELECT count(used) FROM preset_users WHERE institution=$inst[1] AND used=0;";
$res = mysql_query($cons) or die('Error: ' . mysql_error());
$cuentas = mysql_fetch_row($res);
if ($cuentas[0] <= 20) {print"	<td align='center' bgcolor='#FF0000'><font color = 'ffffff'><b>$cuentas[0]</b></font></td>";}else{print"	<td align='center'>$cuentas[0]</td>";}

print"
	<td align='center'><font face='Verdana' size='2'>$inst[2]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 1";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level1 = $level1+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 2";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level2 = $level2+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 3";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level3 = $level3+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 4";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level4 = $level4+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 9";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level9 = $level9+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 5";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level5 = $level5+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 6";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level6 = $level6+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 7";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level7 = $level7+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 8";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level8 = $level8+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

$query = "SELECT count(*) FROM users WHERE institution = '$inst[1]' AND level = 10";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$level = mysql_fetch_row($result);
$level10 = $level10+$level[0];
if ($level[0] == 0) $level[0] = "";
print"<td align='center'><font face='Verdana' size='2'>$level[0]</font></td>";

	
print"	
  </tr>";
  
$total = $total+$inst[2];	
}

print"
  <tr>
    <td align='right' colspan='3'><b>TOTALES:</b></td>
	<td align='center'><b>$total</b></td>
    <td align='center'><b>$level1</b></td>
	<td align='center'><b>$level2</b></td>
	<td align='center'><b>$level3</b></td>
	<td align='center'><b>$level4</b></td>
	<td align='center'><b>$level9</b></td>
	<td align='center'><b>$level5</b></td>
	<td align='center'><b>$level6</b></td>
	<td align='center'><b>$level7</b></td>
	<td align='center'><b>$level8</b></td>
	<td align='center'><b>$level10</b></td>
  </tr>
  <tr>
    <td valign='top'  colspan='3'>
	<b>INSTITUCIÓN</b>
	</td>
    <td align='center' valign='top'>
	<b>Registros</b>
	</td>
    <td align='center' valign='top'>Técnico</td>
    <td align='center' valign='top'>Estudiante de<br />Especialidad</td>
    <td align='center' valign='top'>Estudiante de<br />Licenciatura</td>
    <td align='center' valign='top'>Estudiante de<br />Maestría</td>
    <td align='center' valign='top'>Estudiante de<br />Doctorado</td>
    <td align='center' valign='top'>Académico</td>
    <td align='center' valign='top'>Bibliotecario</td>
    <td align='center' valign='top'>Investigador</td>
    <td align='center' valign='top'>Referencista</td>
    <td align='center' valign='top'>Otro</td>
  </tr>";

print"
</table></div>
<br><div align='center'>
<font face='Verdana' size='2'>$total registros procesados de $institutions[0] instituciones distintas.</font><br>";

$query = "SELECT count(*) FROM users WHERE preset_user_id !=''";
$result = mysql_query($query) or die('Fallo al contar rol: ' . mysql_error());	
$approved = mysql_fetch_row($result);

print"
<font face='Verdana' size='2'>Número de usuarios aprobados para hacer uso del servicio: $approved[0].</font>
</div><br>";

include("pChart/pData.class");
include("pChart/pChart.class");

 // Dataset definition 
 $DataSet = new pData;
 $DataSet->AddPoint(array($level1,$level2,$level3,$level4,$level9,$level5,$level6,$level7,$level8,$level10),"Serie1");
 $DataSet->AddPoint(array("Técnico: $level1","Estudiante de Especialidad: $level2","Estudiante de Licenciatura: $level3","Estudiante de Maestría: $level4","Estudiante de Doctorado: $level9","Académico: $level5","Bibliotecario: $level6","Investigador: $level7","Referencista: $level8","Otro: $level10"),"Serie2");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie("Serie2");

 // Initialise the graph
 $Test = new pChart(768,280);
 $Test->drawFilledRoundedRectangle(7,7,478,258,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,480,260,5,230,230,230);
 $Test->createColorGradientPalette(195,204,56,223,110,41,5);
 $Test->setColorPalette(0,0,163,221); 
 $Test->setColorPalette(1,255,0,120);
 $Test->setColorPalette(2,160,48,51); 
 $Test->setColorPalette(3,255,255,0);
 $Test->setColorPalette(4,0,183,96);
 $Test->setColorPalette(5,91,2,122);
 $Test->setColorPalette(6,128,255,0);
 $Test->setColorPalette(7,255,128,0);
 $Test->setColorPalette(8,0,255,128);
 $Test->setColorPalette(9,0,128,255);
  

 // Draw the pie chart
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 $Test->AntialiasQuality = 0;
 $Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),220,120,150,PIE_PERCENTAGE,TRUE,50,20,5);
 $Test->drawPieLegend(440,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);

 // Write the title
 $Test->setFontProperties("Fonts/MankSans.ttf",10);
 $Test->drawTitle(10,20,"Usuarios registrados",0,0,0);

 $Test->Render("usuarios.png");
 echo("<div align='center'><img src='usuarios.png?");
 echo rand(1,65536);
 echo("'></div><br><br><br><br>");

?>

</body>
