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

}