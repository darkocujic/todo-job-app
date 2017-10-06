<?php
	session_start();
	require_once("includes/user.php");
	$login = new User();

	if($login->loggedIn() != ""){
		$login->redirect('dashboard.php');
	}

	if(isset($_POST['btn-login'])){
		$u_email = strip_tags($_POST['txt_email']);
		$u_pass = strip_tags($_POST['txt_password']);

		if ($login->LogIn($u_email, $u_pass)){
			if($login->isActive($u_email)){
				$login->redirect('dashboard.php');
			}
			else {
				$error = "Korisnički račun nije aktiviran.";
			}
		}
		else {
			$error = "Neispravni email ili lozinka. Molimo unesite ponovno.";
		}		
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="utf-8">

	<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
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
			<div class="login-main">
				<form class="form-signin" method="post" id="login-form">
					<h2 class="form-signin-naslov">Logirajte se u To-Do</h2>
					<hr />

					<div id="error">
						<?php
						if (isset($error)){
							?>
							<div class="alert login-alert">
								<i class="icon-warning"></i> <?php echo $error; ?>
							</div>
						<?php
						}
						?>
					</div>

					<div class="form-group">
						<input type="text" class="form-control" name="txt_email" placeholder="Vaša e-mail adresa" required />
						<span id="check-e"></span>
					</div>

					<div class="form-group">
						<input type="password" class="form-control" name="txt_password" placeholder="Vaša lozinka" />
					</div>

					<hr />

					<div class="form-group">
						<button type="submit" name="btn-login" class="btn button-login">
							<i class="glyphicon glyphicon-log-in"></i> Log In
						</button>
					</div>

					<br />
					<label>Ukoliko nemate korisnički račun, kreirajte ga <a href="register.php">OVDJE</a></label>

				</form>
			</div>
		</div>

		<div class="footer">
			<p>To-Do List maker, 2016. Darko Cujic</p>
		</div>
	</div>
</body>
</html>

	