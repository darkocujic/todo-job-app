<?php

require_once('session.php');
require_once('includes/user.php');
require_once('includes/list.php');
require_once('includes/task.php');

$user_auth = new User();
$singlelist = new TodoList();
$userid = $_SESSION['user_session'];

$username = $user_auth->getUserName($userid);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="utf-8">
	<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<title>To-Do :: LIST MAKER :: DASHBOARD</title>

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
					//echo $userrow['ime'] . ' ' . $userrow['prezime'];
					?>
					</p>
			</div>
			<div class="header-right">
				<a href="logout.php?logout=true"><i class="glyphicon glyphicon-log-out"></i>&nbsp;ODJAVA</a>
			</div>
		</header>
		<div class="main-cont">
			<div class="sort">
				<div class="add-new-div">
					<a href="add-new.php">
						<button type="button" class="btn btn-new-list"><i class="glyphicon glyphicon-plus"></i>&nbsp;DODAJ NOVU LISTU</button>
					</a>
				</div>
				<div class="sort-by">
					<label>Sortiraj po: </label>
					<select class="form-control" id="sortby" name="sortby">
						<option selected value="date">datumu kreiranja</option>
						<option value="abc">nazivu</option>
					</select>
				</div>
			</div>
			<div id="ispis">
				<?php
					$result = $singlelist->outputList();
					
					echo $result;
				?>
			</div>
			
		</div>
	</div>
	<footer class="footer">
		<p>To-Do List maker, 2016. Darko Cujic</p>
	</footer>
	<script>
		$(document).ready(function(){
			$("#sortby").change(function(){
				$.ajax({
					method: "POST",
					url: "functions.php",
					data: { sortby:$("#sortby").val() }
				})
				.done(function(result) {
					$("#ispis").html(result);
				});
			});

			$('body').on('click', '.delete-button', function(e){
				var id = $(this).data("id");
				console.log("klik");
				$.ajax({
					method: "POST",
					url: "functions.php",
					data: { sortby:$("#sortby").val(), deleteid:id }
				})
				.done(function(result) {
					$("#ispis").html(result);
				});
			});
		});
	</script>
</body>
</html>