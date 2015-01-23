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
				$stmt = $pdo->prepare("SELECT * FROM preset_users WHERE used <> 1 AND institution = ? LIMIT 1");
				$stmt->execute(array($user->institution));
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
	
	// Method added by Raul Medina. Insert register into preset_users table
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
		$extra = "0001";
		
		$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM preset_users WHERE institution = ? AND used = 1");
		$stmt->execute(array($user->institution));
		$used = $stmt->fetch();
		
		// If user is from IMSS then username is the same than account number
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
			
			if($used['total'] > 0) {
				$extra = str_pad(((int)$used['total'] + 1), 4, "0", STR_PAD_LEFT);
			}
		}
		
		return $username.$extra;
	}
	
	private function CreatePassword($lenght = 10) {
		$password = substr(str_shuffle(md5(time())), 0, $lenght);
		return $password;
	}
}