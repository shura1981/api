<?php
//Cargamos el framework
require_once 'vendor/autoload.php';
require 'connections/connection_hana.php';
require 'connections/connection_numericas.php';
date_default_timezone_set('America/Bogota');
set_time_limit(0);
ini_set('allow_url_fopen', 1);
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '500M');
ini_set('max_input_time', 4000); // Play with the values
ini_set('max_execution_time', 4000); // Play with the values
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
die();
}
$dominio="http://localhost/api/";
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=UTF-8'); 
//Devuelve los principales datos de un cliente filtrado el número de cédula
$app->get('/client-json', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region ejecutar los procesos.
try{
require_once './models/clientes.php';
$id= $app->request()->params('id');
$cliente= new tb_clientes($dbh);
$data= $cliente->getData($id);
if($data !=null){
$app->response()->status(200);
$result =$data;
}else{
$app->response()->status(404);
$result =array("message"=>"sin resultado");
}
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
}
//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});
//Devuelve los números telefónicos de un clientes
$app->get('/phones-json', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region ejecutar los procesos.
try{
require_once './models/clientes.php';
$id= $app->request()->params('id');
$cliente= new tb_clientes($dbh);
$data= $cliente->getPhones($id);
if($data !=null){
$app->response()->status(200);
$result =$data;
}else{
$app->response()->status(401);
$result =array("message"=>"sin resultado");
}
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
}
//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});
//Devuelve los clientes activos e inactivos filtrados por vendedor
$app->get('/clients-seller', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region ejecutar los procesos.
$id = $app->request()->params('id');
$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
require_once './models/clientes.php';
require_once './models/querys.php';
$cliente= new tb_clientes($dbh);
$sql= QUERYS::joinClientesInactivos($id);
$resul=array();
try {
$stmt = $dbh->prepare($sql);
$stmt -> execute();
$result = $stmt->fetchAll();
$inactivos = array();
$activos = array();
for($i=0; $i<count($result); $i++){
$id_cliente = $result[$i]['CardCode'];    
$sql2= QUERYS::ClientesInactivos($id_cliente, $ini, $fin);
$stmt2 = $dbh->prepare($sql2);
$stmt2 -> execute();
$result2 = $stmt2->fetchAll(); 
$ID=$result[$i]['CardCode'];
$d= $cliente->getData($ID);
if(count($result2) == 0)array_push($inactivos,base64_encode(json_encode($d)));
else array_push($activos,base64_encode(json_encode($d)));
}
//region sucursales
if($id==28){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C8909006081","nombres"=>"ALMACENES EXITO sede CR 51B 87 50","teléfono"=>6049696,"dirección"=>"CR 51B 87 50",
"ciudad"=>"BARRANQUILLA ", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C8909006082","nombres"=>"ALMACENES EXITO sede CR 53 CON CL 98","teléfono"=>6049696,"dirección"=>"CR 53 CON CL 98",
"ciudad"=>"BARRANQUILLA ", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C900742925","nombres"=>"INVERSIONES ZONAZUL SAS","teléfono"=>0,"dirección"=>"CR 51B 87 50",
"ciudad"=>"BARRANQUILLA ", "e_mail"=>"", "descuento"=>6, "celular"=>3176607511,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C1061711846","nombres"=>"WILLIAM ALEJANDRO TORRES NAVAEZ","teléfono"=>0,"dirección"=>"CALLE 18 8-04 L3",
"ciudad"=>"BARRANQUILLA ", "e_mail"=>"", "descuento"=>3, "celular"=>3204005387,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}else if($id==49){
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C8909006081","nombres"=>"ALMACENES EXITO sede CL 10 43E 135","teléfono"=>6049696,"dirección"=>"CL 10 43E 135",
"ciudad"=>"ENVIGADO", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C8909006082","nombres"=>"ALMACENES EXITO sede CRA 48 34B SUR 29","teléfono"=>6049696,"dirección"=>"CRA 48 34B SUR 29",
"ciudad"=>"ENVIGADO", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C8909006083","nombres"=>"ALMACENES ÉXITO LAURELES sede LAURELES CR 8 1 37-100","teléfono"=>6049696,"dirección"=>"CR 8 1 37-100",
"ciudad"=>"MEDELLÍN", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}else if($id==54){
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C9000612241","nombres"=>"MERCAMIO S.A sede CALLE 18 106 46","teléfono"=>5137190,"dirección"=>"CALLE 18 106 46",
"ciudad"=>"CALI", "e_mail"=>"", "descuento"=>2, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C9000612242","nombres"=>"MERCAMIO S.A sede CLL 44 N 2G 30 LC 13","teléfono"=>5137190,"dirección"=>"CLL 44 N 2G 30 LC 13",
"ciudad"=>"CALI", "e_mail"=>"", "descuento"=>2, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C9000612243","nombres"=>"MERCAMIO S.A sede CL 6 59A 30","teléfono"=>5137190,"dirección"=>"CL 6 59A 30",
"ciudad"=>"CALI", "e_mail"=>"", "descuento"=>2, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C8909006081","nombres"=>"ALMACENES EXITO sede AV 3F NORTE 52N 46","teléfono"=>6049696,"dirección"=>"AV 3F NORTE 52N 46",
"ciudad"=>"CALI", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C8909006082","nombres"=>"ALMACENES EXITO sede CR 100 CON PASOANCHO 5 169 LOCAL 244","teléfono"=>6049696,"dirección"=>"CR 100 CON PASOANCHO 5 169 LOCAL 244",
"ciudad"=>"CALI", "e_mail"=>"", "descuento"=>1, "celular"=> 0,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}else if($id==31){
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C52835405","nombres"=>"Leidy Mireya Rodriguez Cruz sede sur","teléfono"=>3134167804,"dirección"=>"Cl 18 # 8 -10 local 103",
"ciudad"=>"BOGOTÁ", "e_mail"=>"lrodriguezvl@yahoo.com", "descuento"=>2, "celular"=> 3134167804,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==30 || $id==54 || $id==37){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C131","nombres"=>"NUTRITION MEGA STORE CALI","teléfono"=>6049696,"dirección"=>"AVENIDA 6A 23N 65 SANTA MÓNICA",
"ciudad"=>"CALI", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C132","nombres"=>"ELITE NUTRITION GROUP","teléfono"=>6049696,"dirección"=>"# 22, Cl. 15 #25a207, Yumbo, Valle del Cauca",
"ciudad"=>"YUMBO", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C133","nombres"=>"BODEGA EL TRONCAL","teléfono"=>6049696,"dirección"=>"Cl. 39 #8a46",
"ciudad"=>"CALI", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));  
}
if($id==28 || $id==46){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C124","nombres"=>"UNIVERSAL PERFECT NUTRITION BARRANQUILLA","teléfono"=>6049696,"dirección"=>"CALLE 70 #45-75 BARRIO COLOMBIA",
"ciudad"=>"BARRANQUILLA", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==25){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C125","nombres"=>"MEGAPLEX STORE BUCARAMANGA","teléfono"=>6049696,"dirección"=>"Carrera 35 #54-70 B/CABECERA",
"ciudad"=>"BUCARAMANGA", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==59){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C126","nombres"=>"MEGAPLEX STORE CÚCUTA","teléfono"=>6049696,"dirección"=>"CALLE 11 #1-57 BARRIO LATINO",
"ciudad"=>"CÚCUTA", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==26 || $id==5 || $id==49){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C127","nombres"=>"NUTRITION MEGASTORE MEDELLÍN","teléfono"=>6049696,"dirección"=>"CALLE 33 #65B-12 CONQUISTADORES",
"ciudad"=>"MEDELLÍN", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==31 || $id==29 || $id==47 || $id==48){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C128","nombres"=>"MEGAPLEX STARS STORE KENEDY","teléfono"=>6049696,"dirección"=>"Carrera 79 sur #41C-43 Kennedy",
"ciudad"=>"BOGOTÁ", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C129","nombres"=>"BODEGA MEGAPLEX SANTA SOFÍA","teléfono"=>6049696,"dirección"=>"cr 28 76 28 santa sofia",
"ciudad"=>"YUMBO", "e_mail"=>"srealpe@elitenut.com", "descuento"=>1, "celular"=> 3207582916,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==32 ){
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C901285849","nombres"=>"GRUPO MI PROTEINA SAS ITAGUI","teléfono"=>3218331781,"dirección"=>"CL 36 50A 63 AP 401 LAS MARGARITAS",
"ciudad"=>"ITAGUI", "e_mail"=>"srealpe@elitenut.com", "descuento"=>8, "celular"=> 3218331781,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C9012858491","nombres"=>"GRUPO MI PROTEINA SAS MEDELLIN","teléfono"=>3218331781,"dirección"=>"CR 48 10-45 CC MONTERREY LC 026",
"ciudad"=>"MEDELLIN", "e_mail"=>"srealpe@elitenut.com", "descuento"=>8, "celular"=> 3218331781,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0 ))));
}
if($id==27 ){
array_push($activos,base64_encode(json_encode( array("id_cliente"=>"C901285849","nombres"=>"GRUPO MIPROTEINA SAS sede CARTAGENA","teléfono"=>3218331781,"dirección"=>"SUPER CC LOS EJECUTIVOS LC 62",
"ciudad"=>"CARTAGENA", "e_mail"=>"gerencia@miproteina.com.co", "descuento"=>8, "celular"=> 3218331781,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C9012858491","nombres"=>"GRUPO MIPROTEINA SAS sede SANTA MARTA","teléfono"=>3218331781,"dirección"=>"CR 12A 12 60 LC 2",
"ciudad"=>"SANTA MARTA", "e_mail"=>"gerencia@miproteina.com.co", "descuento"=>8, "celular"=> 3218331781,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0))));
array_push($activos, base64_encode(json_encode(array("id_cliente"=>"C9012858492","nombres"=>"GRUPO MIPROTEINA SAS sede BARRANQUILLA","teléfono"=>3218331781,"dirección"=>"CL 84 43 B 27 L 2 SERVYTECH",
"ciudad"=>"BARRANQUILLA", "e_mail"=>"gerencia@miproteina.com.co", "descuento"=>8, "celular"=> 3218331781,"vendedor"=>"OFICINA PRINCIPAL", "teléfono2"=>0))));
}


//endregion
$app->response()->status(200);
$total_clientes=count($activos)+count($inactivos);
$result=array("activos"=>$activos,"inactivos"=>$inactivos,"total_clientes"=>$total_clientes,"total_activos"=>count($activos),"total_inactivos"=>count($inactivos));
}
catch (Exception $e) {
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$stmt=null;
$stmt2=null;
$dbh=null;
}
//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});
//Devuelve el total en ordenes, facturas y devoluciones por vendedor
$app->get('/ordenesyfacturas', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
require_once './models/querys.php';
require_once './models/curl.php';
$SELLERS=CURL::consumeGetApi("https://www.elitenutritiongroup.com/api_eliteN/api/webservicev2.php/sellers");
$resp = array();

foreach ($SELLERS as $key => $value) {
$id= $value->id_vendedor;
$vendedor= $value->vendedor;
$categoria= $value->categoria;

$sql= QUERYS::sellerv2_Block1($ini,$fin,$id);
$sqldev=  QUERYS::sellerv2_Block2($ini,$fin,$id);
$sqlord= QUERYS::sellerv2_Block3($ini,$fin,$id);
$ventastotallinea = QUERYS::ventasTotalLinea($ini,$fin,$id);
$sqlnumd = QUERYS::sqlNumd($ini,$fin,$id);
$sqlnumo = QUERYS::SQLNUMO($ini,$fin,$id);
$stmt = $dbh->prepare($sql);
$stmt -> execute();
$result = $stmt->fetchAll();

$stmt2 = $dbh->prepare($sqldev);
$stmt2 -> execute();
$result2 = $stmt2->fetchAll();
$stmt3 = $dbh->prepare($sqlord);
$stmt3 -> execute();
$result3 = $stmt3->fetchAll();
if(count($result) > 0 || count($result2) > 0 || count($result3) > 0){
$app->response()->status(200); 
$stmt4 = $dbh->prepare($ventastotallinea);
$stmt4 -> execute();
$result4 = $stmt4->fetchAll();
$stmt5 = $dbh->prepare($sqlnumd);
$stmt5 -> execute();
$result5 = $stmt5->fetchAll();
$stmt6 = $dbh->prepare($sqlnumo);
$stmt6 -> execute();
$result6 = $stmt6->fetchAll();
$totalVentas = 0;
$totalDev = 0;
$totalOrd = 0;
$lineaVenta = count($result4);
$lineaDev = count($result5);
$lineaOrd = count($result6);
for($i=0; $i<count($result); $i++){
$totalVentas = $totalVentas + $result[$i]['TOTAL'];
}
for($j=0; $j<count($result2); $j++){
$totalDev = $totalDev + $result2[$j]['TOTAL'];
}
for($k=0; $k<count($result3); $k++){
$totalOrd = $totalOrd + $result3[$k]['TOTAL'];
}

array_push($resp, array(
"id_vendedor"=>$id,"categoria"=>$categoria, "vendedor"=>$vendedor, "meta"=>0,
"facturado"=>round($totalVentas),
"total_f"=>round($lineaVenta),
"devoluciones"=>round($totalDev),
"total_d"=>round($lineaDev),
"ordenes"=>round($totalOrd),
"total_o"=>round($lineaOrd)                 
));

// $result=$SELLERS;
}else{
array_push($resp, array(
"id_vendedor"=>$id,"categoria"=>$categoria, "vendedor"=>$vendedor, "meta"=>0,
"facturado"=>0,
"total_f"=>0,
"devoluciones"=>0,
"total_d"=>0,
"ordenes"=>0,
"total_o"=>0                
));
}

}


$result=$resp;



}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$stmt=null;$stmt2=null;$stmt3=null;$stmt4=null;$stmt5=null;$stmt6=null;
$dbh=null;
}

//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});
//Devuelve el informe principal de ventas: total en ordenes, facturas, metas y devoluciones por vendedor
$app->get('/usuariosVentas', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
require_once './models/querys.php';
require_once './models/curl.php';
$SELLERS=CURL::consumeGetApi("https://www.elitenutritiongroup.com/api_eliteN/api/webservicev2.php/sellersup");
$resp = array();

foreach ($SELLERS as $key => $value) {
$id= $value->id_usuario;
$vendedor= $value->usuario;
$categoria= $value->categoria;
$callcenter= $value->callcenter;
$meta= $value->meta;
$F= $value->F;
$sql= QUERYS::sellerv2_Block1($ini,$fin,$id);
$sqldev=  QUERYS::sellerv2_Block2($ini,$fin,$id);
// $sqlord= QUERYS::sellerv2_Block3($ini,$fin,$id);
$ventastotallinea = QUERYS::ventasTotalLinea($ini,$fin,$id);
$sqlnumd = QUERYS::sqlNumd($ini,$fin,$id);
$sqlnumo = QUERYS::SQLNUMO($ini,$fin,$id);
$stmt = $dbh->prepare($sql);
$stmt -> execute();
$result = $stmt->fetchAll();

$stmt2 = $dbh->prepare($sqldev);
$stmt2 -> execute();
$result2 = $stmt2->fetchAll();
// $stmt3 = $dbh->prepare($sqlord);
// $stmt3 -> execute();
// $result3 = $stmt3->fetchAll();
if(count($result) > 0 || count($result2) > 0){
$app->response()->status(200); 
$stmt4 = $dbh->prepare($ventastotallinea);
$stmt4 -> execute();
$result4 = $stmt4->fetchAll();
$stmt5 = $dbh->prepare($sqlnumd);
$stmt5 -> execute();
$result5 = $stmt5->fetchAll();
$stmt6 = $dbh->prepare($sqlnumo);
$stmt6 -> execute();
$result6 = $stmt6->fetchAll();
$totalVentas = 0;
$totalDev = 0;
$totalOrd = 0;
$lineaVenta = count($result4);
$lineaDev = count($result5);
$lineaOrd = count($result6);
for($i=0; $i<count($result); $i++){
$totalVentas = $totalVentas + $result[$i]['TOTAL'];
}
for($j=0; $j<count($result2); $j++){
$totalDev = $totalDev + $result2[$j]['TOTAL'];
}
// for($k=0; $k<count($result3); $k++){
// $totalOrd = $totalOrd + $result3[$k]['TOTAL'];
// }
$usuario= array( "id_usuario"=>$id,"categoria"=>$categoria, "usuario"=>$vendedor, "F"=>$F,
"ventas"=>array("facturado"=>round($totalVentas),"meta"=>$meta,"devoluciones"=>round($totalDev),"ordenes"=>round($totalOrd))
);
array_push($resp,$usuario); 
}else{
$usuario= array( "id_usuario"=>$id,"categoria"=>$categoria, "usuario"=>$vendedor, "F"=>$F,
"ventas"=>array("facturado"=>0,"meta"=>$meta,"devoluciones"=>0,"ordenes"=>0)
);
array_push($resp,$usuario); 
}

}
$result=$resp;
}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$stmt=null;$stmt2=null;$stmt4=null;$stmt5=null;$stmt6=null;
$dbh=null;
}
//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});

//Devuelve las ordenes  por vendedor
$app->get('/documentos_ordenes', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
$id = $app->request()->params('id');
$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
require_once './models/querys.php';
require_once './models/curl.php';
$resp = array();
$sqlord= QUERYS::datosOrdenes($ini,$fin,$id);
$stmt3 = $dbh->prepare($sqlord);
$stmt3 -> execute();
$result3 = $stmt3->fetchAll();
$data=array();
//   array_push($data, array("fecha"=>$ini,"fechaF"=>$fin, "id"=>$id));
if(count($result3) > 0){
  
for($k=0; $k<count($result3); $k++){
$item=utf8_encode($result3[$k]["CardName"]);   
$id_orden=$result3[$k]['DocNum'];
$r=0;
$total=0;

for ($i=0; $i < count($data) ; $i++) { 
if($data[$i]['id']==$id_orden){
$r=1;
$total += round($data[$i]['total']);
}
}

if ($r==0) {
array_push($data, array("id"=>$id_orden, "total"=>$result3[$k]['TOTAL'], "cedula"=>$result3[$k]['CardCode'],
"nivel"=>$result3[$k]['ListNum'], "ciudad"=>utf8_encode($result3[$k]['City']), "nombre"=>$item));
}else{
for ($i=0; $i < count($data) ; $i++) { 
if($data[$i]['id']==$id_orden){
$data[$i]['total']=$result3[$k]['TOTAL']+$total;
}
}
    
}



}
}
$app->response()->status(200); 
$result=$data;
}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$stmt3=null;
$dbh=null;
}

//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});

//Devuelve las ventas y metas por rango de fecha de un vendedor
$app->get('/ventas_fecha', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
require_once './models/querys.php';
require_once './models/curl.php';

$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
$id = $app->request()->params('id');
$month=explode("-",$ini)[1];
$year=explode("-",$ini)[0];

$SELLERS=CURL::consumeGetApi("https://www.elitenutritiongroup.com/api_eliteN/api/webservicev2.php/sellersupdate?id=$id&month=$month&year=$year");
$resp = array();
$devolucione=0;
$totalDev=0;
$totalVentas=0;
if($SELLERS){
$meta= $SELLERS->meta;
//region create sale
$sql= QUERYS::sellerv2_Block1($ini,$fin,$id);
$sqldev=  QUERYS::sellerv2_Block2($ini,$fin,$id);
$stmt2 = $dbh->prepare($sqldev);
$stmt2 -> execute();
$result2 = $stmt2->fetchAll();
$stmt = $dbh->prepare($sql);
$stmt -> execute();
$result = $stmt->fetchAll();
if(count($result) > 0){
$app->response()->status(200); 
for($j=0; $j<count($result2); $j++){$totalDev = $totalDev + $result2[$j]['TOTAL']; }
$devoluciones=round($totalDev);
for($i=0; $i<count($result); $i++){$totalVentas = $totalVentas + $result[$i]['TOTAL'];}
$facturado= (round($totalVentas)+ $devoluciones );

}else $facturado=0;

if($meta==0)$meta=$facturado;
array_push($resp, array("meta"=>$meta, "venta"=>$facturado, "diferencia"=>($meta-$facturado)));
//endregion
}
$result=$resp[0];
}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$stmt=null;$stmt2=null;
$dbh=null;
}

//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});



//Devuelve las ventas del último trimestre de un vendedor
$app->get('/ventas_trimestre', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
require_once './models/vendedores.php';
require_once './models/utils.php';

$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
$id = $app->request()->params('id');
// $fecha_actual = date("d-m-Y");
// $fecha_actual = date("Y-m-01");

$fecha_actual = new DateTime();
$fecha_actual->modify('first day of this month'); 

$saller= new tb_vendedores($dbh);  

$ventas=array();
$total=0;

for ($i=0; $i <3 ; $i++) { 

if ($i != 0) {
 // Si no es el primer ciclo (mes actual), retrocedemos un mes
 $fecha_actual->modify('-1 month');
}


$total_días = $fecha_actual->format('t');
$mes = $fecha_actual->format('m');
$year = $fecha_actual->format('Y');

$fechaI = $year . "-" . $mes . "-01";
$fechaF = $year . "-" . $mes . "-" . $total_días;

$monthName =Utils::nombremes($mes);
$total= $saller->getData($id, $fechaI, $fechaF);
$monthOne=array('mes'=>$monthName,  'total'=>$total);

array_push($ventas,$monthOne);
}

$app->response()->status(200); 
$result=$ventas;
}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$dbh=null;
}

//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});



//Devuelve las ventas de los últimos cinco días de un vendedor
$app->get('/ventas_semana', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
require_once './models/vendedores.php';
require_once './models/utils.php';

$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
$id = $app->request()->params('id');
$fecha_actual = date("d-m-Y");
$saller= new tb_vendedores($dbh);  

$ventas=array();
$total=0;
$issunday=0;
$count=6;
for ($i=1; $i <$count ; $i++) { 
$response_fecha= date("d-m-Y",strtotime($fecha_actual."- $i days")); 
$total_días= date('t', strtotime($response_fecha));
$day=date('d', strtotime($response_fecha)) ;
$mes=date('m', strtotime($response_fecha)) ;
$year=date('Y', strtotime($response_fecha)) ;
$name_day=date('N', strtotime($response_fecha)) ;
$number_week=date('w', strtotime($response_fecha)) ;
$fechaI=$year."-".$mes."-".$day;
$dayText =Utils::numberDay($name_day);
$total= $saller->getData($id, $fechaI, $fechaI);
if($total>0){
$monthOne=array('day'=>$dayText,  'total'=>$total, 'fecha'=>$fechaI);
array_push($ventas,$monthOne);   
}else $count++;
}

$app->response()->status(200); 
$result=$ventas;
}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$dbh=null;
}

//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});
//Devuelve el informe de numéricas
$app->post('/salesreport', function () use($app, $dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//region CODE
try{
require_once './models/numericas.php';
$req = $app->request();
$data= json_decode($req->getBody());
$object=array();
$vendedores= $data->vendedores;
$fecha= $data->fecha;
$producto=$data->producto;
$item2= $producto->item;
$fechaI= $fecha->fechaI;
$fechaF= $fecha->fechaF;
$numerica= new ProductNumeric($dbh);
for($i=0;$i<count($vendedores);$i++) 
{ 
$id =$vendedores[$i]->id_vendedor; 
$item=$numerica->getData($id, $item2, $fechaI, $fechaF);
array_push($object,$item);
}
$app->response()->status(200); 
$result=$object;
}
catch(Exception $e){
$app->response()->status(500);
$result= array('mensaje' => 'Ocurrió un error en el servidor '.$e->getMessage());
}
finally{
$dbh=null;
}
//endregion
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});


//Api para tarea programada "numéricas y ponderadas diarias para todos los productos"
$app->get('/cronjob', function () use($app,$mysqlNumerica,$dbh) {
try{
require_once './models/tb_productos.php';
require_once './models/usuariosventas.php';
require_once './models/tb_numericas.php';
require_once './models/numericas.php';
$tb_numerica= new tb_numericas($mysqlNumerica);   
$tb= new  tb_productos($mysqlNumerica);
$us= new tb_usuarios_ventas($mysqlNumerica);
$numerica= new ProductNumeric($dbh);
$list=$tb->getData();
$fecha = date('Y-m-d');
$vendedores=$us->getData();
$vendedor=array();
//region Proceso
for ($i=0; $i < count($list); $i++) { 
$cod_item=$list[$i]['cod_item'];
$nom_item=$list[$i]['nom_item'];  
foreach ($vendedores as $key => $value) {
$nombre_vendedor=$value['usuario'];
$id_vendedor= $value['id_usuario'];
$res=$numerica->getData($id_vendedor, $cod_item, $fecha, $fecha);
$json=array(
'id_vendedor'=>$id_vendedor,
'nombre_vendedor'=>$nombre_vendedor,
'producto_foco'=>$cod_item,
'nombre_producto_foco'=>$nom_item,
'clientes_activos'=>$res['clientes_activos'],
'clientes_impactados'=>$res['clientes_impactados'],
'total_producto_foco'=>$res['total_producto_foco'],
'numerica'=>$res['numerica'],
'ponderada'=>$res['ponderada'],
'total_clientes'=>$res['total_clientes'],
'fecha'=>$fecha
);
//guardar
$item= $tb_numerica->Insert($json);
array_push($vendedor,$item);
}
}
//endregion
$result=$vendedor;
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
$mysqlNumerica->close();
}
echo  json_encode($result, JSON_NUMERIC_CHECK);
});

//Api para  "numéricas y ponderadas estableciendo una fecha"
$app->get('/cronjobdate', function () use($app,$mysqlNumerica,$dbh) {
try{
require_once './models/tb_productos.php';
require_once './models/usuariosventas.php';
require_once './models/tb_numericas.php';
require_once './models/numericas.php';
$tb_numerica= new tb_numericas($mysqlNumerica);   
$tb= new  tb_productos($mysqlNumerica);
$us= new tb_usuarios_ventas($mysqlNumerica);
$numerica= new ProductNumeric($dbh);
$list=$tb->getData();
$fecha =$app->request()->params('fecha');
$vendedores=$us->getData();
$vendedor=array();
//region Proceso
for ($i=0; $i < count($list); $i++) { 
$cod_item=$list[$i]['cod_item'];
$nom_item=$list[$i]['nom_item'];  
foreach ($vendedores as $key => $value) {
$nombre_vendedor=$value['usuario'];
$id_vendedor= $value['id_usuario'];
$res=$numerica->getData($id_vendedor, $cod_item, $fecha, $fecha);
$json=array(
'id_vendedor'=>$id_vendedor,
'nombre_vendedor'=>$nombre_vendedor,
'producto_foco'=>$cod_item,
'nombre_producto_foco'=>$nom_item,
'clientes_activos'=>$res['clientes_activos'],
'clientes_impactados'=>$res['clientes_impactados'],
'total_producto_foco'=>$res['total_producto_foco'],
'numerica'=>$res['numerica'],
'ponderada'=>$res['ponderada'],
'total_clientes'=>$res['total_clientes'],
'fecha'=>$fecha
);
//guardar
$item= $tb_numerica->Insert($json);
array_push($vendedor,$item);
}
}
//endregion
$result=$vendedor;
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
$mysqlNumerica->close();
}
echo  json_encode($result, JSON_NUMERIC_CHECK);
});


//Api para  "numéricas y ponderadas estableciendo una fecha" solo para nivelar las numéricas de fabio
$app->get('/cronjobdatefabio', function () use($app,$mysqlNumerica,$dbh) {
try{
require_once './models/tb_productos.php';
require_once './models/usuariosventas.php';
require_once './models/tb_numericas.php';
require_once './models/numericas.php';
$tb_numerica= new tb_numericas($mysqlNumerica);   
$tb= new  tb_productos($mysqlNumerica);
$us= new tb_usuarios_ventas($mysqlNumerica);
$numerica= new ProductNumeric($dbh);
$list=$tb->getData();
$fecha =$app->request()->params('fecha');
$vendedores=$us->getDataFabio();
$vendedor=array();
//region Proceso
for ($i=0; $i < count($list); $i++) { 
$cod_item=$list[$i]['cod_item'];
$nom_item=$list[$i]['nom_item'];  
foreach ($vendedores as $key => $value) {
$nombre_vendedor=$value['usuario'];
$id_vendedor= $value['id_usuario'];
$res=$numerica->getData($id_vendedor, $cod_item, $fecha, $fecha);
$json=array(
'id_vendedor'=>$id_vendedor,
'nombre_vendedor'=>$nombre_vendedor,
'producto_foco'=>$cod_item,
'nombre_producto_foco'=>$nom_item,
'clientes_activos'=>$res['clientes_activos'],
'clientes_impactados'=>$res['clientes_impactados'],
'total_producto_foco'=>$res['total_producto_foco'],
'numerica'=>$res['numerica'],
'ponderada'=>$res['ponderada'],
'total_clientes'=>$res['total_clientes'],
'fecha'=>$fecha
);
//guardar
$item= $tb_numerica->Insert($json);
array_push($vendedor,$item);
}
}
//endregion
$result=$vendedor;
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
$mysqlNumerica->close();
}
echo  json_encode($result, JSON_NUMERIC_CHECK);
});


$app->get('/numericas', function () use($app,$mysqlNumerica,$dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//
try{
require_once './models/tb_productos.php';
require_once './models/usuariosventas.php';
require_once './models/tb_numericas.php';
$fechaI = $app->request()->params('fechaI');
$fechaF = $app->request()->params('fechaF');
$tb_numerica= new tb_numericas($mysqlNumerica);   
$tb= new  tb_productos($mysqlNumerica);
$us= new tb_usuarios_ventas($mysqlNumerica);
$list=$tb->getData();
$vendedores=$us->getData();
$vendedor=array();
//region Proceso
for ($i=0; $i < count($list); $i++) { 
$cod_item=$list[$i]['cod_item'];
$nom_item=$list[$i]['nom_item'];  
$lista_vendedores=array();
foreach ($vendedores as $key => $value) {
$nombre_vendedor=$value['usuario'];
$id_vendedor= $value['id_usuario'];
$item= $tb_numerica->Get($id_vendedor,$cod_item, $fechaI, $fechaF)[0];
try{
$item['numerica']= round(($item['clientes_impactados']/$item['clientes_activos'])*100);
}
catch(Exception $e){
$item['numerica']=0;    
}
$impactados= $item['clientes_impactados'];
$foco=$item['total_producto_foco'];
if($impactados==0)$pond=0;
else $pond= $foco/$impactados;   
$item['ponderada']= number_format($pond,1);
array_push($lista_vendedores,$item);
}
array_push($vendedor,array("producto"=>$nom_item, "cod_product"=>$cod_item,"vendedores"=>$lista_vendedores));
}
//endregion
$result=$vendedor;
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
$mysqlNumerica->close();
}
//
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});


$app->get('/numericasbyid', function () use($app,$mysqlNumerica,$dbh) {
$valid_passwords = array ("ck_dbc029e06ebfe7f689b2fe4b8bd78c5a279a7b1b" =>
"cs_488c93c99a9179787587f46b3bb25fdc3fc7ed0c");
$valid_users = array_keys($valid_passwords);
if(isset($_SERVER['PHP_AUTH_USER']) &&
isset($_SERVER['PHP_AUTH_PW'])) {
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated) {
header('WWW-Authenticate: Basic realm="Access denied"');
header('HTTP/1.0 401 Unauthorized');
$app->response()->status(401);
$result= array('mensaje' => 'Usuario no autorizado');
}else{
//
try{
require_once './models/tb_productos.php';
require_once './models/usuariosventas.php';
require_once './models/tb_numericas.php';
$fechaI = $app->request()->params('fechaI');
$fechaF = $app->request()->params('fechaF');
$id = $app->request()->params('id_vendedor');
$tb_numerica= new tb_numericas($mysqlNumerica);   
$tb= new  tb_productos($mysqlNumerica);
$us= new tb_usuarios_ventas($mysqlNumerica);
$list=$tb->getData();
$vendedores=$us->getByIdData($id);
$vendedor=array();
//region Proceso
for ($i=0; $i < count($list); $i++) { 
$cod_item=$list[$i]['cod_item'];
$nom_item=$list[$i]['nom_item'];  
$lista_vendedores=array();
foreach ($vendedores as $key => $value) {
$nombre_vendedor=$value['usuario'];
$id_vendedor= $value['id_usuario'];
$item= $tb_numerica->Get($id_vendedor,$cod_item, $fechaI, $fechaF)[0];
try{
$item['numerica']= round(($item['clientes_impactados']/$item['clientes_activos'])*100);
}
catch(Exception $e){
$item['numerica']=0;    
}
$impactados= $item['clientes_impactados'];
$foco=$item['total_producto_foco'];
if($impactados==0)$pond=0;
else $pond= $foco/$impactados;   
$item['ponderada']= number_format($pond,1);
array_push($lista_vendedores,$item);
}
array_push($vendedor,array("producto"=>$nom_item, "cod_product"=>$cod_item,"vendedores"=>$lista_vendedores));
}
//endregion
$result=$vendedor;
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
$mysqlNumerica->close();
}
//
}
}else{
$app->response()->status(401);
$result= array('mensaje' => 'La autenticacion es requerida para consumir este api');
}    
echo  json_encode($result, JSON_NUMERIC_CHECK);
});








$app->get('/numericsday', function () use($app,$mysqlNumerica,$dbh) {
try{
require_once './models/tb_productos.php';
require_once './models/usuariosventas.php';
require_once './models/tb_numericas.php';
$fecha = $app->request()->params('fecha');
$tb_numerica= new tb_numericas($mysqlNumerica);   
$tb= new  tb_productos($mysqlNumerica);
$us= new tb_usuarios_ventas($mysqlNumerica);
$list=$tb->getData();
$vendedores=$us->getData();
$vendedor=array();
//region Proceso
for ($i=0; $i < count($list); $i++) { 
$cod_item=$list[$i]['cod_item'];
$nom_item=$list[$i]['nom_item'];  
$lista_vendedores=array();
foreach ($vendedores as $key => $value) {
$nombre_vendedor=$value['usuario'];
$id_vendedor= $value['id_usuario'];
$item= $tb_numerica->Get($id_vendedor,$cod_item, $fecha, $fecha)[0];
array_push($lista_vendedores,$item);
}
array_push($vendedor,array("producto"=>$nom_item, "cod_product"=>$cod_item,"vendedores"=>$lista_vendedores));
}
//endregion
$result=$vendedor;
}catch(Exception $e){
$app->response()->status(500);
$result = array('status' => 'false', 'message' => 'Ocurrió un error: '.$e->getMessage());
}
finally{
$dbh=null;
$mysqlNumerica->close();
}
echo  json_encode($result, JSON_NUMERIC_CHECK);
});


//Inicia el Api
$app->run();

