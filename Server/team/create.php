<?php

if(!isset($IAmServer))
	exit;

CheckRequest("name", "category", "region");

$st = $mysqli->prepare("SELECT Name FROM UserTeams WHERE Name = ?");
$st->bind_param("s", $_REQUEST['name']);
$st->execute();
$res = $st->get_result();

if($res->num_rows > 0)
{
	FailMsg("TEAM_EXISTS", "Team name already exsits.");
}

if(!isset($Settings["Cost"]["TeamCreate"][$_REQUEST['category']]))
{
	FailMsg("CATEGORY_MISSING", "The category does not exist.");
}

$cost = $Settings["Cost"]["TeamCreate"][$_REQUEST['category']];

TryCompanyTransaction($cost, "Team/Create", 0, "Fee for creating a " . $_REQUEST['category'] . " team");

$st = $mysqli->prepare("INSERT INTO `UserTeams` (`ID`, `UserID`, `Name`, `Category`, `Region`) VALUES (NULL, ?,?,?,?);");
$st->bind_param("ssss", $_REQUEST['user_id'], $_REQUEST['name'], $_REQUEST['category'], $_REQUEST['region']);
$st->execute();
$st->close();

$st = $mysqli->prepare("SELECT * FROM UserTeams WHERE Name = ?");
$st->bind_param("s", $_REQUEST['name']);
$st->execute();
$res = $st->get_result();
$team = $res->fetch_assoc();
$st->close();

SendToGame($team);

?>