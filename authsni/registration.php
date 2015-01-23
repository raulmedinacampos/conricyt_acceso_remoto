<?php
error_reporting(0);
require_once('config.php');

if(isset($_GET['profile'])){
	if(isset($_POST['Registrar']) && $_POST['honeypot'] == '') {
		$user = new User(
			$pdo,
			false,
			trim($_POST['firstname']),
			trim($_POST['lastname']),
			$_POST['rfc'],
			(int)$_POST['gender'],
			$_POST['institution'],
			$_POST['account_num'],
			$_POST['inst_email'],
			$_POST['comm_email'],
			(int)$_POST['profile'],
			$_POST['unit_faculty'],
			($_GET['profile'] == 'es' ? 1 : 2)
		);

		$user->save();
		
		$email = new Email($twig, 
			array($user->inst_email), 
			'consorcio@conacyt.mx', 
			'registration.mail.twig',
			array(
				'user' => $user
			)
		);

		$email->send();

		echo $twig->render('registration_finished.twig', array());

	} else {
		switch($_GET['profile']){
			case 'es':
				$profile = array(
					'title' => "Estudiante",
					'id_number' => "Matricula/ No. de Cuenta*",
					'level_title' => "Nivel Escolar",
					'levels' => $pdo
						->query("SELECT * FROM level WHERE role = 1")
						->fetchAll()
					);
				break;
			case 'ac':
				$profile = array(
					'title' => "Académico o Administrativo",
					'id_number' => "No. de Empleado/ No. Económico*",
					'level_title' => "Perfil",
					'levels' => $pdo
						->query("SELECT * FROM level WHERE role = 2")
						->fetchAll()
					);
				break;
			default:
				$profile = false;
				break;
		}
		if((bool)$profile){
			$institutions = $pdo->query('SELECT * FROM inst WHERE status=1 ORDER BY inst_name ASC')->fetchAll();
			$genders = $pdo->query("SELECT * FROM gender")->fetchAll();
			echo $twig->render("registration.twig", array(
				'institutions' => $institutions, 
				'genders' => $genders,
				'profile' => $profile,
				'recaptcha_form' => recaptcha_get_html($publickey)
			));
		} else {header('HTTP/1.0 403 Forbidden');}

	}
} elseif (isset($_GET['user'])){
	if($_GET['user'] == 'all'){
		$users = User::find($pdo, array('rejected' => 0));
	} elseif($_GET['user'] == 'es'){
		$users = User::find($pdo, array('role' => 1, 'rejected' => 0));
	} elseif($_GET['user'] == 'ac') {
		$users = User::find($pdo, array('role' => 2, 'rejected' => 0));
	} elseif($_GET['user'] == 'unapproved'||$_GET['user'] == 'pending') {
		$users = User::find($pdo, array('active' => 0, 'rejected' => 0));
	} elseif($_GET['user'] == 'approved'){
		$users = User::find($pdo, array('active' => 1, 'rejected' => 0));
	} elseif($_GET['user'] == 'rejected'){
		$users = User::find($pdo, array('active' => 0, 'rejected' => 1));
	} else {

	}
	echo $twig->render('verification.twig', array('users' => $users) );
} elseif (isset($_POST['verify'])) {
	$userid = $_POST['verify'];
	$user = new User($pdo, $userid);
	if($user->generateCredentials() == false){
		echo 'false';
	} else {
		$user->save();
		$email = new Email($twig, 
			array($user->inst_email), 
			'consorcio@conacyt.mx', 
			'verified.mail.twig',
			array(
				'user' => $user
			)
		);

		$email->send('PDF');
	}
} elseif(isset($_POST['reject'])){
	$userid = $_POST['reject'];
	$user = new User($pdo, $userid);
	$user->reject();
	if($user->save()){
		$email = new Email($twig,
			array($user->inst_email),
			'consorcio@conacyt.mx',
			'reject.mail.twig',
			array(
				'user' => $user
			)
		);
		$email->send();
	} else {
		echo 'false';
	}
} elseif(isset($_POST['suspend'])){
	$userid = $_POST['suspend'];
	$user = new User($pdo, $userid);
	$user->suspend(!isset($_POST['un']));
	if(!$user->save()){
		echo 'false';
	}
} elseif(isset($_GET['forgotpass'])){
	if(isset($_POST['forgotpass_submit'])){
		$user = User::find($pdo, array(
			'inst_email' => $_POST['email']
		));
		if(!empty($user)){

			$email = new Email($twig, 
				array($_POST['email']), 
				'consorcio@conacyt.mx', 
				'forgotpass.mail.twig',
				array(
					'user' => $user[0]
				)
			);

			$email->send('PDF');
			
			echo $twig->render('forgotpass_submit.twig', array('user' => $user));
		} else {
			header("Location: registration.php?forgotpass=true&mess=true");
		}
	} else {
		echo $twig->render('forgotpass.twig', array());
	}
} else {
	header('HTTP/1.0 404 Not Found');
}
