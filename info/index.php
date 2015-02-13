<head>
<meta http-equiv="Content-Language" content="es-mx">
<title>Localizador de usuarios registrados</title>

<script LANGUAGE="JavaScript">

function validateForm()
{

var firstname=document.forms["findme"]["firstname"].value;
var lastname=document.forms["findme"]["lastname"].value;

if (firstname=="" && lastname=="")
  {
  alert("Debes llenar al menos uno de los dos campos de búsqueda.");
  return false;
  }

}


function confirmSend() 
{
var agree=confirm("¿Reenviar credenciales a esta dirección?");
if (agree)
	return true ;
else
	return false ;
}

</script>

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
</head>

<body>
<p align="center"><img src="http://www.conricyt.mx/conremoto/auth/images/header-summon-background-01.png" width="858" height="200" alt="Conricyt">
		

<?PHP

$invalidos = array("%", "'", "_", "\\", "\"");

if (isset($_POST['firstname']))
{
          $firstname = $_POST["firstname"];
		  $firstname = str_replace($invalidos, "", "$firstname");
}

if (isset($_POST['lastname']))
{
          $lastname = $_POST["lastname"];
		  $lastname = str_replace($invalidos, "", "$lastname");
}

if (isset($_POST['queryme']))
{
          $queryme = $_POST["queryme"];
}

date_default_timezone_set('America/Mexico_City');

$link = mysql_connect('127.0.0.1','conricyt_4r3m070','3zpr0x1blu3')
    or die('Imposible conectar al servidor: ' . mysql_error());

mysql_select_db('conricyt_proxy') or die('Imposible seleccionar la base de datos.' . mysql_error());

print"
<div align='center'>
<form method='POST' action='index.php' name='findme' id='findme' onsubmit='return validateForm()'>
	<p><font face='Verdana'>Localizar usuario registrado:</font></p>
	<table border='0' id='table1' cellspacing='3' cellpadding='2'>
		<tr>
			<td><font face='Verdana' size='2'>Nombre(s):</font></td>
			<td><font face='Verdana'>
			<input type='text' name='firstname' size='40' value='".$firstname."' onfocus=\"this.value=''\"></font></td>
		</tr>
		<tr>
			<td><font face='Verdana' size='2'>Apellido(s):</font></td>
			<td><font face='Verdana'>
			<input type='text' name='lastname' size='40' value='".$lastname."' onfocus=\"this.value=''\"></font></td>
		</tr>
	</table>
	<p><font face='Verdana'>
	<input type='hidden' name='queryme' value='1'>
	<input type='submit' value='Enviar consulta' name='SendButton'></font></p>
</form>
</div>
";

if ($queryme == 1) {

$query = "SELECT count(*) FROM users WHERE firstname LIKE '%$firstname%' AND (lastname1 LIKE '%$lastname%' OR lastname2 LIKE '%$lastname%');";
$result = mysql_query($query) or die('Fallo al recuperar listado de usuarios: ' . mysql_error());
$conteo = mysql_fetch_row($result);

print"
<div align='center'>
<p><font face='Verdana'>Usuarios localizados: $conteo[0]</font></p>
<table border='1' id='usertable' cellspacing='4' cellpadding='3' style='border-collapse: collapse'>
	<tr>
		<td align='center'><b>ID</b></td>
		<td align='center'><b>Apellido(s)</b></td>
		<td align='center'><b>Nombre(s)</b></td>
		<td align='center'><b>Institución</b></td>
		<td align='center'><b>Correo institucional</b></td>
		<td align='center'><b>Correo comercial</b></td>
		<td align='center'><b>Status</b></td>
		<td align='center'><b>Fecha de registro</b></td>
		<td align='center'><b>Fecha de aprobación</b></td>
		<td align='center'><b>Usuario</b></td>
		<td align='center'><b>Contraseña</b></td>
	</tr>";

$query = "SELECT id, firstname, lastname1, lastname2, institution, inst_email, comm_email, preset_user_id, active, rejected, suspended,fecha_reg,fecha_apr FROM users WHERE firstname LIKE '%$firstname%' AND (lastname1 LIKE '%$lastname%' OR lastname2 LIKE '%$lastname%') ORDER BY lastname1, lastname2;";
$result = mysql_query($query) or die('Fallo al recuperar listado de usuarios: ' . mysql_error());
while ($user = mysql_fetch_row($result))
	{
	print"
		<tr>
		<td>$user[0]</td>
		<td>$user[2] $user[3]</td>
		<td>$user[1]</td>
		";
$query2 = "SELECT * FROM inst WHERE id='$user[4]'";
$resulta = mysql_query($query2) or die('Fallo al recuperar institución: ' . mysql_error());
$row3 = mysql_fetch_row($resulta);

print"	<td>$row3[1]</td>";

if ($user[7] != "") {
	$query3 = "SELECT * FROM preset_users WHERE id=$user[7]";
	$results = mysql_query($query3) or die('Fallo al recuperar claves de acceso: ' . mysql_error());
	$row6 = mysql_fetch_row($results);}

if ($user[8] == 1) {
print"
<td><form ACTION='sendmail.php' name='sendinst$user[0]' METHOD='post' target='sendmail'>
<input type='hidden' value='$user[1]' name='firstname'>
<input type='hidden' value='$user[2] $user[3]' name='lastname'>
<input type='hidden' value='$row3[1]' name='inst_name'>
<input type='hidden' value='$row6[1]' name='username'>
<input type='hidden' value='$row6[2]' name='password'>
<input type='hidden' value='$user[5]' name='inst_email'>
<input type='hidden' value='$user[12]' name='fecha_apr'>
<a href='javascript:document.sendinst$user[0].submit();'>$user[5]</a>
</form></td>";}else{
print"<td>$user[5]</td>";}

if ($user[6] != "")
{
	if ($user[8] == 1) {
	print"
	<td><form ACTION='sendmail.php' name='sendcomm$user[0]' METHOD='post' target='sendmail'>
	<input type='hidden' value='$user[1]' name='firstname'>
	<input type='hidden' value='$user[2] $user[3]' name='lastname'>
	<input type='hidden' value='$row3[1]' name='inst_name'>
	<input type='hidden' value='$row6[1]' name='username'>
	<input type='hidden' value='$row6[2]' name='password'>
	<input type='hidden' value='$user[6]' name='comm_email'>
	<input type='hidden' value='$user[12]' name='fecha_apr'>
	<a href='javascript:document.sendcomm$user[0].submit();'>$user[6]</a>
	</form></td>";}else{
	print"<td>$user[6]</td>";}
}else{
print"<td>&nbsp;</td>";
}

if ($user[8] == 1) print"<td><font color='#0000FF'>Activo</font></td>";
if ($user[9] == 1) print"<td><font color='#FF0000'>Rechazado</font></td>";
if ($user[10] == 1) print"<td><font color='#FF0000'>Suspendido</font></td>";
if ($user[8]+$user[9]+$user[10] == 0) print"<td><font color='#808000'>Pendiente</font></td>";
print"
		<td>$user[11]</td>
		<td>$user[12]</td>
		";
if ($user[7] != "") {
	print"
			<td>$row6[1]</td>
			<td>$row6[2]</td>
		</tr>";
		}else{
	print"
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>";}
	}

print"</table>
</div>";

}



exit;

?>

</body>
</html>
