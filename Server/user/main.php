<?php

if(!isset($IAmServer))
	exit;


switch($Game["Request"][1])
{
	case "Login":
		require_once "login.php";
		exit;
}

?>