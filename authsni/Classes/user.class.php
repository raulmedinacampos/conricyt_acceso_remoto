<?php
class User {
	private $pdo;
	private $id = 0;
	public $firstname;
	public $lastname;
	public $rfc;
	public $gender;
	public $institution;
	public $account_num;
	public $inst_email;
	public $comm_email;
	public $profile;
	public $unit_faculty;
	public $role;
	public $presetuser_id = null;
	public $active;
	public $rejected;
	public $suspended;

	function __construct( $pdo, $id = false , $first = '', $last = '', 
	$rfc = '', $gender = '', $institution = '', $account_num = '',
	$inst_email = '', $comm_email = '', $profile = '', 
	$unit_faculty = '', $role = '') {
		$this->pdo = $pdo;
		if((bool)$id){
			$this->byId($id);
		} 
		if(!empty($first)){
			$this->firstname = $first;
			$this->lastname = $last;
			$this->rfc = $rfc;
			$this->gender = $gender;
			$this->institution = $institution;
			$this->account_num = $account_num;
			$this->inst_email = $inst_email;
			$this->comm_email = $comm_email;
			$this->profile = $profile;
			$this->unit_faculty = $unit_faculty;
			$this->role = $role;
			$this->active = false;
			$this->rejected = false;
			$this->suspended = false;
		}
	}

	public function byId($id)
	{
		try {
			$stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
			$stmt->execute(array('id' => $id));
			$user = $stmt->fetch();
		} catch(PDOException $e){
			print_r($e->getMessage());
			die;
		}
		$this->id = $user['id'];
		$this->firstname = $user['firstname'];
		$this->lastname = $user['lastname'];
		$this->rfc = $user['rfc'];
		$this->gender = $user['gender'];
		$this->institution = $user['institution'];
		$this->account_num = $user['account_num'];
		$this->inst_email = $user['inst_email'];
		$this->comm_email = $user['comm_email'];
		$this->profile = $user['level'];
		$this->unit_faculty = $user['unit_faculty'];
		$this->role = $user['role'];
		$this->presetuser_id = $user['preset_user_id'];
		$this->active = (bool)$user['active'];
		$this->rejected = (bool)$user['rejected'];
		$this->suspended = (bool)$user['suspended'];
		$this->fecha_reg = $user['fecha_reg'];
		return true;
	}

	public function getId()
	{
		return $this->id;
	}

	public function generateCredentials(){
		$next = new PresetUsers($this->pdo, $this);
		if(empty($next->id)){
			return false;
		}
		$this->presetuser_id = $next->id;
		$this->active = true;
		$this->rejected = false;
		$this->suspended = false;
		$next->Used($this->pdo);
		return array(
			'username' => $next->username,
			'password' => $next->password
		);
	}

	public function reject()
	{
		$this->active = false;
		$this->rejected = true;
		print_r($this);
	}

	public function suspend($state = true)
	{
		$this->suspended = (bool)$state;
	}

	public function name()
	{
		return ucwords($firstname . " " . $lastname);
	}

	public function Institution()
	{
		$institution = $this->pdo->prepare("SELECT * FROM inst WHERE id = ?");
		$institution->execute(array($this->institution));
		return $institution->fetch();
	}
	public function Credentials()
	{
		if(empty($this->presetuser_id)){
			return false;
		}
		$cred = new PresetUsers($this->pdo, $this);
		return $cred;
	}

	public function Gender()
	{
		$gender = $this->pdo->prepare("SELECT * FROM gender WHERE id = ?");
		$gender->execute(array($this->gender));
		$gender = $gender->fetch();
		return $gender['name'];
	}

	public function Profile()
	{
		$profile = $this->pdo->prepare("SELECT * FROM level WHERE id = ?");
		$profile->execute(array($this->profile));
		$profile = $profile->fetch();
		return $profile['name'];
	}

	public function save()
	{
		try {			
			$stmt = $this->pdo->prepare("Select * FROM users WHERE id = :id");
			$stmt->execute(array(':id' => $this->id));
			if($stmt->fetchColumn() > 0){
				$query = $this->pdo->prepare("UPDATE users set
					firstname = :firstname,
			 		lastname = :lastname,
			 		rfc = :rfc,
			 		gender = :gender,
			 		institution = :institution,
			 		account_num = :account_num,
			 		inst_email = :inst_email,
			 		comm_email = :comm_email,
			 		level = :profile,
			 		unit_faculty = :unit_faculty,
			 		role = :role,
			 		preset_user_id = :preset_user_id,
			 		active = :active,
			 		rejected = :rejected,
			 		suspended = :suspended,
			 		fecha_reg = fecha_reg,
			 		fecha_apr = CURRENT_TIMESTAMP
			 		WHERE id = :id
				");
			} else {
				$stmt = $this->pdo->prepare("Select * FROM users WHERE inst_email = :inst_email");
				$stmt->execute(array(':inst_email' => $this->inst_email));
				if($stmt->fetchColumn() == 0){
					$query = $this->pdo->prepare("INSERT INTO users VALUES(
						:id,
						:firstname,
						:lastname,
						:rfc,
						:gender,
						:institution,
						:account_num,
						:inst_email,
						:comm_email,
						:profile,
						:unit_faculty,
						:role,
						:preset_user_id,
						:active,
						:rejected,
						:suspended,
						CURRENT_TIMESTAMP,
						'0000-00-00 00:00:00'
					);");
				}else{
					return false;
					die;
				}
			}

			$query->bindParam(':id', $this->id, PDO::PARAM_INT);
			$query->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
			$query->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
			$query->bindParam(':rfc', $this->rfc, PDO::PARAM_STR);
			if($this->gender != 0){
				$query->bindParam(':gender', $this->gender, PDO::PARAM_INT);
			} else {
				$query->bindParam(':gender', null, PDO::PARAM_NULL);
			}
			$query->bindParam(':institution', $this->institution, PDO::PARAM_INT);
			$query->bindParam(':account_num', $this->account_num, PDO::PARAM_STR);
			$query->bindParam(':inst_email', $this->inst_email, PDO::PARAM_STR);
			$query->bindParam(':comm_email', $this->comm_email, PDO::PARAM_STR);
			if($this->profile != 0){
				$query->bindParam(':profile', $this->profile, PDO::PARAM_INT);
			} else {
				$query->bindParam(':profile', null, PDO::PARAM_NULL);
			}
			$query->bindParam(':unit_faculty', $this->unit_faculty, PDO::PARAM_STR);
			$query->bindParam(':role', $this->role, PDO::PARAM_STR);
			$query->bindParam(':preset_user_id', $this->presetuser_id, PDO::PARAM_INT);
			$query->bindParam(':active', $this->active, PDO::PARAM_INT);
			$query->bindParam(':rejected', $this->rejected, PDO::PARAM_INT);
			print_r($this->suspended);
			$query->bindParam(':suspended', $this->suspended, PDO::PARAM_INT);

			$query->execute();

		} catch(PDOException $e){
			print_r('save: ' . $e->getMessage());
			die;
		}
		return true;
	}

	static function find($pdo, $args = array()){
		$sql = "SELECT * FROM users";
		if(!empty($args)) {
			$sql_array = array();
			foreach($args as $key => $value){
				$sql_array[] = "$key = '$value'";
			}
			$sql = $sql . " WHERE " . implode(' AND ', $sql_array);
		}
		try {
			$result = $pdo->query($sql);
		} catch(PDOException $e){
			print_r($e->getMessage());
			die;
		}
		$users = array();
		foreach($result as $row){
			$user = new self($pdo, $row['id']);
			$users[] = $user;
		}
		return $users;
	}

}
