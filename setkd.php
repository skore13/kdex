<?php

session_start();
$sql = new SQLite3("data/data.sqlite");
header('Location: /');

if(!isset($_SESSION["name"])){
	die();
}


$num = intval($_POST['kdcount']);
$sql->exec('UPDATE "users" SET volno='.$num.' WHERE ID = '.$_SESSION['ID']);
