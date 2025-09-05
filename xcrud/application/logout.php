<?php
	require_once('session.php');
	require_once('protect.user.php');
	$user_logout = new USER();
	$base_url = config::get('base_url');
	$redirect_login = config::get('redirect_login');
	$redirect_logout = config::get('redirect_logout');

	if($user_logout->is_loggedin()!="")
	{
		$user_logout->redirect($redirect_login);
	}
	if(isset($_GET['logout']) && $_GET['logout']=="true")
	{
		$user_logout->doLogout();
		$user_logout->redirect($base_url.''.$redirect_logout); // if you are using page outside the directory
																// use full url starting with http://

	}

//Backup line 16 if you want to change the code above
//$user_logout->redirect($base_url.'/'.$redirect_logout);
//