<?php

$host = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'conricyt_proxy';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
	echo "ERROR: " . $e->getMessage();
}
