<?php

require_once('includes/db.php');

class User {
	
	private $dbconn;

	public function __construct(){
		$database = new Db();
		$db = $database->dbConnect();
		$this->dbconn = $db;
	}

	public function runQuery($sql){
		$sqlquery = $this->dbconn->prepare($sql);

		return $sqlquery;
	}

	public function getUserName($userid){
		$sqlquery = $this->dbconn->prepare("SELECT *
									FROM korisnik
									WHERE id=:userid");
		$sqlquery->execute(array(":userid"=>$userid));
		$userrow = $sqlquery->fetch(PDO::FETCH_ASSOC);

		return $userrow['ime'] . '&nbsp;' . $userrow['prezime'];
	}

	public function register($email, $pass, $name, $surname){
		try{
			$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
			$confirm = md5(uniqid(rand()));
			
			$sqlquery = $this->dbconn->prepare("INSERT INTO korisnik(email, password, ime, prezime, status, reg_date, confirm_code)
												VALUES(:u_email, :u_pass, :u_name, :u_surname, '0', NOW(), :code)");
			
			$sqlquery->execute(array(":u_email"=>$email, ":u_pass"=>$hashed_pass, ":u_surname"=>$surname, ":u_name"=>$name, ":code"=>$confirm));

			$this->sendActivation($email, $name, $confirm);

			return $sqlquery;
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function sendActivation($email, $name, $confirm){
		$message = "Pozdrav, " . $name . ".";
		$message .= "Molimo kliknite na link da biste aktivirali svoj korisnički račun.\r\n";
		$message .= "localhost/todo_list/activate.php?confirm=" . $confirm;

		$header = "from ToDo list";

		$sendmail = mail($email, $header, $message);
	}

	public function isActive($email) {
		$query = $this->dbconn->prepare("SELECT status
										FROM korisnik
										WHERE email=:u_email");
		$query->execute(array(":u_email"=>$email));
		$active = $query->fetch(PDO::FETCH_ASSOC);
		if ($active['status'] == '1'){
			return true;
		} else {
			return false;
		}
	}

	public function LogIn($email, $pass){
		try {
			$sqlquery = $this->dbconn->prepare("SELECT email, password, id
												FROM korisnik
												WHERE email=:u_email ");
			$sqlquery->execute(array(':u_email'=>$email));
			$userRow = $sqlquery->fetch(PDO::FETCH_ASSOC);

			if ($sqlquery->rowCount() == 1){
				if (password_verify($pass, $userRow['password'])) {
					$_SESSION['user_session'] = $userRow['id'];

					$loginquery = $this->dbconn->prepare("UPDATE korisnik
														SET log_date=NOW()
														WHERE id=:u_date");
					$loginquery->execute(array(':u_date'=>'NOW()'));

					return true;
				} else {
					return false;
				}
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function loggedIn(){
		if(isset($_SESSION['user_session'])){
			return true;
		}
	}

	public function redirect($url){
		header("Location: $url");
	}

	public function LogOut(){
		session_destroy();
		unset($_SESSION['user_session']);

		return true;
	}

}