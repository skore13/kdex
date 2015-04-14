<?php

session_start();
$sql = new SQLite3("data/data.sqlite");

$name = SQLite3::escapeString ($_POST["name"]);
$password = SQLite3::escapeString ($_POST["password"]);

$results = $sql->query('SELECT * FROM users WHERE name="'.$name.'"');
$row = $results->fetchArray();

if($row == false) { // Uživatel nenalezen
	$_SESSION['error'] = "Takový uživatel neexistuje. Nejdříve se zaregistrujte.";
	header('Location: /');
	die();
}

if($password != $row["password"]){
	$_SESSION['error'] = "Heslo neodpovídá. Zkuste to prosím znovu.";
	header('Location: /');
	die();
}

$_SESSION['name'] = $name;
$_SESSION['ID'] = $row['ID'];
$_SESSION['ikaID'] = $row['ikaID'];

header('Location: /');
