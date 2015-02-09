<?php
require_once('config.php');
echo $twig->render('registration_finished.twig', array('user' => $user));
?>