<?php
session_start();
error_reporting(0);
require_once('config.php');

$users = User::find($pdo, array('institution' => 475, 'active' => 1, 'deleg_imss IS NOT' => 'NULL'));
echo $twig->render('etech_imss.twig', array('users' => $users));
?>