<?php
	session_start();
	require_once('includes/list.php');
	$obj = new TodoList();


	if (array_key_exists('deleteid', $_POST)){
		$obj->deleteList($_POST['deleteid']);
	}
  	
   	$task = $obj->outputList($_POST['sortby']);

   	echo $task;
?>