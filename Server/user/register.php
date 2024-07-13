<?php

if(!isset($IAmServer))
	exit;

CheckRequest("user", "region", "pw", "mail", "company");

$st = $mysqli->prepare("SELECT ID, Name FROM UserAccounts WHERE Name = ?");
$st->bind_param("s", $_REQUEST['user']);
$st->execute();
$res = $st->get_result();
$st->close();
if($res->num_rows > 0)
{
	FailMsg("USER_EXISTS", "The username already exists.");
}

$st = $mysqli->prepare("SELECT ID, Name FROM UserAccounts WHERE Mail = ?");
$st->bind_param("s", $_REQUEST['mail']);
$st->execute();
$res = $st->get_result();
$st->close();
if($res->num_rows > 0)
{
	FailMsg("MAIL_EXISTS", "The mail adress already exists.");
}

$st = $mysqli->prepare("SELECT * FROM UserAccounts WHERE Company = ?");
$st->bind_param("i", $_REQUEST['company']);
$st->execute();
$res = $st->get_result();
$st->close();
if($res->num_rows > 0)
{
	FailMsg("COMPANY_EXISTS", "The company name is already in use.");
}

// Now create the user and create the new company.
$st = $mysqli->prepare("INSERT INTO `UserAccounts` (`ID`, `Region`, `Name`, `Password`, `Mail`, `Company`) VALUES (NULL, ?,?,?,?,?);");
$st->bind_param("sssss", $_REQUEST['region'], $_REQUEST['user'], $_REQUEST['pw'], $_REQUEST['mail'], $_REQUEST['company']);
$st->execute();
$st->close();

$st = $mysqli->prepare("SELECT ID, Status, Mail, Name, Company, CompanyFunds FROM UserAccounts WHERE Name = ?");
$st->bind_param("s", $_REQUEST['user']);
$st->execute();
$res = $st->get_result();
$user = $res->fetch_assoc();
$user["Teams"] = array();
$st->close();
SendToGame($user);

?>