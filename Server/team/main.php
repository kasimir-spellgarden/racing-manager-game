<?php

if(!isset($IAmServer))
	exit;

CheckLoggedIn();

switch($Game["Request"][1])
{
	case "Create":
		require_once "create.php";
		exit;
}

?>