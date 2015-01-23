
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<html>
<head>
<!--
     NOTE:
     To include graphics on this page, place the graphics in the public subdirectory
  and use links such as:
     <img src="/public/logo.gif">
     It is important to use /public/ instead of just public/ since some EZproxy
     login URLs are not be relative to /
-->

    <script type="text/javascript" LANGUAGE="JavaScript">


function JumpToIt(list) {
    var newPage = list.options[list.selectedIndex].value
    if (newPage != "None") {
        location.href=newPage
    }
}
//-->
</SCRIPT>
</head>
<body class="oneColFixCtr" style="background-color:#FFF">
<div id="header">
<div id="container">

       
        
    <div class="illustration" style="background-color:#FFFFFF; text-align:center;"> 

    
      <p><img src="images/header-summon-background-01.png"   alt="header" /></a></p>
    </div>

 

 
</div>
</div>
<div id="main" style="min-height:300px; background-color:white">

<div>
  
  <div class="borderedContent"><center><br>




	<form action="/login" method="post">
	
	<table>
	<tbody><tr>
		<td style="font-family: Arial,Helvetica,sans-serif; font-weight: bold; color: rgb(255, 255, 255); font-size: 14px;" align="center" bgcolor="#255170" height="30">

		<p><span style="font-size: 0.9em">Acceso Remoto</span></p>
		</td>
	</tr>
	<tr>
		<td style="font-family: Arial,Helvetica,sans-serif; color:#255170; font-size: 12px;" height="45">
        <input type="hidden" name="url" value="http://conricyt4.summon.serialssolutions.com" />        Usuario<br>		<input type="text"     name="user" />
		</td>
	</tr>
	<tr>
		<td style="font-family: Arial,Helvetica,sans-serif; color: #255170; font-size: 12px;" height="45">
		<p>Contrase&ntilde;a<br>
		  <span style="font-family: Arial,Helvetica,sans-serif; color:#255170; font-size: 12px;">
		  <input type="password" name="pass" />
		  </span></p>
		</td>

	</tr>
	<tr>
		<td height="30">
		<p>
		<a href="registration.php?forgotpass=true">¿Olvido su contraseña?</a><br />
<input style="font-family: Arial,Helvetica,sans-serif; color:black; font-size: 12px;" value="Entrar" type="submit">
		  </p></td>
	</tr>
	</tbody></table>
	<p>Solicita  tu Cuenta de Acceso Remoto
<select id="myselect" onChange="JumpToIt(this)">
			<option value="#" selected="selected">Seleccione Uno</option>
	  <option value="registration.php?profile=es">Estudiante</option>
	  <option value="registration.php?profile=ac">Académico o Administrativo</option>
</select>

	<br />
	<p>&nbsp;</p>
    </form>	
	 
</center>

 </div>
</div>
</div>
</body>
</html>