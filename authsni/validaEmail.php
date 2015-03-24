<?php
require_once 'config.php';

$correo = (isset($_POST["email"])) ? $_POST["email"] : "";  // Correo institucional

if(strlen($correo) > 3) {
	$query = "SELECT COUNT(*) AS total FROM users WHERE inst_email LIKE '".$correo."' OR comm_email LIKE '".$correo."'";
	$result = $pdo->query($query)->fetch();
	
	if($result['total'] > 0) {
		echo "false";
	} else {
		echo "true";
	}
}
?>
