<?php

$sql = new SQLite3("data/data.sqlite");
$sql->exec("CREATE TABLE 'users' ('ID' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL ,'name' TEXT, 'password' TEXT,'ikaID' INTEGER NOT NULL, 'volno' INTEGER NOT NULL DEFAULT 0);");
$sql->exec("CREATE UNIQUE INDEX users_name ON users(name);");
$sql->exec("CREATE TABLE 'dohody' ('ID1' INTEGER, 'ID2' INTEGER);");

$sql->close();
