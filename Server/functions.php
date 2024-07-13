<?php

if(!isset($IAmServer))
	exit;

$User = null;
// a week of session time without a ping
define("__SESSION_DURATION__", 60*60*24*7);

function SendToGame($data)
{
	global $Game, $User;

	header('Content-Type: application/json; charset=utf-8');
	if(!is_array($data))
	{
		$data = array('Result' => array("Data" => $data));
	} else {
		$data = array('Result' => $data);
	}

	if(isset($User) && $User != null)
	{
		$data["User"] = $User;
	}

	$data["Info"] = $Game;
	if(!isset($data["Status"]))
		$data["Status"] = "OK";
	echo json_encode($data);
	exit;
}

function FailMsg($code, $msg) {
	echo SendToGame(array("Status" => "ERR", "Code" => $code, "Message" => $msg, "Request" => $_REQUEST));	
}

function CheckRequest(... $needed)
{
	foreach ($needed as $value) {
		if(!isset($_REQUEST[$value]))
		{
			FailMsg("PARAM_MISSING", "Missing parameter " . $value);
		}
	}
}

function CheckLoggedIn(){
	global $mysqli, $User;

	CheckRequest("session_token");
	
	$st = $mysqli->prepare("SELECT * FROM UserAccounts WHERE SessionToken = ?");
	$st->bind_param("s", $_REQUEST['session_token']);
	$st->execute();
	$res = $st->get_result();
	$st->close();
	if($res->num_rows == 0)
	{
		FailMsg("WRONG_SESSION_TOKEN", "User was not found or wrong password.");
	}
	$User = $res->fetch_assoc();
	if($User["SessionTime"] + __SESSION_DURATION__ < time())
	{
		FailMsg("EXPIRED_SESSION", "Your session has expired.");
	}
}

function TryUserTransaction($cost, $category, $categoryID, $description)
{
	global $mysqli, $User;

	if($cost > 0 && $User["Funds"] < $cost)
	{
		FailMsg("NOT_ENOUGH_FUNDS", "You can't afford this: " . $description . " for " . $cost . ". You have " . $User["Funds"]);
	}

	$User["Funds"] -= $cost;

	$st->prepare("INSERT INTO `UserTransactions` (`ID`, `Time`, `UserID`, `Amount`, `Category`, `CategoryID`, `Description`) VALUES (NULL, current_timestamp(), ?, ?, ?, ?, ?);");
	$category = "Private/" .$category;
	$st->bind_param("iisis", $User["ID"], $cost, $category, $categoryID, $description);
	$st->execute();
	$st->close();
	if($cost > 0)
	{
		$st->prepare("UPDATE `UserAccounts` SET Funds = Funds + ? WHERE ID = ?");
	} else {
		$cost = $cost * -1;
		$st->prepare("UPDATE `UserAccounts` SET Funds = Funds - ? WHERE ID = ?");
	}
	$st->bind_param("ii", $cost, $User["ID"]);
}

function TryCompanyTransaction($cost, $category, $categoryID, $description)
{
	global $mysqli, $User;

	if($cost > 0 && $User["CompanyFunds"] < $cost)
	{
		FailMsg("NOT_ENOUGH_FUNDS", "You can't afford this: " . $description . " for " . $cost . ". You have " . $User["Funds"]);
	}

	$User["CompanyFunds"] -= $cost;

	$st = $mysqli->prepare("INSERT INTO `UserTransactions` (`ID`, `Time`, `UserID`, `Amount`, `Category`, `CategoryID`, `Description`) VALUES (NULL, current_timestamp(), ?, ?, ?, ?, ?);");
	$category = "Company/" .$category;
	$st->bind_param("iisis", $User["ID"], $cost, $category, $categoryID, $description);
	$st->execute();
	$st->close();

	if($cost > 0)
	{
		$st = $mysqli->prepare("UPDATE `UserAccounts` SET CompanyFunds = CompanyFunds - ? WHERE ID = ?");
	} else {
		$cost = $cost * -1;
		$st = $mysqli->prepare("UPDATE `UserAccounts` SET CompanyFunds = CompanyFunds + ? WHERE ID = ?");
	}
	$st->bind_param("ii", $cost, $User["ID"]);
	$st->execute();
	$st->close();
}


?>