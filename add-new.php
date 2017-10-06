<?php
	require_once('session.php');
	require_once('includes/user.php');
	require_once('includes/list.php');

	$user_auth = new User();
	$singlelist = new TodoList();
	$userid = $_SESSION['user_session'];

	$username = $user_auth->getUserName($userid);

	if (isset($_POST['btn-add-list'])){
		$txtnewlist = strip_tags($_POST['txt_new_list']);

		$novi_id = $singlelist->addNewList($txtnewlist, $userid);
		
		if($singlelist){
			$user_auth->redirect('single.php?id=' . $novi_id);
		}
		else {
			$error = "Greška pri dodavanju novog unosa";
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

	<title>To-Do :: LIST MAKER :: DODAJ LISTU</title>

	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:700,300|Roboto:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="container">
		<header class="sticky-header">
			<div class="header-left">
				<p>Dobrodošli, 
					<?php 
					echo $username;
					?>
					</p>
			</div>
			<div class="header-right">
				<a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i>&nbsp;ODJAVA</a>
			</div>
		</header>
		<div class="main-cont">
			<div class="add-list-main">
				<a href="dashboard.php">
					<button type="button" class="botun-nazad btn btn-nazad-dodaj">
						<i class="glyphicon glyphicon-chevron-left"></i>&nbsp;DASHBOARD
					</button>
				</a>
				<div>
					<h1>Upišite naziv nove ToDo liste</h1>
				</div>
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
				<form class="form-add-list" method="POST" id="form-add">
					<input type="text" name="txt_new_list" class="list_name_txt" placeholder="Naziv nove liste" />
					<hr />
					<button type="submit" name="btn-add-list" class="btn button-add-list"><i class="glyphicon glyphicon-plus"></i>&nbsp;Dodaj</button>
				</form>
			</div>
		</div>
	</div>
	<footer class="footer">
		<p>To-Do List maker, 2016. Darko Cujic</p>
	</footer>
</body>
</html>
