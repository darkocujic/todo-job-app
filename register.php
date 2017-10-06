<?php

session_start();
require_once('includes/user.php');
$user = new User();

if ($user->loggedIn() != ""){
	$user->redirect('dashboard.php');
}

if (isset($_POST['btn-register'])){
	$email = strip_tags($_POST['txt_email']);
	$pass = strip_tags($_POST['txt_password']);
	$name = strip_tags($_POST['txt_name']);
	$surname = strip_tags($_POST['txt_surname']);

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error[] = "Upišite ispravnu mail adresu.";
	}
	else if($name == ""){
		$error[] = "Upišite ime.";
	}
	else if($surname == ""){
		$error[] = "Upišite prezime.";
	}
	else if($pass == ""){
		$error[] = "Upišite lozinku.";
	}
	else if(strlen($pass) < 6){
		$error[] = "Lozinka mora sadržavati najmanje 6 znakova";
	}
	else {
		try {
			$sqlquery = $user->runQuery("SELECT email
										FROM korisnik
										WHERE email=:u_email");
			$sqlquery->execute(array(":u_email"=>$email));

			$row = $sqlquery->fetch(PDO::FETCH_ASSOC);

			if($row['email'] == $email) {
				$error[] = "E mail adresa se već koristi, molimo probajte drugu.";
			}
			else {
				if($user->register($email, $pass, $name, $surname)){
					$user->redirect('register.php?registered');
				}
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="utf-8">

	<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	

	<title>To-Do :: LIST MAKER</title>

	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:700,300|Roboto:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="container">
		<header id="sticky-header">
			<div class="header-left">
				<p>Welcome to To-Do List maker.</p>
			</div>
		</header>
		<div class="main-cont">
			<div class="register-form-sve">
				<form method="post" class="form-register">
					<h2 class="form-register-naslov">Registrirajte se</h2>
					<hr />

					<?php
						if(isset($error)){
							foreach ($error as $error) {
					?>
								<div class="alert login-alert">
									<i class="icon-warning"></i> &nbsp; <?php echo $error; ?>
								</div>
					<?php
							}
						}
						else if(isset($_GET['registered'])){
							?>
							<div class="icon-login">
								</i> &nbsp; Poslan vam je mail s aktivacijom, a logirati se možete <a href="index.php">OVDJE</a><br />
							</div>
					<?php
						}
					?>

					<div class="form-group">
						<input type="text" class="form-control" name="txt_email" placeholder="Vaša email adresa" />
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="txt_password" placeholder="Lozinka" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="txt_name" placeholder="Ime" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="txt_surname" placeholder="Prezime" />
					</div>
					<hr />

					<div class="form-group">
						<button type="submit" class="btn button-login" name="btn-register">
							<i class="glyphicon glyphicon-log-in"></i>&nbsp;REGISTRIRAJ SE
						</button>
					</div>
					<hr />
				</form>
				<div>
					<label>Ukoliko već imate korisnički račun, logirajte se <a href="index.php">OVDJE</a></label>
				</div>
			</div>
		</div>
	</div>
	<div class="footer">
		<p>To-Do List maker, 2016. Darko Cujic</p>
	</div>
</body>
</html>