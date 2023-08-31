<?php
//servidor, usuario de base de datos, contraseña del usuario, nombre de base de datos
// $mysqlElite = new mysqli("localhost","root","Excalibur1225*","intranet"); 
$mysqlNumerica = new mysqli("localhost","root","","bd_numericas"); 
$mysqlNumerica->set_charset("utf8mb4");
if(mysqli_connect_errno()){
echo 'Conexión Fallida : ', mysqli_connect_error();
exit();
}
