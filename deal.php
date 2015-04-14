<?php
session_start();
$sql = new SQLite3("data/data.sqlite");
header('Location: /');

if(!isset($_SESSION["name"])){
	die();
}

$who = intval($_POST["partnerID"]);
$sql->exec("INSERT INTO dohody (ID1,ID2) VALUES (".$_SESSION['ID'].",".$who.")");

$time = $_POST["time"];
if($time == "now") {
	$sql->exec('UPDATE "users" SET volno=(SELECT volno FROM users WHERE ID = '.$_SESSION['ID'].')-1 WHERE ID = '.$_SESSION['ID']);
}

