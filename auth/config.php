<?php

require_once 'Classes/presetusers.class.php';
require_once 'Classes/presetusersold.class.php';
require_once 'Classes/user.class.php';
require_once 'Classes/email.class.php';
require_once 'Classes/recaptchalib.php';
require_once 'db.connect.php';

$publickey = "6Lc2KuMSAAAAAPzhPrjORZVv7laJUXwo4wfj3KXK"; // you got this from the signup page
$privatekey = "6Lc2KuMSAAAAAIBgY6oht7Jn-I9LrycWLQc49UJi";

require_once '../Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates/');
$twig = new Twig_Environment($loader, array());