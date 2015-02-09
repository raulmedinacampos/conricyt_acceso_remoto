<?php
require_once 'config.php';

$institucion = (isset($_POST['inst'])) ? $_POST['inst'] : 0;
$cuenta = (isset($_POST['cuenta'])) ? $_POST['cuenta'] : "";

if($institucion == 475 && $cuenta) {
	$query = "SELECT id, firstname, lastname1, lastname2, rfc, entidad, gender, inst_email, comm_email, categoria_sui, deleg_imss, clave_est_org, unit_faculty FROM preaprobado WHERE account_num = '$cuenta'";
	$datos = $pdo->query($query)->fetchAll();
	
	$datos[0]['homoclave'] = "";
	
	if(strlen($datos[0]['rfc']) >= 10) {
		$datos[0]['homoclave'] = substr($datos[0]['rfc'], 10);
		$datos[0]['rfc'] = substr($datos[0]['rfc'], 0, 10);
	}
	
	echo json_encode($datos);
}
?>