<?php
session_start();
$sql = new SQLite3("data/data.sqlite");
header('Location: /');

if(!isset($_SESSION["name"])){
	die();
}

$who = intval($_POST["partnerID"]);
$sql->exec("DELETE FROM dohody WHERE ID1=$who AND ID2=".$_SESSION['ID']);
$sql->exec("DELETE FROM dohody WHERE ID2=$who AND ID1=".$_SESSION['ID']);

$add = $_POST["add"];
if($add == "yes") {
	$sql->exec('UPDATE "users" SET volno=(SELECT volno FROM users WHERE ID = '.$_SESSION['ID'].')+1 WHERE ID = '.$_SESSION['ID']);
}

