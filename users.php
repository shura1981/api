<?php


//Cargamos el framework
require_once 'vendor/autoload.php';
$app = new \Slim\Slim();
//Creamos la conexión a la base de datos con MySQLi
$corsOptions = array(
"origin" => "*",
"exposeHeaders" => array("Content-Type", "X-Requested-With", "X-authentication", "X-client"),
"allowMethods" => array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS')
);
$cors = new \CorsSlim\CorsSlim($corsOptions);
$app->add($cors);
function getConnection() {
$dbhost="127.0.0.1";
$dbuser="root";
$dbpass="";
$dbname="upn";
$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $dbh;
}

$app->response()->header('Content-Type', 'application/json;charset=UTF-8'); 

/*
* Ruta/Controlador Get, le decimos que use la variable $app
* GET para CONSEGUIR
*/

$app->get('/hello/:name', function ($name) {
echo json_encode("Holaaa " . $name);
});


$app->get('/params', function ()  use($app){
try{
$paramname = $app->request()->params('name');
$paramedad = $app->request()->params('edad');
if($paramname && $paramedad){
echo json_encode("Holaaa ". $paramname . ", su edad es : ". $paramedad . "años");
} else echo json_encode("hola desconocido");
}catch(Exception $exception){
echo json_encode("Ocurrió un error:" . $exception);
}
});


$app->get('/prueba', function () use($app) {
try{
$select = getConnection()->query("select * from tb_productos;");
$clientes=$select->fetchAll(PDO::FETCH_OBJ);
echo json_encode($clientes);
}catch(Exception $exception){
echo json_encode("Ocurrió un error:" . $exception);
}
});

//Post con array
$app->post('/lista', function() use($app){
$req = $app->request();
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
$app->post('/base64', function() use($app){
try{
$req = $app->request();
$json = json_decode($req->getBody());
$file= $json->file;
$image_base64 = $file->image;
define('UPLOAD_DIR', 'uploads/');
$data = base64_decode($image_base64);
$file = UPLOAD_DIR . uniqid() . '.png';
$success = file_put_contents($file, $data);
if($success){
$app->response()->status(201);
$result = array("status" => "true", "message" => "Archivo guardado correctamente.", "file"=>$file);
}else{
$app->response()->status(400);
$result = array("status" => "false", "message" => "No se guardó el archivo");
}
}catch(Exception $e){
$app->response()->header('X-Status-Reason', $e->getMessage());
$app->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});

//POST para INSERTAR, recibe un json raw
$app->post('/users', function () use($app) {
//Request recoge variables de las peticiones http
$request = $app->request;
$body = $request->getBody();
$prueba= json_decode($body); 
try{
$insert = getConnection()->query("INSERT INTO tb_pruebas SET
correo = '$prueba->correo',
nombres = '$prueba->nombres',
edad = $prueba->edad,
celular = '$prueba->celular'");
if ($insert) {
$app->response()->status(201);
$result = array("status" => "true", "message" => "Usuario creado correctamente");
} else {
$app->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO creado");
}
}catch(Exception $e){
$app->response()->header('X-Status-Reason', $e->getMessage());
$app->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});

//POST, recibe datos desde x-www-form-urlencoded
$app->post('/form', function () use($app) {
try{
$request = $app->request;
$sql = "INSERT INTO tb_prueba SET
correo = '{$request->params("correo")}',
nombres = '{$request->params("nombres")}',
edad = {$request->params("edad")},
id = {$request->params("id")},
celular = '{$request->params("celular")}'";
$insert= getConnection()->query($sql);
if ($insert) {
$app->response()->status(201);
$result = array("status" => "true", "message" => "Insertado correctamente");
} else {
$app->response()->status(400);
$result = array("status" => "false", "message" => "No insertado");
}
}catch(Exception $e){
$app->response()->header('X-Status-Reason', $e->getMessage());
$app->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});

//PUT json
$app->put('/users/:id', function ($id) use($app) {
//Request recoge variables de las peticiones http
$request = $app->request;
$body = $request->getBody();
$prueba= json_decode($body); 
try{
$sql = "UPDATE tb_prueba SET
correo = '$prueba->correo',
nombres = '$prueba->nombres',
edad = $prueba->edad,
celular = '$prueba->celular'
WHERE id=$id";
$update = getConnection()->query($sql);
if ($update) {
$app->response()->status(200);
$result = array("status" => "true", "message" => "Usuario modificado correctamente");
} else {
$app->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO modificado");
}
}catch(Exception $e){
$app->response()->header('X-Status-Reason', $e->getMessage());
$app->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});



//PUT x-www-form-urlencoded
$app->put('/form/:id', function ($id) use($app) {
$request = $app->request;
try{
$sql = "UPDATE tb_prueba SET
correo = '{$request->params("correo")}',
nombres = '{$request->params("nombres")}',
edad = '{$request->params("edad")}',
celular = '{$request->params("celular")}'
WHERE id=$id";
$update = getConnection()->query($sql);
if ($update) {
$app->response()->status(200);
$result = array("status" => "true", "message" => "Usuario modificado correctamente");
} else {
$app->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO modificado");
}
}catch(Exception $e){
$app->response()->header('X-Status-Reason', $e->getMessage());
$app->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});



//DELETE para BORRAR
$app->delete('/users/:id', function ($id) use($app) {
$request = $app->request;
try{
$sql = "DELETE FROM tb_prueba WHERE id=$id";
$delete = getConnection()->query($sql);
if ($delete) {
$app->response()->status(200);
$result = array("status" => "true", "message" => "Usuario eliminado correctamente");
} else {
$app->response()->status(400);
$result = array("status" => "false", "message" => "Usuario NO eliminado");
}
}catch(Exception $e){
$app->response()->header('X-Status-Reason', $e->getMessage());
$app->response()->status(500);
$result = array("status" => "false", "message" => "Ocurrió un error.".$e->getMessage());
}
echo json_encode($result);
});



//Método form-data, subir archivos al servidor.
$app->post('/upload-file',function() use($app){
$path = 'uploads/';
$request = $app->request();
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
$app->run();
?>
