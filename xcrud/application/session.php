<?php

	session_start();
	
	require_once 'protect.user.php';
	$session = new USER();
	$url =  "{$_SERVER["REQUEST_URI"]}";
	$base_url = config::get('base_url');

	// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	
	if(!$session->is_loggedin())
	{
		// session no set redirects to login page
		$session->redirect($base_url.'/login.php?return='.$url);
	}