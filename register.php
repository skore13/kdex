<?php

session_start();
$sql = new SQLite3("data/data.sqlite");
header('Location: /');
if(strlen($_POST["name"])<3 || strlen($_POST["name"]) > 15) {
	$_SESSION['error'] = "Jméno musí mít 3-15 znaků (zadejte stejné jméno jako v Ikariamu)";
	die();
}
$name = SQLite3::escapeString ($_POST["name"]);
$password = SQLite3::escapeString ($_POST["password"]);
$ikaid = intval($_POST["ikaID"]);
$success = $sql->exec("INSERT INTO \"users\" (\"name\",\"password\",\"ikaID\") VALUES (\"$name\",\"$password\",$ikaid)");

if($success) {
	$results = $sql->query('SELECT * FROM users WHERE name="'.$name.'"');
	$row = $results->fetchArray();

	$_SESSION['name'] = $name;
	$_SESSION['ID'] = $row['ID'];
	$_SESSION['ikaID'] = $row['ikaID'];
}else{
	$_SESSION["error"] = "Vytvoření uživatele selhalo. Možná už existuje?";
}
$sql->close();
