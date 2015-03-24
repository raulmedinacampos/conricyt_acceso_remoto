<?php
class User {
	private $pdo;
	private $id = 0;
	public $firstname;
	public $lastname1;
	public $lastname2;
	public $rfc;
	public $entidad;
	public $gender;
	public $institution;
	public $account_num;
	public $inst_email;
	public $comm_email;
	public $profile;
	public $deleg_imss;
	public $clave_est_org;
	public $unit_faculty;
	public $role;
	public $presetuser_id = null;
	public $active;
	public $rejected;
	public $suspended;

	function __construct( $pdo, $id = false , $first = '', $last1 = '', $last2 = '',  
	$rfc = '', $entidad = '', $gender = '', $institution = '', $account_num = '',
	$inst_email = '', $comm_email = '', $profile = '', 
	$deleg_imss = '', $clave_est_org = '', 
	$unit_faculty = '', $role = '') {
		$this->pdo = $pdo;
		if((bool)$id){
			$this->byId($id);
		} 
		if(!empty($first)){
			$this->firstname = $first;
			$this->lastname1 = $last1;
			$this->lastname2 = $last2;
			$this->rfc = $rfc;
			$this->entidad = $entidad;
			$this->gender = $gender;
			$this->institution = $institution;
			$this->account_num = $account_num;
			$this->inst_email = $inst_email;
			$this->comm_email = $comm_email;
			$this->profile = $profile;
			$this->deleg_imss = $deleg_imss;
			$this->clave_est_org = $clave_est_org;
			$this->unit_faculty = $unit_faculty;
			$this->role = $role;
			$this->active = false;
			$this->rejected = false;
			$this->suspended = false;
		}
	}
	
	private function formatearNombre($cadena) {
		$excepciones = array('de', 'la', 'de la', 'del', 'y');
		$cadena = trim($cadena);
		$partes = explode(" ", $cadena);
		$encoding = 'UTF-8';
			
		for($i=0; $i<sizeof($partes); $i++) {
			$firstChar = mb_substr($partes[$i], 0, 1, $encoding);
			$then = mb_substr($partes[$i], 1, mb_strlen($partes[$i], $encoding)-1, $encoding);
			$partes[$i] = mb_strtoupper($firstChar, $encoding) . mb_strtolower($then, $encoding);
		}
			
		for($i=0; $i<sizeof($partes); $i++) {
			for($j=0; $j<sizeof($excepciones); $j++) {
				if(mb_strtolower($partes[$i], $encoding) == $excepciones[$j]) {
					$partes[$i] = mb_strtolower($partes[$i], $encoding);
				}
			}
		}
			
		$cadena = implode(" ", $partes);
		return $cadena;
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
		$this->lastname1 = $user['lastname1'];
		$this->lastname2 = $user['lastname2'];
		$this->rfc = $user['rfc'];
		$this->entidad = $user['entidad'];
		$this->gender = $user['gender'];
		$this->institution = $user['institution'];
		$this->account_num = $user['account_num'];
		$this->inst_email = $user['inst_email'];
		$this->comm_email = $user['comm_email'];
		$this->profile = $user['level'];
		$this->deleg_imss = $user['deleg_imss'];
		$this->clave_est_org = $user['clave_est_org'];
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
	
	public function generateCredentialsOld(){
		$next = new PresetUsersOld($this->pdo, $this);
		if(empty($next->id)){
			return false;
		}
		$this->presetuser_id = $next->id;
		//$this->presetuser_id = $next->Insert($this->pdo, $this);
		$this->active = true;
		$this->rejected = false;
		$this->suspended = false;
		
		$next->Used($this->pdo);
		return array(
				'username' => $next->username,
				'password' => $next->password
		);
	}

	public function generateCredentials(){
		$next = new PresetUsers($this->pdo, $this);
		if(empty($next->id)){
			return false;
		}
		//$this->presetuser_id = $next->id;
		$this->presetuser_id = $next->Insert($this->pdo, $this);
		$this->active = false;
		$this->rejected = false;
		$this->suspended = false;
		
		//$next->Used($this->pdo);
		return array(
			'username' => $next->username,
			'password' => $next->password
		);
	}
	
	// Metodo agregado por Raul Medina. Cambia el estatus del campo user.active a 1
	public function approveUser() {
		$this->active = true;
		$this->rejected = false;
		$this->suspended = false;
		
		return true;
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
	
	public function Delegacion()
	{
		$deleg = $this->pdo->prepare("SELECT * FROM cat_deleg_imss WHERE id = ?");
		$deleg->execute(array($this->deleg_imss));
		$deleg = $deleg->fetch();
		return $deleg['delegacion'];
	}

	public function Profile()
	{
		$profile = $this->pdo->prepare("SELECT * FROM level WHERE id = ?");
		$profile->execute(array($this->profile));
		$profile = $profile->fetch();
		return $profile['name'];
	}
	
	public function createUsername() {
		// Código para generar el nombre de usuario de acuerdo al nombre corto de la institución
		$query = $this->pdo->prepare("INSERT INTO preset_users VALUES(NULL, 'usuarioprueba', 'password', 1, 1, NULL");
		$query->execute();
		return "nombreDeUsuario";
	}

	public function save()
	{
		try {
			$accion = "";
			$stmt = $this->pdo->prepare("Select * FROM users WHERE id = :id");
			$stmt->execute(array(':id' => $this->id));
			if($stmt->fetchColumn() > 0){
				$accion = "u";
				$query = $this->pdo->prepare("UPDATE users set
					firstname = :firstname,
			 		lastname1 = :lastname1,
			 		lastname2 = :lastname2,
					rfc = :rfc,
					entidad = :entidad,
			 		gender = :gender,
			 		institution = :institution,
			 		account_num = :account_num,
			 		inst_email = :inst_email,
			 		comm_email = :comm_email,
			 		level = :profile,
					deleg_imss = :deleg_imss,
					clave_est_org = :clave_est_org,
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
				$accion = "i";
				$stmt = $this->pdo->prepare("Select * FROM users WHERE comm_email = :comm_email");
				$stmt->execute(array(':comm_email' => $this->comm_email));
				if($stmt->fetchColumn() == 0){
					$query = $this->pdo->prepare("INSERT INTO users VALUES(
						:id,
						:firstname,
						:lastname1,
						:lastname2,
						:rfc,
						:entidad,
						:gender,
						:institution,
						:account_num,
						:inst_email,
						:comm_email,
						:profile,
						:deleg_imss,
						:clave_est_org,
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
			$query->bindParam(':firstname', $this->formatearNombre($this->firstname), PDO::PARAM_STR);
			$query->bindParam(':lastname1', $this->formatearNombre($this->lastname1), PDO::PARAM_STR);
			$query->bindParam(':lastname2', $this->formatearNombre($this->lastname2), PDO::PARAM_STR);
			$query->bindParam(':rfc', $this->rfc, PDO::PARAM_STR);
			$query->bindParam(':entidad', $this->entidad, PDO::PARAM_INT);
			if($this->gender != 0){
				$query->bindParam(':gender', $this->gender, PDO::PARAM_INT);
			} else {
				$query->bindParam(':gender', null, PDO::PARAM_NULL);
			}
			$query->bindParam(':institution', $this->institution, PDO::PARAM_INT);
			$query->bindParam(':account_num', $this->account_num, PDO::PARAM_STR);
			$query->bindParam(':inst_email', strtolower($this->inst_email), PDO::PARAM_STR);
			$query->bindParam(':comm_email', strtolower($this->comm_email), PDO::PARAM_STR);
			if($this->profile != 0){
				$query->bindParam(':profile', $this->profile, PDO::PARAM_INT);
			} else {
				$query->bindParam(':profile', null, PDO::PARAM_NULL);
			}
			$query->bindParam(':deleg_imss', $this->deleg_imss, PDO::PARAM_STR);
			$query->bindParam(':clave_est_org', $this->clave_est_org, PDO::PARAM_STR);
			$query->bindParam(':unit_faculty', $this->unit_faculty, PDO::PARAM_STR);
			$query->bindParam(':role', $this->role, PDO::PARAM_STR);
			$query->bindParam(':preset_user_id', $this->presetuser_id, PDO::PARAM_INT);
			$query->bindParam(':active', $this->active, PDO::PARAM_INT);
			$query->bindParam(':rejected', $this->rejected, PDO::PARAM_INT);
			$query->bindParam(':suspended', $this->suspended, PDO::PARAM_INT);

			$query->execute();

		} catch(PDOException $e){
			print_r('save: ' . $e->getMessage());
			die;
		}
		if($accion == "i") {
			return $this->pdo->lastInsertId();
		} else {
			return true;
		}
	}

	static function find($pdo, $args = array()){
		$sql = "SELECT * FROM users";
		if(!empty($args)) {
			$sql_array = array();
			foreach($args as $key => $value){
				$condicion = explode(" ", $key);
				$operador = "";
				if(sizeof($condicion) > 1) {
					$campo = $condicion[0];
					for($i=1; $i<sizeof($condicion); $i++) {
						$operador .= $condicion[$i] . " ";
					}
					$operador = trim($operador);
					if($value == "NULL") {
						$sql_array[] = "$campo $operador $value";
					} else {
						$sql_array[] = "$campo $operador '$value'";
					}
				} else {
					$sql_array[] = "$key = '$value'";
				}
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
