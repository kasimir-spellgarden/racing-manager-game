<?php

if(!isset($IAmServer))
	exit;

$st = $mysqli->prepare("SELECT * FROM UserTransactions WHERE UserID = ? ORDER BY ID ASC LIMIT 0, 100");
$st->bind_param("i", $User['ID']);
$st->execute();
$res = $st->get_result();

$transactions = array();

while ($transaction = $res->fetch_assoc())
{
	$transactions[] = $transaction;
}

SendToGame($transactions);

?>