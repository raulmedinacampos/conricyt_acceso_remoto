<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Env�o de correo</title>
</head>

<body>

<?php
require_once 'class.phpmailer.php';
require_once 'class.smtp.php';

if (isset($_POST['firstname']))
{
          $firstname = $_POST["firstname"];
}

if (isset($_POST['lastname']))
{
          $lastname = $_POST["lastname"];
}

if (isset($_POST['inst_name']))
{
          $inst_name = $_POST["inst_name"];
}

if (isset($_POST['username']))
{
          $username = $_POST["username"];
}

if (isset($_POST['password']))
{
          $password = $_POST["password"];
}

if (isset($_POST['inst_email']))
{
          $inst_email = $_POST["inst_email"];
}

if (isset($_POST['comm_email']))
{
          $comm_email = $_POST["comm_email"];
}

if (isset($_POST['fecha_apr']))
{
          $fecha_apr = $_POST["fecha_apr"];
}

	// Enviar copia del comprobante de aprobaci�n por correo
	if ($inst_email != "") 	$destinatario = $inst_email;
	if ($comm_email != "") 	$destinatario = $comm_email;
	$asunto = "CONRICYT -  Autorizaci�n de acceso remoto";

	//para el env�o en formato HTML
	/*$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	//direcci�n del remitente
	$headers .= "From: CONRICYT <consorcio@conacyt.mx>\r\n";

	//direcci�n de respuesta, si queremos que sea distinta que la del remitente
	$headers .= "Reply-To: consorcio@conacyt.mx\r\n";

	//ruta del mensaje desde origen a destino
	$headers .= "Return-path: consorcio@conacyt.mx\r\n";

	//direcciones que recibir�n copia
	$headers .= "Bcc: conricyt@gmail.com\r\n";

	//direcciones que recibir�n copia oculta
	$headers .= "Bcc: nilbalion@outlook.com\r\n";*/
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  = 0;
	$mail->SMTPAuth   = true;					// activa autenticaci�n
	//$mail->Host       = "smtp.gmail.com";		// servidor de correo
	$mail->Host       = "74.125.136.108";		// servidor de correo
	$mail->Port       = 465;                    // puerto de salida que usa Gmail
	$mail->SMTPSecure = 'ssl';					// protocolo de autenticaci�n
	$mail->Username   = "conricyt@gmail.com";
	$mail->Password   = 'C0nR1c17p1x3l8lu3';
	$mail->Subject    = $asunto;
	$mail->AltBody    = $asunto;
	
	$mail->SetFrom('conricyt@gmail.com', 'CONRICyT');
		
	$mail->AddAddress($destinatario);
	
	//$mail->CharSet = 'UTF-8';

	$cuerpo = "<title>Servicio de Acceso Remoto - CONRICYT</title></head><body><h1>Autorizaci�n Acceso Remoto - CONRICYT</h1>";
	$cuerpo .="<h3><strong>Apreciable ".$firstname." ".$lastname.",</strong></h3><div align='center'>";
	$cuerpo .="<table border='1' width='640' id='table1' style='border-collapse: collapse' cellpadding='3'><tr><td>";
	$cuerpo .="<p align='center'>A continuaci�n encontrar�s tus datos de acceso para el Servicio de Acceso Remoto a los Recursos Electr�nicos del ";
	$cuerpo .="Consorcio Nacional de Recursos de Informaci�n Cient�fica y Tecnol�gica (CONRICYT).</td></tr></table></div>";
	$cuerpo .="<p>Tu clave de acceso remoto est� activada y mediante ella podr�s acceder a los recursos electr�nicos a los que tiene derecho la Instituci�n a la que ";
	$cuerpo .="perteneces, a trav�s del sitio <a href='http://etechwebsite.com/conricyt/conricyt-select/'>http://etechwebsite.com/conricyt/conricyt-select/</a>. Recuerda que si tienes ";
	$cuerpo .="alg�n problema para acceder, puedes escribir un correo electr�nico a la direcci�n: <a href='mailto:consorcio@conacyt.mx?subject=Problema con acceso'>";
	$cuerpo .="consorcio@conacyt.mx</a> para apoyarte y solucionar el mismo.</p><h2><strong>Guarde esta informaci�n de acceso en un sitio seguro:</strong></h2>";
	$cuerpo .="<table border='1' style='margin-left:0px; padding:none' cellpadding='3'><tr><td colspan='2' valign='top'><p align='center'><img src='http://conricyt.mx/conremoto/auth/conricyt2.png' /><strong>Servicio ";
	$cuerpo .="de Acceso Remoto</strong></p></td></tr><tr><td><p align='right'>Nombre completo:</p></td><td><p>".$firstname." ".$lastname."</p></td></tr><tr>";
	$cuerpo .="<td><p align='right'>Nombre de la Instituci�n:</p></td><td><p>".$inst_name."</p></td></tr><tr><td><p align='right'>Fecha de aprobaci�n:</p></td>";
	$cuerpo .="<td><p> ".$fecha_apr."</p></td></tr><tr><td><p align='right'>Usuario:</td><td><strong>".$username."</strong></td></tr><tr><td>";
	$cuerpo .="<p align='right'>Contrase�a:</td><td> <strong>".$password."</strong></td></tr></table><table border='0' cellspacing='3' cellpadding='0' width='100%'>";
	$cuerpo .="<tr><td valign='top'></td></tr><tr><td valign='top'></td></tr></table><p align='center' style='font-size:0.8em'>*Si deseas conocer cu�les son los ";
	$cuerpo .="recursos electr�nicos a los que tu Instituci�n tiene acceso por parte del CONRICYT,<br>puedes consultarlos en <a href='http://www.conricyt.mx/acervo-editorial/recursos-por-institucion.htm'>";
	$cuerpo .="http://www.conricyt.mx/acervo-editorial/recursos-por-institucion.htm</a></p><p style='text-align: justify'><strong>Aviso de Confidencialidad</strong>";
	$cuerpo .="<br />En el Consorcio Nacional de Recursos de Informaci�n Cient�fica y Tecnol�gica (CONRICYT), www.conricyt.mx, con domicilio en Av. Insurgentes Sur 1582, Col. Cr�dito ";
	$cuerpo .="Constructor, Delegaci�n Benito Ju�rez, C.P.: 03940, M�xico, D.F.; la informaci�n de nuestros usuarios es tratada en forma estrictamente confidencial por lo que ";
	$cuerpo .="sus datos personales son utilizados �nicamente con la finalidad de brindar acceso a los recursos electr�nicos proporcionados por el Consorcio.</p>";
	$cuerpo .="<p style='text-align: justify'>&nbsp;</p><p style='text-align: center'><strong>T�RMINOS Y CONDICIONES DE USO PARA LA CUENTA DE ACCESO REMOTO A LOS ";
	$cuerpo .="RECURSOS ELECTR�NICOS DEL CONRICYT</strong></p><p style='text-align: justify'>Los recursos electr�nicos de informaci�n cient�fica y tecnol�gica, asignados a ";
	$cuerpo .="su instituci�n por medio del CONRICYT tienen la finalidad de apoyar las tareas sustantivas de las comunidades acad�micas y cient�ficas tales como la: docencia ";
	$cuerpo .="e investigaci�n, por lo que su uso est� destinado exclusivamente para fines acad�micos.</p><p style='text-align: justify'>";
	$cuerpo .="Reconozco que el uso de mi cuenta de acceso remoto es personal, privada e intransferible, por lo que en ning�n caso podr� prestarla a ning�n otro usuario, ";
	$cuerpo .="incluyendo cualquier otro miembro de la comunidad universitaria a la que pertenezco.</p><p style='text-align: justify'>Los contratos contra�dos con las casas editoras e integradoras proh�ben que los ";
	$cuerpo .="recursos electr�nicos sean utilizados para los siguientes prop�sitos:</p><p style='text-align: justify'>En ning�n caso, podr� modificar, adaptar, transformar, traducir ni crear o ";
	$cuerpo .="vender ning�n trabajo derivado en cualquier medio, basado en los recursos electr�nicos o que incluya tales materiales; tampoco podr� utilizar de otro modo ";
	$cuerpo .="dichos materiales de manera tal que viole los derechos de autor u otros derechos de propiedad exclusiva sobre ellos.</p><p style='text-align: justify'>";
	$cuerpo .="No podr� eliminar, ocultar ni modificar de ning�n modo los avisos de derechos de autor, marca comercial u otros avisos de derechos de propiedad exclusiva, ";
	$cuerpo .="atribuciones de autor�a ni exclusiones de responsabilidad incluidos por los editores y autores.</p><p style='text-align: justify'>No me est� permitida la descarga masiva y/o sistem�tica de documentos.</p>";
	$cuerpo .="<p style='text-align: justify'>No me est� permitida la reproducci�n sustancial o sistem�tica ni el suministro o distribuci�n sistem�ticos de copias �nicas o m�ltiples en cualquier forma a ";
	$cuerpo .="personas que no sean usuarios autorizados.</p><p style='text-align: justify'>No me est� permitida la distribuci�n de cualquier parte de los recursos en red ";
	$cuerpo .="electr�nica.</p><p style='text-align: justify'>Reconozco y Acepto que estoy enterado(a) que, la violaci�n de cualquiera de las prohibiciones se�aladas p�rrafos arriba tendr� como sanci�n la suspensi�n ";
	$cuerpo .="inmediata e irrevocable de mi clave de acceso remoto, sin que pueda ser sujeto(a) a una renovaci�n de la misma. La suspensi�n inmediata e irrevocable de ";
	$cuerpo .="mi clave de acceso remoto ser� notificada a mi Instituci�n de adscripci�n.<br /></p></div>";
	
	$mail->MsgHTML($cuerpo);
	
	$mail->Send();
	
	//mail($destinatario,$asunto,$cuerpo,$headers);

print"
<h2 align='center'>
  <strong>El correo se ha enviado con �xito a:<br>
	$firstname $lastname,</strong></h2>
  <div align='center'>
  
<table border='1' style='margin-left:0px; padding:none' cellpadding='3'>
  <tr>
    <td colspan='2' valign='top'><p align='center'><img src='http://conricyt.mx/conremoto/auth/conricyt2.png' /><strong>Servicio 
	de Acceso Remoto</strong></p></td>
  </tr>
  <tr>
    <td><p align='right'>Nombre completo:</p></td>
    <td><p>$firstname $lastname</p></td>
  </tr>
  <tr>
    <td><p align='right'>Nombre de la Instituci�n:</p></td>
    <td><p>$inst_name</p></td>
  </tr>
  <tr>
    <td><p align='right'>Fecha de aprobaci�n:</p></td>
    <td><p> $fecha_apr </p></td>
  </tr>
  <tr>
    <td>
	<p align='right'>Usuario:</td>
    <td><strong>$username</strong></td>
  </tr>
  <tr>
    <td>
	<p align='right'>Contrase�a:</td>
    <td> <strong>$password</strong></td>
  </tr>
  <tr>
    <td>
	<p align='right'>Correo electr�nico:</td>
    <td>$destinatario</td>
  </tr>
</table>
</div>";

?>

<p align='center'><input type='button' value='Cerrar esta ventana' name='B4U' onClick='javascript:window.close();' style='font-size:12px'></p>

</body>
</html>
