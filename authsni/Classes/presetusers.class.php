<?php
class PresetUsers{
	private $pdo;
	public $id;
	public $username;
	public $password;
	public $used_date;

	public function __construct($pdo, $user)
	{
		if(!empty($user->presetuser_id)){
			try{
				$stmt = $pdo->prepare("SELECT * FROM preset_users WHERE id = ? LIMIT 1");
				$stmt->execute(array($user->presetuser_id));
				$cred = $stmt->fetch();
			} catch(PDOException $e){
				print_r('get preset user: ' . $e->getMessage());
				die;
			}
		} else {
			try{
				//$stmt = $pdo->prepare("SELECT * FROM preset_users WHERE used <> 1 AND institution = ? LIMIT 1");
				$stmt = $pdo->prepare("SELECT * FROM preset_users WHERE used <> 1 LIMIT 1");
				//$stmt->execute(array($user->institution));
				$stmt->execute();
				$cred = $stmt->fetch();
			} catch(PDOException $e){
				print_r('get next preset user: ' . $e->getMessage());
				die;
			}
		}
		$this->pdo = $pdo;
		$this->id = $cred['id'];
		$this->username = $cred['username'];
		$this->password = $cred['password'];
		$this->used_date = $cred['used_date'];
	}

	public function Used($pdo)
	{
		try{
			$stmt = $pdo->prepare("UPDATE preset_users SET used = 1, used_date = NOW() WHERE id = ?;");
			$stmt->execute(array($this->id));
		} catch(PDOException $e){
			print_r('make preset user used: ' . $e->getMessage());
			die;
		}
	}
	
	// Metodo agregado por Raul Medina. Inserta registro en la tabla preset_user
	public function Insert($pdo, $user)
	{
		$inserted = false;
		try{
			$stmt = $pdo->prepare("INSERT INTO preset_users(username, password, used, institution, used_date) VALUES(?, ?, ?, ?, ?)");
			$stmt->execute(array($this->CreateUsername($pdo, $user), $this->CreatePassword(), 1, $user->institution, date('Y-m-d')));
			$inserted = $pdo->lastInsertId('id');
		} catch(PDOException $e){
			print_r('make preset user used: ' . $e->getMessage());
			die;
		}
		return $inserted;
	}
	
	private function CreateUsername($pdo, $user) {
		$username = "usr";
		$extra = "0000001";
		
		$stmt = $pdo->prepare("SELECT id, shortname FROM inst WHERE id = ?");
		$stmt->execute(array($user->institution));
		$inst = $stmt->fetch();
		
		$stmt = $pdo->prepare("SELECT id, shortname FROM inst WHERE shortname = ?");
		$stmt->execute(array($inst['shortname']));
		$instituciones = "";
		while($inst = $stmt->fetch()) {
			$instituciones .= $inst['id'].',';
		}
		
		$instituciones = trim($instituciones, ',');
		
		//$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM preset_users WHERE institution = ? AND used = 1");
		//$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM preset_users WHERE (institution = ? OR username LIKE ?) AND used = 1");
		$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM preset_users WHERE institution IN(".$instituciones.") AND used = 1");
		//$stmt->execute(array($user->institution));
		$stmt->execute();
		$used = $stmt->fetch();
		
		// Si el usuario es del IMSS el usuario es el mismo que la cuenta
		if($user->institution == 475) {
			$username = $user->account_num;
			$extra = "";
		} else {
			$stmt = $pdo->prepare("SELECT shortname FROM inst WHERE id = ?");
			$stmt->execute(array($user->institution));
			$shortname = $stmt->fetch();
			
			if($shortname['shortname']) {
				$username = strtolower($shortname['shortname']);
			}
			
			if($used && $used['total'] > 0) {
				$extra = str_pad(((int)$used['total'] + 1), 7, "0", STR_PAD_LEFT);
			}
		}
		
		return $username.$extra;
	}
	
	private function CreatePassword($lenght = 10) {
		$password = substr(str_shuffle(md5(time())), 0, $lenght);
		return $password;
	}
}