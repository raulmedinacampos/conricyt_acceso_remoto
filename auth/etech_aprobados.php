<?php
session_start();
error_reporting(0);
require_once('config.php');

$users = User::find($pdo, array('institution <>' => 475, 'active' => 1, 'preset_user_id <>' => '', 'fecha_reg >=' => '2015-02-05 19:00:00'));
echo $twig->render('etech_aprobados.twig', array('users' => $users));
?>