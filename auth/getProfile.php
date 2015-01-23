<?php
require_once 'config.php';

$role = (isset($_POST['perfil'])) ? $_POST['perfil'] : "";

if($role) {
	$query = "SELECT id, name FROM level WHERE role = $role";
	$niveles = $pdo->query($query)->fetchAll();
	
	echo json_encode($niveles);
}
?>