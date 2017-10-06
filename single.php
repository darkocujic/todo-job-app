<?php

require_once('session.php');
require_once('includes/user.php');
require_once('includes/list.php');
require_once('includes/task.php');

$user_auth = new User();
$singlelist = new TodoList();
$singletask = new Task();
$userid = $_SESSION['user_session'];

$username = $user_auth->getUserName($userid);

if (isset($_GET['id'])){
	$listid = $_GET['id'];
	
	$lista = array();
	$lista = $singlelist->getSingleList($listid);

}
else {
	$user_auth->redirect('dashboard.php');
}

$totaltask = count($singlelist->getTask($listid));


if ($totaltask == 0){
	$unfinished = '0';
	$percent = '0';
	$finished = '0';
}
else {
	$unfinished = count($singlelist->getUnfinishedTask($listid));
	$finished = ($totaltask - $unfinished);
	$percent = round((($totaltask-$unfinished) / $totaltask)*100, 0);
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

	<title>To-Do :: LIST MAKER :: PRIKAZ LISTE</title>

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
			<div class="button-back">
				<a href="dashboard.php">
					<button type="button" class="botun-nazad btn"><i class="glyphicon glyphicon-chevron-left"></i>&nbsp;DASHBOARD</button>
				</a>
			</div>
			<div class="list-info" data-id="<?php echo $lista['id']; ?>">
				<div class="lista-ime">
					<h1><?php echo $lista['list_name']; ?></h1>
				</div>
				<div class="list-info-details">
					<div class="list-details list-details-out">
						<i class="glyphicon glyphicon-time"></i>&nbsp;
						<?php echo $lista['create_date']; ?>
					</div>
					<div class="list-details">
						<i class="glyphicon glyphicon-exclamation-sign" title="ukupno taskova"></i>&nbsp;
						<span class="totaltask"><?php echo $totaltask; ?></span>
					</div>
					<div class="list-details">
						<i class="glyphicon glyphicon-remove-sign" title="nedovršenih taskova"></i>&nbsp;
						<span class="unfinished"><?php echo $unfinished; ?></span>
					</div>
					<div class="list-details list-details-out">
						<i class="glyphicon glyphicon-ok-sign"></i>&nbsp;
						<div class="progress" style="">
  							<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $finished; ?>" aria-valuemin="0" aria-valuemax="<?php echo $totaltask; ?>" style="width:<?php echo $percent; ?>%">
  								<?php echo $percent . '%'; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="sort">
				<div class="add-new-div">
					<a href="#">
						<button type="button" class="btn btn-new-task"><i class="glyphicon glyphicon-plus"></i>&nbsp;DODAJ NOVI TASK</button>
					</a>
				</div>
				<div class="sort-by">
					<label>Sortiraj po: </label>
					<select class="form-control" id="sortby" name="sortby">
						<option selected value="dead">roku izvršetka</option>
						<option value="abc">nazivu</option>
						<option value="status">statusu</option>
						<option value="prior">prioritetu</option>
					</select>
				</div>
			</div>
			<div id="ispis" class="single-ispis">
				<?php
					$result = $singletask->outputTasks($listid);

					echo $result;
				?>
			</div>
			<div class="sort">
				<div class="delete-entire-list">
					<button type="button" class="btn btn-del-list"><i class="glyphicon glyphicon-remove"></i>&nbsp;IZBRIŠI LISTU</button>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
		<p>To-Do List maker, 2016. Darko Cujic</p>
	</footer>

<!-- edit-task -->
<div class="single-list single-list-temp">
	<div class="inside-single-task-edit">
		<form class="add-new-task-list" id="add-task">
			<div class="ime-liste ime-liste-edit">
				<input type="text" name="txt_task_name" id="name-task" class="form-control" placeholder="Upiši ime taska" required  />
			</div>
			<div class="prioritet-task prioritet-task-edit">
				<select class="form-control" id="prior-select" name="priority">
					<option selected value="0">low</option>
					<option value="1">normal</option>
					<option value="2">high</option>
				</select>
			</div>
			<div class="datum-list datum-list-edit">
				<i class="glyphicon glyphicon-calendar"></i>
				<div class="date-input">
					<input class="form-control" type="datetime-local" value="" id="datepicker" value="1970-00-00T00-01">	
				</div>
			</div>
			<div class="submit-task-edit check-task">
				<a class="submit-button" href="#"><i title="prihvati" class="glyphicon glyphicon-ok"></i></a>
			</div>
			<div class="delete-list delete-list-edit">
				<a class="delete-button-edit" href="#"><i title="odbaci" class="glyphicon glyphicon-remove"></i></a>
			</div>
		</form>
	</div>
</div>

	<script>
		$(document).ready(function(){
			$('body').on('click', '.check-button', function(e){
				var id = $(this).data("id");
				var listid = $('.list-info').data("id");
				var status = $(this).data("status");

				var total =parseInt($('.progress-bar').attr('aria-valuemax'), 10);
				var finished = $('.progress-bar').attr('aria-valuenow');
				if (parseInt(status, 10) == 1){
					var newfin = parseInt(finished, 10) - 1;
				} else {
					var newfin = parseInt(finished, 10) + 1;
				}
				var percent = Math.round((newfin / total) * 100);

				$.ajax({
					method: "POST",
					url: "func_task.php",
					data: { sortby:$("#sortby").val(), checkid:id, currlist:listid }
				})
				.done(function(result) {
					$('.progress-bar').attr('aria-valuenow', newfin);
					$('.progress-bar').css('width', percent+'%');
					$('.progress-bar').text(percent+'%');
					$('.unfinished').text(total-newfin);
					$("#ispis").html(result);
				});
			});
			$('body').on('click', '.btn-del-list', function(e){
				var listid = $('.list-info').data("id");
				$.ajax({
					method: "POST",
					url: "functions.php",
					data: { deleteid:listid }
				})
				.done(function() {
					window.location.href = 'dashboard.php';
				})
			});
			$('body').on('click', '.delete-button', function(e){
				var id = $(this).data("id");
				var listid = $('.list-info').data("id");
				var total = parseInt($('.progress-bar').attr('aria-valuemax'), 10);
				total = total - 1;
				var finished = parseInt($('.progress-bar').attr('aria-valuenow'), 10);
				var unfinished = parseInt($('.unfinished').text(), 10);

				var status = $(this).closest('.single-list').find('a.check-button').data("status");
				if (status == 1){
					finished = finished - 1;
				} else {
					unfinished = unfinished - 1;
				}
				if (total == 0){
					var percent = 0;
				} else{
					var percent = Math.round((finished / total) * 100);
				}
				$.ajax({
					method: "POST",
					url: "func_task.php",
					data: { sortby:$("#sortby").val(), deleteid:id, currlist:listid }
				})
				.done(function(result) {
					$('.totaltask').text(total);
					$('.unfinished').text(unfinished);
					$('.finished').text(finished);
					$('.progress-bar').attr('aria-valuemax', total);
					$('.progress-bar').css('width', percent+'%');
					$('.progress-bar').text(percent+'%');
					$("#ispis").html(result);
				});
			});
			$("#sortby").change(function(){
				var listid = $('.list-info').data("id");
				$.ajax({
					method: "POST",
					url: "func_task.php",
					data: { currlist:listid, sortby:$("#sortby").val() }
				})
				.done(function(result) {
					$("#ispis").html(result);
				});
			});
			$('body').on('click', '.btn-new-task', function(e){
				$("#ispis").append( $('.single-list-temp').clone().addClass('single-list-edit') );
				$('.single-list-edit').show();
			});
			$('body').on('click', '.delete-button-edit', function(e){
				$('.single-list-edit').remove();
			});
			$('body').on('click', '.single-list-edit .submit-button', function(e){
				var name = $('.single-list-edit #name-task').val();
				var prior = $('.single-list-edit #prior-select').val();
				var date = $('.single-list-edit #datepicker').val();
				var id = $('.list-info').data("id");
				var total = parseInt($('.progress-bar').attr('aria-valuemax'), 10);
				var finished = parseInt($('.progress-bar').attr('aria-valuenow'), 10);

				total = total + 1;
				var percent = Math.round((finished / total) * 100);

				var unfinished = parseInt($('.unfinished').text(), 10);
				var unfin = unfinished + 1;
				$.ajax({
					method: "POST",
					url: "func_task.php",
					data: { upload:'1', datetime:date, prioritet:prior, taskname:name, sortby:$('#sortby').val(), currlist:id }
				})
				.done(function(result) {
					$('.progress-bar').attr('aria-valuemax', total);
					$('.progress-bar').css('width', percent+'%');
					$('.progress-bar').text(percent+'%');
					$('.totaltask').text(total);
					$('.unfinished').text(unfin);
					$('#ispis').html(result);
				});
			});
			$('body').on('click', '.edit-button', function(e){
				var listid = $('.list-info').data("id");
				var taskid = $(this).closest('.single-list').data("id");
				var task_name = $(this).closest('.single-list').find('.ime-liste').html();
				var task_prior = $(this).closest('.single-list').find('.prioritet-task').data("prior");

				$(this).closest('.single-list').prepend( $('.inside-single-task-edit').clone().addClass('single-task-edit') );
				$(this).closest('.single-list').find('.inside-single-task').hide();
				
				$('.single-task-edit #name-task').val(task_name);
				$('.single-task-edit #prior-select').val(task_prior);
				$('.single-task-edit').show();
			});
			$('body').on('click', '.single-task-edit .delete-button-edit', function(e){				
				$(this).closest('.single-list').find('.inside-single-task').show();
				$(this).closest('.single-list').find('.inside-single-task-edit').remove();
			});
			$('body').on('click', '.single-task-edit .submit-button', function(e) {
				var listid = $('.list-info').data("id");
				var taskid = $(this).closest('.single-list').data("id");
				var new_task_name = $('.single-task-edit #name-task').val();
				var new_prior = $('.single-task-edit #prior-select').val();
				var new_date = $('.single-task-edit #datepicker').val();
				$.ajax({
					method: "POST",
					url: "func_task.php",
					data: { edit:'1', taskname:new_task_name, prioritet:new_prior, sortby:$('#sortby').val(), currlist:listid, currtask:taskid, datetime:new_date }
				})
				.done(function(result) {
					$('#ispis').html(result);
				});
			});
		});
	</script>
</body>
</html>