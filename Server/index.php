<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$IAmServer = true;

require_once "functions.php";

if(!isset($_POST["version"]))
{
	FailMsg("SERVER_VERSION_01", "Server version mismatch");
	exit;
}

$Game = array("Version" => "1");
$Game["Time"] = time();

/* You should enable error reporting for mysqli before attempting to make a connection */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$mysqli = new mysqli('localhost', 'd040bc16', 'qbjMMcAG9T4eriKkysTG', 'd040bc16');

if($mysqli->connect_errno)
{
	FailMsg("DATABASE", $mysqli->error);
}

if($_POST["version"] != $Game["Version"])
{
	FailMsg("SERVER_VERSION_02", "Server version mismatch");
	exit;
}

if(!isset($_REQUEST['path']))
{
	FailMsg("UNKNOWN_COMMAND_01", "Request is unknown.");
	exit;
}

$Game["Request"] = explode("/", $_REQUEST['path']);

switch($Game["Request"][0])
{
	case "Status":
		SendToGame(array("Data" => "Healthy"));
		exit;	
	case "User":
		require_once "user/main.php";
		exit;	
}

FailMsg("UNKNOWN_COMMAND_01", "Request is unknown.");
exit;
?>