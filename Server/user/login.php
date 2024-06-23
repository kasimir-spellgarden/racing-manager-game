<?php

if(!isset($IAmServer))
	exit;

CheckRequest("user", "pw");

$st = $mysqli->prepare("SELECT ID, Status, Mail, Name FROM UserAccounts WHERE Name = ? AND Password = ?");
$st->bind_param("ss", $_REQUEST['user'], $_REQUEST['pw']);
$st->execute();
$res = $st->get_result();

if($res->num_rows == 0)
{
	FailMsg("WRONG_LOGIN", "User was not found or wrong password.");
}

$user = $res->fetch_assoc();

$st = $mysqli->prepare("SELECT * FROM UserCompanies WHERE UserID = ?");
$st->bind_param("i", $user['ID']);
$st->execute();
$res = $st->get_result();
$user["Companies"] = array();
while($company = $res->fetch_assoc())
{
	$user["Companies"][] = $company;
}
SendToGame($user);
?>