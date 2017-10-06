<?php
	require_once('session.php');
	require_once('includes/user.php');
	$user_logout = new User();
	
	if($user_logout->loggedIn()!="")
	{
		$user_logout->redirect('dashboard.php');
	}
	if(isset($_GET['logout']) && $_GET['logout']=="true")
	{
		$user_logout->LogOut();
		$user_logout->redirect('index.php');
	}
