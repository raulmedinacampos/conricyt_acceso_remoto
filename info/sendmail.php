<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Envío de correo</title>
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

	// Enviar copia del comprobante de aprobación por correo
	if ($inst_email != "") 	$destinatario = $inst_email;
	if ($comm_email != "") 	$destinatario = $comm_email;
	$asunto = "CONRICYT -  Autorización de acceso remoto";

	//para el envío en formato HTML
	/*$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	//dirección del remitente
	$headers .= "From: CONRICYT <consorcio@conacyt.mx>\r\n";

	//dirección de respuesta, si queremos que sea distinta que la del remitente
	$headers .= "Reply-To: consorcio@conacyt.mx\r\n";

	//ruta del mensaje desde origen a destino
	$headers .= "Return-path: consorcio@conacyt.mx\r\n";

	//direcciones que recibirán copia
	$headers .= "Bcc: conricyt@gmail.com\r\n";

	//direcciones que recibirán copia oculta
	$headers .= "Bcc: nilbalion@outlook.com\r\n";*/
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  = 0;
	$mail->SMTPAuth   = true;					// activa autenticación
	//$mail->Host       = "smtp.gmail.com";		// servidor de correo
	$mail->Host       = "74.125.136.108";		// servidor de correo
	$mail->Port       = 465;                    // puerto de salida que usa Gmail
	$mail->SMTPSecure = 'ssl';					// protocolo de autenticación
	$mail->Username   = "conricyt@gmail.com";
	$mail->Password   = 'C0nR1c17p1x3l8lu3';
	$mail->Subject    = $asunto;
	$mail->AltBody    = $asunto;
	
	$mail->SetFrom('conricyt@gmail.com', 'CONRICyT');
		
	$mail->AddAddress($destinatario);
	
	//$mail->CharSet = 'UTF-8';

	$cuerpo = "<title>Servicio de Acceso Remoto - CONRICYT</title></head><body><h1>Autorización Acceso Remoto - CONRICYT</h1>";
	$cuerpo .="<h3><strong>Apreciable ".$firstname." ".$lastname.",</strong></h3><div align='center'>";
	$cuerpo .="<table border='1' width='640' id='table1' style='border-collapse: collapse' cellpadding='3'><tr><td>";
	$cuerpo .="<p align='center'>A continuación encontrarás tus datos de acceso para el Servicio de Acceso Remoto a los Recursos Electrónicos del ";
	$cuerpo .="Consorcio Nacional de Recursos de Información Científica y Tecnológica (CONRICYT).</td></tr></table></div>";
	$cuerpo .="<p>Tu clave de acceso remoto está activada y mediante ella podrás acceder a los recursos electrónicos a los que tiene derecho la Institución a la que ";
	$cuerpo .="perteneces, a través del sitio <a href='http://etechwebsite.com/conricyt/conricyt-select/'>http://etechwebsite.com/conricyt/conricyt-select/</a>. Recuerda que si tienes ";
	$cuerpo .="algún problema para acceder, puedes escribir un correo electrónico a la dirección: <a href='mailto:consorcio@conacyt.mx?subject=Problema con acceso'>";
	$cuerpo .="consorcio@conacyt.mx</a> para apoyarte y solucionar el mismo.</p><h2><strong>Guarde esta información de acceso en un sitio seguro:</strong></h2>";
	$cuerpo .="<table border='1' style='margin-left:0px; padding:none' cellpadding='3'><tr><td colspan='2' valign='top'><p align='center'><img src='http://conricyt.mx/conremoto/auth/conricyt2.png' /><strong>Servicio ";
	$cuerpo .="de Acceso Remoto</strong></p></td></tr><tr><td><p align='right'>Nombre completo:</p></td><td><p>".$firstname." ".$lastname."</p></td></tr><tr>";
	$cuerpo .="<td><p align='right'>Nombre de la Institución:</p></td><td><p>".$inst_name."</p></td></tr><tr><td><p align='right'>Fecha de aprobación:</p></td>";
	$cuerpo .="<td><p> ".$fecha_apr."</p></td></tr><tr><td><p align='right'>Usuario:</td><td><strong>".$username."</strong></td></tr><tr><td>";
	$cuerpo .="<p align='right'>Contraseña:</td><td> <strong>".$password."</strong></td></tr></table><table border='0' cellspacing='3' cellpadding='0' width='100%'>";
	$cuerpo .="<tr><td valign='top'></td></tr><tr><td valign='top'></td></tr></table><p align='center' style='font-size:0.8em'>*Si deseas conocer cuáles son los ";
	$cuerpo .="recursos electrónicos a los que tu Institución tiene acceso por parte del CONRICYT,<br>puedes consultarlos en <a href='http://www.conricyt.mx/acervo-editorial/recursos-por-institucion.htm'>";
	$cuerpo .="http://www.conricyt.mx/acervo-editorial/recursos-por-institucion.htm</a></p><p style='text-align: justify'><strong>Aviso de Confidencialidad</strong>";
	$cuerpo .="<br />En el Consorcio Nacional de Recursos de Información Científica y Tecnológica (CONRICYT), www.conricyt.mx, con domicilio en Av. Insurgentes Sur 1582, Col. Crédito ";
	$cuerpo .="Constructor, Delegación Benito Juárez, C.P.: 03940, México, D.F.; la información de nuestros usuarios es tratada en forma estrictamente confidencial por lo que ";
	$cuerpo .="sus datos personales son utilizados únicamente con la finalidad de brindar acceso a los recursos electrónicos proporcionados por el Consorcio.</p>";
	$cuerpo .="<p style='text-align: justify'>&nbsp;</p><p style='text-align: center'><strong>TÉRMINOS Y CONDICIONES DE USO PARA LA CUENTA DE ACCESO REMOTO A LOS ";
	$cuerpo .="RECURSOS ELECTRÓNICOS DEL CONRICYT</strong></p><p style='text-align: justify'>Los recursos electrónicos de información científica y tecnológica, asignados a ";
	$cuerpo .="su institución por medio del CONRICYT tienen la finalidad de apoyar las tareas sustantivas de las comunidades académicas y científicas tales como la: docencia ";
	$cuerpo .="e investigación, por lo que su uso está destinado exclusivamente para fines académicos.</p><p style='text-align: justify'>";
	$cuerpo .="Reconozco que el uso de mi cuenta de acceso remoto es personal, privada e intransferible, por lo que en ningún caso podré prestarla a ningún otro usuario, ";
	$cuerpo .="incluyendo cualquier otro miembro de la comunidad universitaria a la que pertenezco.</p><p style='text-align: justify'>Los contratos contraídos con las casas editoras e integradoras prohíben que los ";
	$cuerpo .="recursos electrónicos sean utilizados para los siguientes propósitos:</p><p style='text-align: justify'>En ningún caso, podré modificar, adaptar, transformar, traducir ni crear o ";
	$cuerpo .="vender ningún trabajo derivado en cualquier medio, basado en los recursos electrónicos o que incluya tales materiales; tampoco podré utilizar de otro modo ";
	$cuerpo .="dichos materiales de manera tal que viole los derechos de autor u otros derechos de propiedad exclusiva sobre ellos.</p><p style='text-align: justify'>";
	$cuerpo .="No podré eliminar, ocultar ni modificar de ningún modo los avisos de derechos de autor, marca comercial u otros avisos de derechos de propiedad exclusiva, ";
	$cuerpo .="atribuciones de autoría ni exclusiones de responsabilidad incluidos por los editores y autores.</p><p style='text-align: justify'>No me está permitida la descarga masiva y/o sistemática de documentos.</p>";
	$cuerpo .="<p style='text-align: justify'>No me está permitida la reproducción sustancial o sistemática ni el suministro o distribución sistemáticos de copias únicas o múltiples en cualquier forma a ";
	$cuerpo .="personas que no sean usuarios autorizados.</p><p style='text-align: justify'>No me está permitida la distribución de cualquier parte de los recursos en red ";
	$cuerpo .="electrónica.</p><p style='text-align: justify'>Reconozco y Acepto que estoy enterado(a) que, la violación de cualquiera de las prohibiciones señaladas párrafos arriba tendrá como sanción la suspensión ";
	$cuerpo .="inmediata e irrevocable de mi clave de acceso remoto, sin que pueda ser sujeto(a) a una renovación de la misma. La suspensión inmediata e irrevocable de ";
	$cuerpo .="mi clave de acceso remoto será notificada a mi Institución de adscripción.<br /></p></div>";
	
	$mail->MsgHTML($cuerpo);
	
	$mail->Send();
	
	//mail($destinatario,$asunto,$cuerpo,$headers);

print"
<h2 align='center'>
  <strong>El correo se ha enviado con éxito a:<br>
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
    <td><p align='right'>Nombre de la Institución:</p></td>
    <td><p>$inst_name</p></td>
  </tr>
  <tr>
    <td><p align='right'>Fecha de aprobación:</p></td>
    <td><p> $fecha_apr </p></td>
  </tr>
  <tr>
    <td>
	<p align='right'>Usuario:</td>
    <td><strong>$username</strong></td>
  </tr>
  <tr>
    <td>
	<p align='right'>Contraseña:</td>
    <td> <strong>$password</strong></td>
  </tr>
  <tr>
    <td>
	<p align='right'>Correo electrónico:</td>
    <td>$destinatario</td>
  </tr>
</table>
</div>";

?>

<p align='center'><input type='button' value='Cerrar esta ventana' name='B4U' onClick='javascript:window.close();' style='font-size:12px'></p>

</body>
</html>
