<?php

if(!isset($IAmServer))
	exit;


switch($Game["Request"][1])
{
	case "Login":
		require_once "login.php";
		exit;
	case "Register":
		require_once "register.php";
		exit;
	case "Transactions":
		CheckLoggedIn();
		require_once "transactions.php";
		exit;
}

?>