<?php
	include_once("../config.php");
	
	$query = "SELECT id, inst_name FROM inst ORDER BY inst_name";
	$result = mysql_query($query, $link) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte acceso remoto</title>
<link href="../../css/reporte.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="contenedor">
  <img src="../imgs/heading.png" />
  <h2>Reporte de solicitudes de acceso remoto</h2>
  <form id="form1" name="form1" method="post" action="reporte_xls.php">
    <label for="institucion">Institución:</label>
    <select name="institucion" id="institucion">
      <?php
		while($row = mysql_fetch_object($result)) {
	?>
      <option value="<?php echo $row->id; ?>"><?php echo utf8_encode($row->inst_name); ?></option>
      <?php
		
		}
		mysql_free_result($result);
	?>
    </select>
    <br />
    <label for="aprobado">Aprobado:</label>
    <input type="checkbox" name="aprobado" id="aprobado" />
	<br />
    <label for="button"></label>
    <input type="submit" name="button" id="button" value="Generar Excel" />
  </form>
</div>
</body>
</html>