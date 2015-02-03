<?php
session_start();
error_reporting(0);
require_once('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="../../scripts/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
	$(function() {
		$("#yes").click(function() {
			if($(this).is(":checked")) {
				$('body').append('<div class="lbox" ></div>');
                $("#terminos").toggle();
			}
		});
		
		$(".modal_close").click(function(e) {
			e.preventDefault();
			$(".lbox").remove();
			$("#terminos").toggle();
		});
	});
</script>
<style type="text/css">
	#terminos {display: none;}
	.lbox {
		position: fixed;
		top: 0;
		left: 0;
		min-height: 100%;
		min-width: 100%;
		opacity: 0.7;
		background-color: #333333;
		z-index: 100;
	}
	#terminos {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		padding: 0;
		margin-top: -260px;
		margin-left: -400px;
		z-index: 1000;
	}
	#terminos div {
		margin: 10px;
		padding: 10px;
		height: 470px;
		width: 800px;
		background-color: #FFFFFF;
		border: 2px solid #666666;
		border-radius: 8px;
		overflow: auto;
	}
	.modal_close a {
		position: absolute;
		top: -8px;
		right: 10px;
		text-decoration: none;
		color: #FFFFFF;
	}
	.modal_close a:hover {text-decoration:underline;}
	.tooltip {
		display: none;
		position: absolute;
		margin-top: -40px;
		border: 1px solid #333;
		background-color: #161616;
		border-radius: 5px;
		padding: 5px 10px;
		color: #FFFFFF;
		font-size: 12px;
	}
</style>
</head>
<div id="terminos">
<div>
<p class="modal_close"><a href="#">Cerrar</a></p>
<p align="center"><strong>TÉRMINOS Y CONDICIONES DE USO DE LA CLAVE DE ACCESO REMOTO A LOS<br />
RECURSOS DE INFORMACIÓN CIENTÍFICA Y TECNOLÓGICA DEL CONRICYT</strong></p>
<p>Los recursos electrónicos de información científica y tecnológica, asignados a tu institución a través del CONRICYT tienen como finalidad apoyar las tareas sustantivas de las comunidades académicas y científicas tales como la: docencia e investigación, por lo que su uso está destinado exclusivamente para fines académicos.</p>
<p>Reconozco que el uso de <strong>mi Clave de Acceso Remoto es personal, privada e intransferible y que el periodo de vigencia es de un año</strong>, por lo que en ningún caso podré prestarla a ningún otro usuario, incluyendo cualquier otro miembro de la comunidad académica a la que pertenezco.</p>
<p>Los contratos contraídos con las casas editoras e integradoras prohíben que los recursos electrónicos sean utilizados para los siguientes propósitos:</p>
<p>En ningún caso, podré modificar, adaptar, transformar, traducir ni crear o vender ningún trabajo derivado en cualquier medio, basado en los recursos electrónicos o que incluya tales materiales; tampoco podré utilizar de otro modo dichos materiales de manera tal que viole los derechos de autor u otros derechos de propiedad exclusiva sobre ellos.</p>
<p>No podré eliminar, ocultar ni modificar de ningún modo los avisos de derechos de autor, marca comercial u otros avisos de derechos de propiedad exclusiva, atribuciones de autoría ni exclusiones de responsabilidad incluidos por los editores y autores.</p>
<p>No me está permitido la descarga masiva y/o sistemática de documentos.</p>
<p>No me está permitida la reproducción sustancial o sistemática ni el suministro o distribución sistemáticos de copias únicas o múltiples en cualquier forma a personas que no sean usuarios autorizados.</p>
<p>No me está permitida la distribución de cualquier parte de los recursos en red electrónica.</p>
<p><strong>Reconozco y Acepto que estoy enterado(a) que, la violación de cualquiera de las prohibiciones señaladas párrafos arriba tendrá como sanción la suspensión inmediata e irrevocable de mi clave de acceso remoto, sin que pueda ser sujeto(a) a una renovación de la misma. La suspensión inmediata e irrevocable de mi clave de acceso remoto será notificada a mi Institución de adscripción.</strong></p>
</div>
</div>
<?php
if(isset($_GET['profile'])){
	if(!$_POST['setunique'] || $_POST['setunique'] != $_SESSION['checkUnique']) {
		unset($_SESSION['checkUnique']);
		$uniqueID = time();
		$_SESSION['checkUnique'] = $uniqueID;
	}
	
	if($_POST['setunique'] == $_SESSION['checkUnique'] && isset($_POST['Registrar']) && $_POST['honeypot'] == '') {
		unset($_SESSION['checkUnique']);
		$user = new User(
			$pdo,
			false,
			trim($_POST['firstname']),
			(!$_POST['chkApPaterno']) ? trim($_POST['lastname1']) : "",
			(!$_POST['chkApMaterno']) ? trim($_POST['lastname2']) : "",
			$_POST['rfc'].$_POST['homoclave'],
			(int)$_POST['entidad'],
			(int)$_POST['gender'],
			$_POST['institution'],
			$_POST['account_num'],
			strtolower($_POST['inst_email']),
			strtolower($_POST['comm_email']),
			($_POST['institution'] == 475) ? 19 : (int)$_POST['profile'],
			(int)$_POST['delegacion'],
			$_POST['ceo'],
			$_POST['unit_faculty'],
			($_POST['institution'] != 475 ? ($_POST['perfil'] == 'es' ? 1 : 2) : 3)
		);
		
		if($_POST['precarga']) {
			$precarga = true;
		} else {
			$precarga = false;
		}
				
		//print_r($_SESSION);echo ' '.$_POST['setunique'];exit();
		
		$id = $user->save();
		
		//if($_POST['institution'] == 475) {
			$user = new User($pdo, $id);
			if($user->generateCredentials()){
				$user->save();
				
				$preset_user = new PresetUsers($pdo, $user);
				$user->username = $preset_user->username;
				$user->password = $preset_user->password;
			}
		//}
		
		if($precarga) {
			$email = new Email($twig, 
				array($user->comm_email), 
				'consorcio@conacyt.mx', 
				'registration.mail_precarga.twig',
				array(
					'user' => $user
				)
			);
		} else {
			$email = new Email($twig,
					array($user->comm_email),
					'consorcio@conacyt.mx',
					'registration.mail.twig',
					array(
							'user' => $user
					)
			);
		}

		//$email->send();
		
		if($precarga) {
			if($user->inst_email) {
				$email = new Email($twig,
						array($user->inst_email),
						'consorcio@conacyt.mx',
						'registration.mail_precarga.twig',
						array(
								'user' => $user
						)
				);
				
				//$email->send();
			}
		} else {
			if($user->inst_email) {
				$email = new Email($twig,
						array($user->inst_email),
						'consorcio@conacyt.mx',
						'registration.mail.twig',
						array(
								'user' => $user
						)
				);
			
				//$email->send();
			}
		}

		if($precarga) {
			echo $twig->render('registration_finished_precarga.twig', array('user' => $user));
		} else {
			echo $twig->render('registration_finished.twig', array('user' => $user));
		}

	} else {
		$profile = true;
		if((bool)$profile){
			$institutions = $pdo->query('SELECT * FROM inst WHERE status=1 ORDER BY inst_name ASC')->fetchAll();
			$entidades = $pdo->query('SELECT id, entidad FROM entidad WHERE estatus = 1 ORDER BY entidad')->fetchAll();
			$genders = $pdo->query("SELECT * FROM gender")->fetchAll();
			$delegaciones = $pdo->query("SELECT id, delegacion FROM cat_deleg_imss WHERE estatus = 1 ORDER BY delegacion")->fetchAll();
			echo $twig->render("registration.twig", array(
				'institutions' => $institutions, 
				'states' => $entidades, 
				'genders' => $genders,
				'profile' => $profile,
				'delegaciones' => $delegaciones,
				'recaptcha_form' => recaptcha_get_html($publickey),
				'uniqueid' => $uniqueID
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
	//if($user->createUsername() == false){
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

		//$email->send('PDF');
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