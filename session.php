<?php

	session_start();
	
	require_once('includes/user.php');
	$session = new User();
	
	if(!($session->loggedIn()))
	{
		$session->redirect('index.php');
	}