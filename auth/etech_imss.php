<?php
session_start();
error_reporting(0);
require_once('config.php');

$sql = "SELECT id FROM users
		WHERE institution = 475
		AND active = 1
		AND deleg_imss IS NOT NULL
		AND account_num IN(SELECT account_num FROM preaprobado)";
$result = $pdo->query($sql);
$users = array();

foreach($result as $row) {
	$user = new User($pdo, $row['id']);
	$users[] = $user;
}

$sql = "SELECT id FROM users 
		WHERE institution = 475 
		AND active = 1 
		AND deleg_imss IS NOT NULL 
		AND account_num NOT IN(SELECT account_num FROM preaprobado)";
$result = $pdo->query($sql);
$users2 = array();

foreach($result as $row) {
	$user = new User($pdo, $row['id']);
	$users2[] = $user;
}

echo $twig->render('etech_imss.twig', array('users' => $users, 'users2' => $users2));
?>