<?php
	session_start();
	require_once('includes/task.php');
	$obj = new Task();

	if (array_key_exists('checkid', $_POST)){
		$obj->checkTask($_POST['checkid']);
	}

	if (array_key_exists('deleteid', $_POST)){
		$obj->deleteTask($_POST['deleteid']);
	}

	if (array_key_exists('upload', $_POST)){
		$obj->addTask($_POST['taskname'], $_POST['prioritet'], $_POST['datetime'], $_POST['currlist']);
	}

	if (array_key_exists('edit', $_POST)){
		$obj->editTask($_POST['taskname'], $_POST['prioritet'], $_POST['datetime'], $_POST['currlist'], $_POST['currtask']);
	}
  	
   	$task = $obj->outputTasks($_POST['currlist'], $_POST['sortby'], $_POST['currlist']);

   	echo $task;
?>