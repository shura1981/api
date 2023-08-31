<?php
//Cargamos el framework
require_once 'vendor/autoload.php';
$app2 = new \Slim\Slim();
//Creamos la conexión a la base de datos con MySQLi
$corsOptions = array(
"origin" => "*",
"exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
"allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);
$app2->add($cors);
function getConnection2() {
$dbhost="localhost";
$dbuser="root";
$dbpass="";
$dbname="megastore";
$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $dbh;
}
$app2->response()->header('Content-Type', 'application/json;charset=UTF-8');   
/*
* Ruta/Controlador Get, le decimos que use la variable $app2
* GET para CONSEGUIR
*/

$app2->get('/hello/:name', function ($name) {
echo json_encode("Holaaa 'àdèè  í ó ---nñññññ, " . $name);
});



$app2->get('/validator/:value', function ($value) {
if(is_numeric($value)){

    //ejecutar consulta por id_cliente
 echo json_encode("es un número");}

else{
    //ejecutar consulta por nombre
    echo json_encode("Es un string");
}  
    });
    




$app2->get('/params', function ()  use($app2){
try{
$paramname = $app2->request()->params('name');
$paramedad = $app2->request()->params('edad');
if($paramname && $paramedad){
echo json_encode("Holaaa ". $paramname . ", su edad es : ". $paramedad . "años");
} else echo json_encode("hola desconocido");
}catch(Exception $exception){
 $respuesta=   "Ocurrió un error:" . $exception;
echo json_encode($respuesta);
}
});


$app2->get('/users', function () use($app2) {
try{
$select2 = getConnection2()->query("select * from tb_tabla");
$users=$select2->fetchAll(PDO::FETCH_OBJ);
echo json_encode($users);
}catch(Exception $exception){
echo json_encode("Ocurrió un error:" . $exception);
}
});

//Post con array
$app2->post('/lista', function() use($app2){
$req = $app2->request();
/*
body->
{
"file":{
"name":"texto1.jpg",
"image":"sadfasdfasdfasdfasdf"
},
"data":"2018-01-01"
}
$json = json_decode($req->getBody());
$file= $json->file;
echo json_encode($file->name);
*/
$data= json_decode($req->getBody(),true);
$object=null;
for($i=0;$i<count($data);$i++) 
{ 
$object = $data[$i]['file']; 
}
echo json_encode($object);

});
//Upload json with text base64 and convert to image and save in file.
$app2->post('/base64', function() use($app2){
try{
$req = $app2->request();
$json = json_decode($req->getBody());
$file= $json->file;
$image_base64 = $file->image;
define('UPLOAD_DIR', 'uploads/');
$data = base64_decode($image_base64);
$file = UPLOAD_DIR . $file->name;
$success = file_put_contents($file, $data);
if($success){
$app2->response()->status(201);
$result = array("status" => "true", "message" => "Archivo guardado correctamente.", "file"=>$file);
}else{
$app2->response()->status(400);
$result = array("status" => "false", "message" => "No se guardó el archivo");
}
}catch(Exception $e){
$app2->response()->header('X-Status-Reason', $e->getMessage());
$app2->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});

//POST para INSERTAR, recibe un json raw
$app2->post('/users', function () use($app2) {
$app2->response()->header('Content-Type', 'application/json;charset=UTF-8');
//Request recoge variables de las peticiones http
$request = $app2->request;
$body = $request->getBody();
$prueba= json_decode($body); 
try{
$insert2 = getConnection2()->query("INSERT INTO tb_prueba SET
correo = '$prueba->correo',
nombres = '$prueba->nombres',
edad = $prueba->edad,
celular = '$prueba->celular'");
if ($insert2) {
$app2->response()->status(201);
$result2 = array("status" => "true", "message" => "Usuario creado correctamente");
} else {
$app2->response()->status(400);
$result2 = array("status" => "false", "message" => "Usuario NO creado");
}
}catch(Exception $e){
$app2->response()->header('X-Status-Reason', $e->getMessage());
$app2->response()->status(500);
$result2 = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result2);
});

//POST, recibe datos desde x-www-form-urlencoded
$app2->post('/form', function () use($app2) {
try{
$request = $app2->request;
$sql = "INSERT INTO tb_prueba SET
correo = '{$request->params("correo")}',
nombres = '{$request->params("nombres")}',
edad = {$request->params("edad")},
id = {$request->params("id")},
celular = '{$request->params("celular")}'";
$insert= getConnection2()->query($sql);
if ($insert) {
$app2->response()->status(201);
$result = array("status" => "true", "message" => "Insertado correctamente");
} else {
$app2->response()->status(400);
$result = array("status" => "false", "message" => "No insertado");
}
}catch(Exception $e){
$app2->response()->header('X-Status-Reason', $e->getMessage());
$app2->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});

//PUT json
$app2->put('/users/:id', function ($id) use($app2) {
$app2->response()->header('Content-Type', 'application/json;charset=UTF-8');
//Request recoge variables de las peticiones http
$request = $app2->request;
$body = $request->getBody();
$prueba= json_decode($body); 
try{
$sql = "UPDATE tb_prueba SET
correo = '$prueba->correo',
nombres = '$prueba->nombres',
edad = $prueba->edad,
celular = '$prueba->celular'
WHERE id=$id";
$update = getConnection2()->query($sql);
if ($update) {
$app2->response()->status(200);
$result = array("status" => "true", "message" => "Usuario modificado correctamente");
} else {
$app2->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO modificado");
}
}catch(Exception $e){
$app2->response()->header('X-Status-Reason', $e->getMessage());
$app2->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});



//PUT x-www-form-urlencoded
$app2->put('/form/:id', function ($id) use($app2) {
$request = $app2->request;
try{
$sql = "UPDATE tb_prueba SET
correo = '{$request->params("correo")}',
nombres = '{$request->params("nombres")}',
edad = '{$request->params("edad")}',
celular = '{$request->params("celular")}'
WHERE id=$id";
$update = getConnection2()->query($sql);
if ($update) {
$app2->response()->status(200);
$result = array("status" => "true", "message" => "Usuario modificado correctamente");
} else {
$app2->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO modificado");
}
}catch(Exception $e){
$app2->response()->header('X-Status-Reason', $e->getMessage());
$app2->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});



//DELETE para BORRAR
$app2->delete('/users/:id', function ($id) use($app2) {
$request = $app2->request;
try{
$sql = "DELETE FROM tb_prueba WHERE id=$id";
$delete = getConnection2()->query($sql);
if ($delete) {
$app2->response()->status(200);
$result = array("status" => "true", "message" => "Usuario eliminado correctamente");
} else {
$app2->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO eliminado");
}
}catch(Exception $e){
$app2->response()->header('X-Status-Reason', $e->getMessage());
$app2->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});



//Método form-data, subir archivos al servidor.
$app2->post('/upload-file',function() use($app2){
$path = 'uploads/';
$request = $app2->request();
$name = $request->post('name');
$lastname = $request->post('lastname');
if (isset($_FILES['file'])) {
$originalName = $_FILES['file']['name'];
$ext = '.'.pathinfo($originalName, PATHINFO_EXTENSION);
$generatedName = md5($_FILES['file']['tmp_name']).$ext;
$filePath = $path.$generatedName;

if (!is_writable($path)) {
echo json_encode(array(
'status' => false,
'msg'    => 'Destination directory not writable.'
));
exit;
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
//Here save file name $generatedName into database.

echo json_encode(array(
'status'        => true,
'originalName'  => $originalName,
'generatedName' => $generatedName,
'name'          =>$name,
'lastname'      =>$lastname
));
}
}
else {
echo json_encode(
array('status' => false, 'msg' => 'No file uploaded.')
);
exit;
}
});
$app2->run();
?>











































