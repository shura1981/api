<?php
//servidor, usuario de base de datos, contraseña del usuario, nombre de base de datos
// $mysqlElite = new mysqli("localhost","root","Excalibur1225*","intranet"); 

$username = "SYSTEM";
$password = "B1HanaAdmin";
$dsn = "odbc:serverara";
$dbh = new PDO($dsn, $username, $password);
//$dbh->set_charset("utf8mb4");
