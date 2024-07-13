<?php

if(!isset($IAmServer))
	exit;

CheckRequest("user", "pw");

$st = $mysqli->prepare("SELECT ID, Status, Mail, Name, Company, CompanyFunds, Password FROM UserAccounts WHERE Name = ? AND Password = ?");
$st->bind_param("ss", $_REQUEST['user'], $_REQUEST['pw']);
$st->execute();
$res = $st->get_result();

if($res->num_rows == 0)
{
	FailMsg("WRONG_LOGIN", "User was not found or wrong password.");
}

$User = $res->fetch_assoc();
$User["SessionTime"] = time();
$User["SessionToken"] = password_hash(time() . "-" . rand() . $Game["Version"] . $User["ID"] . $User["Password"], PASSWORD_DEFAULT);

unset($User["Password"]);

$st = $mysqli->prepare("UPDATE UserAccounts SET SessionTime = ?, SessionToken = ? WHERE ID = ?");
$st->bind_param("isi", $User["SessionTime"], $User["SessionToken"], $User['ID']);
$st->execute();
$st->close();

$st = $mysqli->prepare("SELECT * FROM UserTeams WHERE UserID = ?");
$st->bind_param("i", $User['ID']);
$st->execute();
$res = $st->get_result();
$st->close();
$User["Teams"] = array();
while($company = $res->fetch_assoc())
{
	$User["Teams"][] = $company;
}

SendToGame($User);

?>