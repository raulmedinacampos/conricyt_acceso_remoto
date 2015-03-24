<?php
require_once 'config.php';

$institucion = (isset($_POST['inst'])) ? $_POST['inst'] : 0;
$cuenta = (isset($_POST['cuenta'])) ? $_POST['cuenta'] : "";
$existente = 0;

if($institucion > 0 && $cuenta) {
	$query = "SELECT COUNT(*) AS total FROM users WHERE institution = $institucion AND account_num = '$cuenta'";
	$datos = $pdo->query($query)->fetch();
	
	if($datos['total'] > 0) {
		$existente = 1;
	}
}

echo $existente;
?>