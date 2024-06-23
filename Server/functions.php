<?php

if(!isset($IAmServer))
	exit;

function SendToGame($data)
{
	global $Game;

	header('Content-Type: application/json; charset=utf-8');
	if(!is_array($data))
	{
		$data = array('Result' => array("Data" => $data));
	} else {
		$data = array('Result' => $data);
	}

	$data["Info"] = $Game;
	if(!isset($data["Status"]))
		$data["Status"] = "OK";
	echo json_encode($data);
	exit;
}

function FailMsg($code, $msg) {
	echo SendToGame(array("Status" => "ERR", "Code" => $code, "Message" => $msg));
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
?>