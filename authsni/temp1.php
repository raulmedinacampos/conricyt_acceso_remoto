<?php

require_once('config.php');
try{
	foreach($pdo->query("SELECT * FROM preset_users") as $user){
		$new_user = $pdo->prepare("UPDATE preset_users SET username = ?, password = ? WHERE id = ?");
		$new_user->execute(array(trim($user['username']), trim($user['password']), $user['id']));
	}
} catch(PDOException $e){
	print_r($e->getMessage());
	die;
}