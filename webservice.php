<?php
//Cargamos el framework
require_once 'vendor/autoload.php';
//include "../Encrypt/encrypt.php";
require 'connections/connection_hana.php';
//require_once 'curl/firebase.php';
date_default_timezone_set('America/Bogota');
set_time_limit(0);
ini_set('allow_url_fopen', 1);
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '500M');
ini_set('max_input_time', 4000); // Play with the values
ini_set('max_execution_time', 4000); // Play with the values
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
die();
}
// $dominio="http://localhost/api_eliteN/uploads/";
$dominio="https://www.develomentapps.com/api_eliteN/uploads/";
$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=UTF-8'); 
//region get json


/* Informe comercial */

$app->get('/informecomercial/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\",T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' 
UNION 
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\", T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
    
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 

            

            for($i=0; $i<count($result); $i++){  
                
                $temp = explode(" ", $result[$i]['DocDate']);
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                array_push($resp, array(
                    "item"=>$result[$i]["ItemCode"],
                    "producto"=>utf8_encode($result[$i]["Dscription"]),
                    "cantidad"=>utf8_encode($result[$i]["Cantidad"]),
                    "precio"=>utf8_encode($result[$i]["TOTAL"]),
                    "categoria"=>utf8_encode($result[$i]["Categoria"]),
                    "cod_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "cliente"=>utf8_encode($result[$i]["CardName"]),
                    "tipologia"=>utf8_encode($result[$i]["Tipologia"]),
                    "vendedor"=>utf8_encode($result[$i]["Analista Comercial"]),
                    "prefijo_sede"=>utf8_encode($result[$i]["SeriesName"]),
                    "lista_precio"=>utf8_encode($result[$i]["ListName"]),
                    "fecha"=>utf8_encode($fecha),
                    "cod_bodega"=>utf8_encode($result[$i]["WhsCode"])


                    )
                );
                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* informe comercial */

/* informe comercial categorias */

$app->get('/informecomercial/categorias/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\",T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' 
UNION 
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\", T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
    
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 

            

            for($i=0; $i<count($result); $i++){  
                
                $temp = explode(" ", $result[$i]['DocDate']);
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                array_push($resp, array(
                    "item"=>$result[$i]["ItemCode"],
                    "producto"=>utf8_encode($result[$i]["Dscription"]),
                    "cantidad"=>utf8_encode($result[$i]["Cantidad"]),
                    "precio"=>utf8_encode($result[$i]["TOTAL"]),
                    "categoria"=>utf8_encode($result[$i]["Categoria"]),
                    "cod_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "cliente"=>utf8_encode($result[$i]["CardName"]),
                    "tipologia"=>utf8_encode($result[$i]["Tipologia"]),
                    "vendedor"=>utf8_encode($result[$i]["Analista Comercial"]),
                    "prefijo_sede"=>utf8_encode($result[$i]["SeriesName"]),
                    "lista_precio"=>utf8_encode($result[$i]["ListName"]),
                    "fecha"=>utf8_encode($fecha),
                    "cod_bodega"=>utf8_encode($result[$i]["WhsCode"])


                    )
                );
                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }


        $result = groupArray($resp,'categoria');
        $temp=array();
        for ($i=0; $i < count($result); $i++) { 
        $item=$result[$i]['data'];
        $precio=0;
        $cantidad=0;
        foreach ($item as $key => $value) {
        $precio += $value['precio'];
        $cantidad += $value['cantidad'];
        }
        $fila= array("categoria"=>$result[$i]['categoria'], "cantidad"=>$cantidad, "precio"=>$precio);
        array_push($temp, $fila);
        }
        // $result= $result[0]['data'];
        $result= $temp;
        $resp=$result;



        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* informe comercial v2*/

/* informe comercial v2 */

$app->get('/informecomercial/lista_precio/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\",T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' 
UNION 
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\", T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
    
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 

            

            for($i=0; $i<count($result); $i++){  
                
                $temp = explode(" ", $result[$i]['DocDate']);
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                array_push($resp, array(
                    "item"=>$result[$i]["ItemCode"],
                    "producto"=>utf8_encode($result[$i]["Dscription"]),
                    "cantidad"=>utf8_encode($result[$i]["Cantidad"]),
                    "precio"=>utf8_encode($result[$i]["TOTAL"]),
                    "categoria"=>utf8_encode($result[$i]["Categoria"]),
                    "cod_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "cliente"=>utf8_encode($result[$i]["CardName"]),
                    "tipologia"=>utf8_encode($result[$i]["Tipologia"]),
                    "vendedor"=>utf8_encode($result[$i]["Analista Comercial"]),
                    "prefijo_sede"=>utf8_encode($result[$i]["SeriesName"]),
                    "lista_precio"=>utf8_encode($result[$i]["ListName"]),
                    "fecha"=>utf8_encode($fecha),
                    "cod_bodega"=>utf8_encode($result[$i]["WhsCode"])


                    )
                );
                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }

        $result = groupArray($resp,'lista_precio');
        $temp=array();
        for ($i=0; $i < count($result); $i++) { 
        $item=$result[$i]['data'];
        $precio=0;
        foreach ($item as $key => $value) {
        $precio += $value['precio'];
        }
        $fila= array("lista_precio"=>$result[$i]['lista_precio'],  "precio"=>$precio);
        array_push($temp, $fila);
        }
        // $result= $result[0]['data'];
        $result= $temp;
        $resp=$result;


        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* informe comercial v2*/


/* informe x cliente v2 */

$app->get('/informecomercial/clientes/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');
    $client = $app->request()->params('client');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\",T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"CardCode\" = 'C$client'
UNION 
SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\", T12.\"GroupName\" AS \"Tipologia\", T1.\"CardCode\", T2.\"CardName\", T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\", T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\",  T7.\"WhsCode\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"CardCode\" = 'C$client' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
    
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 

            

            for($i=0; $i<count($result); $i++){  
                
                $temp = explode(" ", $result[$i]['DocDate']);
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                array_push($resp, array(
                    "item"=>$result[$i]["ItemCode"],
                    "producto"=>utf8_encode($result[$i]["Dscription"]),
                    "cantidad"=>utf8_encode($result[$i]["Cantidad"]),
                    "precio"=>utf8_encode($result[$i]["TOTAL"]),
                    "categoria"=>utf8_encode($result[$i]["Categoria"]),
                    "tipologia"=>utf8_encode($result[$i]["Tipologia"]),
                    "prefijo_sede"=>utf8_encode($result[$i]["SeriesName"]),
                    "fecha"=>utf8_encode($fecha),
                    "cod_bodega"=>utf8_encode($result[$i]["WhsCode"])


                    )
                );
                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
        /*
        $result = groupArray($resp,'pro');
        $temp=array();
        for ($i=0; $i < count($result); $i++) { 
        $item=$result[$i]['data'];
        $precio=0;
        foreach ($item as $key => $value) {
        $precio += $value['precio'];
        }
        $fila= array("lista_precio"=>$result[$i]['lista_precio'],  "precio"=>$precio);
        array_push($temp, $fila);
        }
        // $result= $result[0]['data'];
        $result= $temp;
        $resp=$result;

        */
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* informe x cliente v2*/


/* inventario ciclico nutra */

$app->get('/inventariociclico', function() use($app){

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT T0.\"ItemCode\", T0.\"ItemName\", T1.\"ItmsGrpCod\", T1.\"ItmsGrpNam\" FROM \"NUTRAMERICAN_PHARMA\".\"OITM\" T0  
    INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OITB\" T1 ON T0.\"ItmsGrpCod\" = T1.\"ItmsGrpCod\" 
    WHERE T0.\"ItemCode\" between '000000' and '999999'
    
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 

            $seleccion = array_rand($result,3);
            for($i=0; $i<count($seleccion); $i++){  
                
                array_push($resp, array(
                    "codigo"=>$seleccion[$i]["ItemCode"],
                    "producto"=>utf8_encode($seleccion[$i]["ItemName"]),
                    "categotia"=>utf8_encode($seleccion[$i]["ItmsGrpNam"])
                    )
                );
                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* inventario ciclico nutra*/




/* Ara */
/* inventario ciclico nutra */

$app->get('/ara/param', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= " 
    SELECT T7.\"LineTotal\" AS \"TOTAL\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' 
UNION 
SELECT T7.\"LineTotal\" AS \"TOTAL\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
 WHERE  T1.\"DocDate\" >='$ini' AND T1.\"DocDate\" <='$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"   
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 

            $seleccion = array_rand($result,3);
            for($i=0; $i<count($seleccion); $i++){  
                
                array_push($resp, array(
                    "total"=>$seleccion[$i]["TOTAL"]
                    )
                );
                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/* Ara */
/* productos por rango y fecha */

$app->get('/productsxrange', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');
    $product = $app->request()->params('product');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "   
    
        SELECT T1.\"Dscription\", sum(T1.\"Quantity\") AS \"Cantidad\", sum(((T1.\"Price\" * T1.\"Quantity\")  * T3.\"Factor\"))  AS \"Total\" FROM \"ELITE_NUTRITION\".\"OINV\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T2.\"ListNum\" = T3.\"ListNum\" 
        WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND  T1.\"ItemCode\" = '$product' 
        GROUP BY T1.\"Dscription\"
        UNION
        SELECT T1.\"Dscription\", (sum(T1.\"Quantity\") *-1), (sum(((T1.\"Price\" * T1.\"Quantity\")  * T3.\"Factor\")) *-1)  AS \"Total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T2.\"ListNum\" = T3.\"ListNum\" 
        WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND  T1.\"ItemCode\" = '$product'  
        GROUP BY T1.\"Dscription\"
        
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            $app->response()->status(200); 
            $cantProduct = 0;
            $totaProduct = 0;
            
           
            for($i=0; $i<count($result); $i++){  
                $nameProduct = utf8_encode($result[$i]["Dscription"]);
                $cantProduct = $cantProduct + round($result[$i]["Cantidad"]);
                $totaProduct = $totaProduct + round($result[$i]["Total"]);

                
            }
            
            array_push($resp, array(
                   
                "producto"=>$nameProduct,
                "cantidad"=>$cantProduct,
                "total"=>$totaProduct
                )
            );
            
            //var_dump($resp);
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* productos por rango y fecha*/
/* Apis ARA */


/* REPORTE VENTAS */

$app->get('/report_ventasfact/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    

    SELECT DISTINCT T1.\"DocEntry\",T0.\"SeriesName\", T1.\"DocNum\", T1.\"DocTotal\", T6.\"Descript\", CASE WHEN T12.\"GroupNum\" = -1 THEN 'CONTADO' ELSE 'CREDITO' END AS \"Recaudo\", T12.\"PymntGroup\", T1.\"DocDate\", T1.\"CardCode\", T2.\"CardName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
        LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
        LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
        LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
        INNER JOIN \"ELITE_NUTRITION\".\"OCTG\" T12 ON T1.\"GroupNum\" = T12.\"GroupNum\"
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' 
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){    



                
                array_push($resp, array(
                    "id_factura"=>$result[$i]["DocEntry"],
                    "prefijo"=>utf8_encode($result[$i]["SeriesName"]),
                    "num_factura"=>utf8_encode($result[$i]["DocNum"]),
                    "valor"=>round($result[$i]["DocTotal"]),
                    "metodo_pago"=>utf8_encode($result[$i]["Recaudo"]),
                    "forma_pago"=>utf8_encode($result[$i]["Descript"]),
                    "fecha"=>utf8_encode($result[$i]["DocDate"]),
                    "id_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "nombres"=> utf8_encode($result[$i]["CardName"])              
                ));

                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* REPORTE VENTAS */



/* REPORTE VENTAS v2 */

$app->get('/report_ventasv2/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    

    SELECT DISTINCT T1.\"DocEntry\",T0.\"SeriesName\", T1.\"DocNum\", T1.\"DocTotal\", T6.\"Descript\", CASE WHEN T12.\"GroupNum\" = -1 THEN 'CONTADO' ELSE 'CREDITO' END AS \"Recaudo\", T12.\"PymntGroup\", T1.\"DocDate\", T1.\"CardCode\", T2.\"CardName\", T1.\"U_ENG_Medio_Pago\" AS \"MedioPago\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
        LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
        LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
        LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
        INNER JOIN \"ELITE_NUTRITION\".\"OCTG\" T12 ON T1.\"GroupNum\" = T12.\"GroupNum\"
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' 
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){  
                
                $temp = explode(" ", $result[$i]['DocDate']);
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                array_push($resp, array(
                    "id_factura"=>$result[$i]["DocEntry"],
                    "prefijo"=>utf8_encode($result[$i]["SeriesName"]),
                    "num_factura"=>utf8_encode($result[$i]["DocNum"]),
                    "valor"=>round($result[$i]["DocTotal"]),
                    "metodo_pago"=>utf8_encode($result[$i]["Recaudo"]),
                    "forma_pago"=>utf8_encode($result[$i]["Descript"]),
                    "fecha"=>utf8_encode($fecha),
                    "id_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "nombres"=> utf8_encode($result[$i]["CardName"]),
                    "medio_pago"=> utf8_encode($result[$i]["MedioPago"])                
                ));

                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* REPORTE VENTAS V2*/




/* REPORTE VENTAS */

$app->get('/report_ventas/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    

    SELECT DISTINCT T1.\"DocEntry\",T0.\"SeriesName\", T1.\"DocNum\", T1.\"DocTotal\", T6.\"Descript\", CASE WHEN T12.\"GroupNum\" = -1 THEN 'CONTADO' ELSE 'CREDITO' END AS \"Recaudo\", T12.\"PymntGroup\", T1.\"DocDate\", T1.\"CardCode\", T2.\"CardName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
        LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
        LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
        LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
        INNER JOIN \"ELITE_NUTRITION\".\"OCTG\" T12 ON T1.\"GroupNum\" = T12.\"GroupNum\"
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' 
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){  
                
                $temp = explode(" ", $result[$i]['DocDate']);
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                array_push($resp, array(
                    "id_factura"=>$result[$i]["DocEntry"],
                    "prefijo"=>utf8_encode($result[$i]["SeriesName"]),
                    "num_factura"=>utf8_encode($result[$i]["DocNum"]),
                    "valor"=>round($result[$i]["DocTotal"]),
                    "metodo_pago"=>utf8_encode($result[$i]["Recaudo"]),
                    "forma_pago"=>utf8_encode($result[$i]["Descript"]),
                    "fecha"=>utf8_encode($fecha),
                    "id_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "nombres"=> utf8_encode($result[$i]["CardName"])              
                ));

                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* REPORTE VENTAS */


/* REPORTE DEVOLUCIONES */

$app->get('/report_devoluciones/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
        SELECT DISTINCT T1.\"DocEntry\", T0.\"DocDate\", T0.\"CardCode\", T0.\"CardName\", T0.\"DocNum\", T1.\"BaseEntry\", T1.\"BaseRef\", T0.\"DocTotal\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"CANCELED\" = 'N'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){    

                $fecha = substr($result[$i]['DocDate'], 0, 10);
                
                array_push($resp, array(
                    "num_devolucion"=>utf8_encode($result[$i]["DocNum"]),
                    "valor"=>round($result[$i]["DocTotal"]*-1),
                    "fecha"=>utf8_encode($fecha),
                    "id_factura_dev"=>utf8_encode($result[$i]["BaseEntry"]),
                    "num_factura_dev"=>utf8_encode($result[$i]["BaseRef"]),
                    "id_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "nombres"=> utf8_encode($result[$i]["CardName"])              
                ));

                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* REPORTE DEVOLUCIONES */

/* REPORTE PAGOS */

$app->get('/report_pagos/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"DocEntry\", T0.\"DocNum\", T0.\"DocDate\", T0.\"CardCode\", T0.\"CardName\", T0.\"DocTotal\", T0.\"NoDocSum\" FROM \"ELITE_NUTRITION\".\"ORCT\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"RCT2\" T1 ON T0.\"DocEntry\" = T1.\"DocNum\" 
        WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"Canceled\" = 'N'
        ORDER BY T0.\"DocNum\"
        
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        
        
        if(count($result) > 0 || count($result2) > 0){
            $app->response()->status(200); 

            for($i=0; $i<count($result); $i++){  
                $fecha = substr($result[$i]['DocDate'], 0, 10);
                $fact = array();
                $forma = array();
                $pago = $result[$i]["DocEntry"];
                $prueba = $result[$i]["DocNum"];
                $sqlpago = "
                SELECT T0.\"DocNum\", T2.\"SeriesName\", T1.\"DocNum\", T1.\"DocDate\", T1.\"DocTotal\" FROM \"ELITE_NUTRITION\".\"RCT2\" T0 
                INNER JOIN \"ELITE_NUTRITION\".\"OINV\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
                INNER JOIN \"ELITE_NUTRITION\".\"NNM1\" T2 ON T1.\"Series\" = T2.\"Series\" 
                WHERE T0.\"DocNum\" = '$pago'
                ";
                $stmt2 = $dbh->prepare($sqlpago);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();
                /*

                for($j=0; $j<count($result2); $j++){

                    $fact = array(
                        "prefijo"=>utf8_encode($result2[$j]["SeriesName"]),
                        "num_factura"=>utf8_encode($result2[$j]["DocNum"]),
                        "fecha_factura"=>utf8_encode($result2[$j]["DocDate"]),
                        "valor_factura"=>utf8_encode($result2[$j]["DocTotal"]),
                    );
                }
                */
                
                $temp=array();
                foreach ($result2 as $key=>$value) { 
                    $pago_factura = $value["DocNum"];
                   
                    $fecha2 = substr($value["DocDate"], 0, 10);
                     $temp = array(
                        "prefijo"=>utf8_encode($value["SeriesName"]),
                        "num_factura"=>utf8_encode($value["DocNum"]),
                        "fecha_factura"=>utf8_encode($fecha2),
                        "valor_factura"=>round($value["DocTotal"]),
                        
                   );  
                                                    
                array_push($fact, $temp);
                }

                $sqlformas = "
                SELECT T1.\"DocNum\", T0.\"CreditAcct\", T2.\"AcctName\", T0.\"FirstSum\", T0.\"FirstDue\" FROM \"ELITE_NUTRITION\".\"RCT3\" T0  
                INNER JOIN \"ELITE_NUTRITION\".\"ORCT\" T1 ON T0.\"DocNum\" = T1.\"DocEntry\" 
                INNER JOIN \"ELITE_NUTRITION\".\"OACT\" T2 ON T0.\"CreditAcct\" = T2.\"AcctCode\" WHERE T1.\"DocNum\"  = '$prueba'
            ";
            $stmt3 = $dbh->prepare($sqlformas);
            $stmt3 -> execute();
            $result3 = $stmt3->fetchAll();

            $temp2=array();
            
            foreach ($result3 as $key1=>$value2) { 
                $fecha3 = substr($value2["FirstDue"], 0, 10);
                 $temp2 = array(
                    "nim_pago"=>utf8_encode($value2["DocNum"]),
                    "id_cuenta"=>utf8_encode($value2["CreditAcct"]),
                    "nombre_cuenta"=>utf8_encode($value2["AcctName"]),
                    "valor_fpago"=>round($value2["FirstSum"]),
                    "fecha_fpago"=>utf8_encode($fecha3),
               );  
                                                
            array_push($forma, $temp2);
            }

               
        
        
                
                
                
                array_push($resp, array(
                    "num_pago"=>utf8_encode($result[$i]["DocNum"]),
                    "valor"=>round($result[$i]["DocTotal"]),
                    "cruce_facturas"=>$fact,
                    "forma_pago"=>$forma,
                    "fecha"=>utf8_encode($fecha),
                    "importe_no_calculado"=>round($result[$i]["NoDocSum"]),
                    "id_cliente"=>utf8_encode($result[$i]["CardCode"]),
                    "nombres"=> utf8_encode($result[$i]["CardName"])              
                ));

                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* REPORTE PAGOS */

/* Facturas por lista de descuento */

$app->get('/discountlistsales/params', function() use($app){
$ini = $app->request()->params('ini');
$fin = $app->request()->params('fin');
if (! extension_loaded('pdo_odbc'))
{
die('ODBC extension not enabled / loaded');
}
$sql= "      
SELECT T3.\"ListName\", sum(T1.\"LineTotal\") AS \"Total\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 
INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T2.\"ListNum\" = T3.\"ListNum\" 
WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' 
GROUP BY T3.\"ListName\", T3.\"ListNum\"";
// UNION
$sql2="  SELECT T3.\"ListName\", (sum(T1.\"LineTotal\")*-1) AS \"Total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0 
INNER JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T2.\"ListNum\" = T3.\"ListNum\" 
WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' 
GROUP BY T3.\"ListName\", T3.\"ListNum\"";
    
$username = "SYSTEM";
$password = "B1HanaAdmin";
$dsn = "odbc:serverara";
$queryString = $sql;
try {
$dbh = new PDO($dsn, $username, $password);
$stmt = $dbh->prepare($queryString);
$stmt -> execute();
$result = $stmt->fetchAll();
$resp = array();
$resp2 = array();

if(count($result) > 0){
for($i=0; $i<count($result); $i++){    
array_push($resp, array(
"nivel"=>utf8_encode($result[$i]["ListName"]),
"valor"=>utf8_encode(round($result[$i]["Total"]))            
));
}
}
$stmt2 = $dbh->prepare($sql2);
$stmt2 -> execute();
$result2 = $stmt2->fetchAll();
if(count($result2) > 0){
for($i=0; $i<count($result2); $i++){    
array_push($resp2, array(
"nivel"=>utf8_encode($result2[$i]["ListName"]),
"valor"=>utf8_encode(round($result2[$i]["Total"]))            
));
}
}
$totales=array();
for ($i=0; $i < count($resp); $i++) { 
$fact= $resp[$i];
array_push($totales,array("nivel"=>$fact['nivel'], "total"=>$fact['valor']) );
for ($j=0; $j < count($resp2); $j++) { 
$dev= $resp2[$j];
if($fact['nivel']==$dev['nivel']){
$sum= $fact['valor']  +  $dev['valor']; 
$totales[$i]['total']= $sum;
}
}
}

$response= array("facturas"=>$resp,"devoluciones"=>$resp2, "totales"=>$totales);
$app->response()->status(200);   
echo json_encode($response, JSON_UNESCAPED_UNICODE);
} catch(Exception $e){
echo '{"error": {"text": '.$e->getMessage().'}}';
}
});



/* Facturas por lista de descuento */

/* Ordenes por lista de descuento */

$app->get('/discountlistorders/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "  

        
SELECT T3.\"ListName\", sum(T1.\"LineTotal\") AS \"Total\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0 
INNER JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T2.\"ListNum\" = T3.\"ListNum\" 
WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' 
GROUP BY T3.\"ListName\", T3.\"ListNum\"

    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){    



                
                array_push($resp, array(
                    "nivel_descuento"=>utf8_encode($result[$i]["ListName"]),
                    "valor"=>utf8_encode(round($result[$i]["Total"]))            
                ));

                
            }
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* Ordenes Por lista de descuento */

/* REPORTE PAGOS RANGO */

$app->get('/report_pagostotal/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"DocEntry\", T0.\"DocNum\", T0.\"DocDate\", T0.\"CardCode\", T0.\"CardName\", T0.\"DocTotal\", T2.\"CreditAcct\", T3.\"AcctName\" FROM \"ELITE_NUTRITION\".\"ORCT\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"RCT2\" T1 ON T0.\"DocEntry\" = T1.\"DocNum\" 
        INNER JOIN \"ELITE_NUTRITION\".\"RCT3\" T2 ON T0.\"DocEntry\" = T2.\"DocNum\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OACT\" T3 ON T2.\"CreditAcct\" = T3.\"AcctCode\" 
        WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"Canceled\" = 'N'
        
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        $totalPago = 0;
        
        
        if(count($result) > 0){
            $app->response()->status(200); 

            for($i=0; $i<count($result); $i++){                 
                $totalPago = $totalPago + $result[$i]["DocTotal"];   
            }
            array_push($resp, array(
                "total_pago"=>round($totalPago)           
            ));
            
                
            
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



/* REPORTE PAGOS */


/** RUTA PARA APP STEVEN TOTAL VENTAS POR VENDEDOR */
$app->get('/sellerv20/params', function() use($app){
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    

    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id'        
    ";

    $sqldev= "    

    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 

       
    ";

    $sqlord= "    

    SELECT DISTINCT T1.\"DocNum\", T7.\"LineTotal\" AS \"TOTAL\", T7.\"Dscription\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORDR\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 

       
    ";

    $ventastotallinea = "

        SELECT T0.\"DocNum\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"SlpCode\"  = '$id'
    ";

    $sqlnumd = "
        SELECT T1.\"DocNum\" FROM \"ELITE_NUTRITION\".\"ORIN\"  T1
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"SlpCode\" = '$id'
    ";

    $sqlnumo = "
        SELECT T1.\"DocNum\" FROM \"ELITE_NUTRITION\".\"ORDR\"  T1
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"SlpCode\" = '$id'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
        if(count($result) > 0){
            $app->response()->status(200); 


            $stmt2 = $dbh->prepare($sqldev);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll();

            $stmt3 = $dbh->prepare($sqlord);
            $stmt3 -> execute();
            $result3 = $stmt3->fetchAll();

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
                /*
                $sql2 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* -1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

                
                $sql3 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* 1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();

                */
                //$totalVentas = $totalVentas + $result[0]['TOTAL'];
                
                $totalVentas = $totalVentas + $result[$i]['TOTAL'];

                
            }
            for($j=0; $j<count($result2); $j++){
                $totalDev = $totalDev + $result2[$j]['TOTAL'];
            }
            for($k=0; $k<count($result3); $k++){
                $totalOrd = $totalOrd + $result3[$k]['TOTAL'];
            }
                
            array_push($resp, array(
                "totalv"=>round($totalVentas),
                "valorv"=>round($lineaVenta),
                "totald"=>round($totalDev),
                "valord"=>round($lineaDev),
                "totalo"=>round($totalOrd),
                "valoro"=>round($lineaOrd)                 
            ));
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN */


/** RUTA PARA APP STEVEN TOTAL VENTAS POR VENDEDOR version 3*/
$app->get('/sellerv2/params', function() use($app){
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    

    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id'        
    ";

    $sqldev= "    

    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 

       
    ";

    $sqlord= "    

    SELECT DISTINCT T1.\"DocNum\", T7.\"LineTotal\" AS \"TOTAL\", T7.\"Dscription\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORDR\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' AND T1.\"U_ENG_Motivo_Cierro\" = 'NA'

       
    ";

    $ventastotallinea = "

        SELECT T0.\"DocNum\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"SlpCode\"  = '$id'
    ";

    $sqlnumd = "
        SELECT T1.\"DocNum\" FROM \"ELITE_NUTRITION\".\"ORIN\"  T1
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"SlpCode\" = '$id'
    ";

    $sqlnumo = "
        SELECT T1.\"DocNum\" FROM \"ELITE_NUTRITION\".\"ORDR\"  T1
        WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T1.\"SlpCode\" = '$id'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();

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
                /*
                $sql2 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* -1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

                
                $sql3 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* 1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();

                */
                //$totalVentas = $totalVentas + $result[0]['TOTAL'];
                
                $totalVentas = $totalVentas + $result[$i]['TOTAL'];

                
            }
            for($j=0; $j<count($result2); $j++){
                $totalDev = $totalDev + $result2[$j]['TOTAL'];
            }
            for($k=0; $k<count($result3); $k++){
                $totalOrd = $totalOrd + $result3[$k]['TOTAL'];
            }
                
            array_push($resp, array(
                "totalv"=>round($totalVentas),
                "valorv"=>round($lineaVenta),
                "totald"=>round($totalDev),
                "valord"=>round($lineaDev),
                "totalo"=>round($totalOrd),
                "valoro"=>round($lineaOrd)                 
            ));
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN */



/** RUTA PARA APP STEVEN TOTAL VENTAS POR VENDEDOR */
$app->get('/seller/params', function() use($app){
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    

    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 

       
    ";

    $sqldev= "    
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT  JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 

    
    ";

    $sqlord= "    

    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\"  AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
LEFT JOIN \"ELITE_NUTRITION\".\"ORDR\"  T1 ON T0.\"Series\" = T1.\"Series\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
WHERE  T1.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T1.\"CANCELED\" = 'N' AND T3.\"U_ENG_BraOffSeller\" = '$id' 

       
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();

        $stmt2 = $dbh->prepare($sqldev);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll();

            $stmt3 = $dbh->prepare($sqlord);
            $stmt3 -> execute();
            $result3 = $stmt3->fetchAll();

            $totalVentas = 0;
            $totalDev = 0;
            $totalOrd = 0;
            $lineaVenta = count($result);
            $lineaDev = count($result2);
            $lineaOrd = count($result3);

        $resp = array();
        if(count($result) > 0 || count($result2) > 0 || count($result3) > 0){
            $app->response()->status(200); 


            


            for($i=0; $i<count($result); $i++){
                /*
                $sql2 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* -1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

                
                $sql3 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* 1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();

                */
                //$totalVentas = $totalVentas + $result[0]['TOTAL'];
                
                $totalVentas = $totalVentas + $result[$i]['TOTAL'];

                
            }
            for($j=0; $j<count($result2); $j++){
                $totalDev = $totalDev + $result2[$j]['TOTAL'];
            }
            for($k=0; $k<count($result3); $k++){
                $totalOrd = $totalOrd + $result3[$k]['TOTAL'];
            }
                
            array_push($resp, array(
                "totalv"=>round($totalVentas),
                "totald"=>round($totalDev),
                "totalo"=>round($totalOrd),                
            ));
    
            

        }else {
            $app->response()->status(200);       
            array_push($resp, array("totalv"=>0,"totald"=>0,"totalo"=>0));
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN */

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
/** RUTA PARA APP STEVEN TOTAL VENTAS POR VENDEDOR */
$app->get('/sellerbackup/params', function() use($app){
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
        SELECT count(T1.\"LineTotal\"), sum(T1.\"LineTotal\") AS \"total\" FROM \"ELITE_NUTRITION\".\"OINV\" T0
        LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
        WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){
                $sql2 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* -1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

                
                $sql3 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* 1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();
                array_push($resp, array(
                    "totalv"=>round($result[0]['total']),
                    "totald"=>round($result2[0]['total']),
                    "totalo"=>round($result3[0]['total'])                    
                ));
            }
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN */

/** factura app */
$app->get('/factureapp/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT T0.\"DocEntry\", T0.\"DocNum\", T0.\"DocDate\", T0.\"DocDueDate\", T0.\"Comments\", T2.\"E_Mail\", T0.\"CardName\",T3.\"Address2\",T3.\"Street\",  T1.\"SlpName\", T2.\"Phone1\", T0.\"CardCode\", T3.\"City\", T0.\"U_ENG_Medio_Pago\",T0.\"DocTotal\" - T0.\"VatSum\" AS \"SubTotal\",  T0.\"VatSum\", T0.\"DocTotal\", T0.\"U_EXX_FE_QRCUFE\" FROM \"ELITE_NUTRITION\".\"OINV\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
    INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
    INNER JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T0.\"ShipToCode\" = T3.\"Address\" 
    WHERE T0.\"U_ENG_Medio_Pago\" = 'Credito' AND   T0.\"DocDate\"  between '$ini' and '$fin'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();  
        
    
        for($i=0; $i<count($result); $i++){
            $productos = array();
            $fecha = substr($result[$i]['DocDate'], 0, 10);
            $fecha_V = substr($result[$i]['DocDueDate'], 0, 10);
           
      
            $id_factura = $result[$i]['DocEntry'];    

           
            $sql2= "
            SELECT T0.\"ItemCode\", T0.\"Dscription\", T0.\"Quantity\", T0.\"Price\", T0.\"LineTotal\" FROM \"ELITE_NUTRITION\".\"INV1\" T0 
            WHERE T0.\"DocEntry\" = '$id_factura'
            ";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 



            for($j=0; $j<count($result2); $j++){

            
                array_push($productos, array(
                    "referencia"=>utf8_encode($result2[$j]['ItemCode']),
                    "descripcion"=>utf8_encode($result2[$j]['Dscription']),
                    "cantidad"=>round(utf8_encode($result2[$j]['Quantity'])),
                    "p_uni"=>round(utf8_encode($result2[$j]['Price']),2),
                    "subtotal"=>round(utf8_encode($result2[$j]['LineTotal']),2)
                ));
           
            }
            array_push($resp, array(
                "N_factura"=>utf8_encode($result[$i]['DocNum']),
                "fecha_factura"=>utf8_encode($fecha), 
                "fecha_vencimiento"=>utf8_encode($fecha_V),  
                "observaciones"=>utf8_encode($result[$i]['Comments']),     
                "vendido_a"=>utf8_encode($result[$i]['CardName']), 
                "correo"=>utf8_encode($result[$i]['E_Mail']),
                "direccion"=>utf8_encode($result[$i]['Street']),  
                "asesor"=>utf8_encode($result[$i]['SlpName']),   
                "telefono"=>utf8_encode($result[$i]['Phone1']), 
                "nit_cc"=>utf8_encode($result[$i]['CardCode']),
                "ciudad"=>utf8_encode($result[$i]['City']),
                "forma_pago"=>utf8_encode($result[$i]['U_ENG_Medio_Pago']),
                "productos"=>$productos,
                "subtotal"=>round(utf8_encode($result[$i]['SubTotal']),2),
                "impuestos"=>round(utf8_encode($result[$i]['VatSum']),2),
                "total"=>round(utf8_encode($result[$i]['DocTotal']),2)
            ));

            
        }
        
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});

/** FIN RUTA STEVEN */

/** factura app v2 */
$app->get('/factureappv2/params', function() use($app){
    $id = $app->request()->params('id');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
    SELECT T0.\"DocEntry\", T0.\"DocNum\", T0.\"DocDate\", T0.\"DocDueDate\", T0.\"Comments\", T2.\"E_Mail\", T0.\"CardName\",T3.\"Address2\",T3.\"Street\",  T1.\"SlpName\", T2.\"Phone1\", T0.\"CardCode\", T3.\"City\", T0.\"U_ENG_Medio_Pago\",T0.\"DocTotal\" - T0.\"VatSum\" AS \"SubTotal\",  T0.\"VatSum\", T0.\"DocTotal\" FROM \"ELITE_NUTRITION\".\"OINV\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
    INNER JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T0.\"CardCode\" = T2.\"CardCode\" 
    INNER JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T0.\"ShipToCode\" = T3.\"Address\" 
    WHERE T0.\"DocNum\" = '$id'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();  
        

        for($i=0; $i<count($result); $i++){
            $productos = array();
            $fecha = substr($result[$i]['DocDate'], 0, 10);
            $fecha_V = substr($result[$i]['DocDueDate'], 0, 10);
           
      
            $id_factura = $result[$i]['DocEntry'];    

           
            $sql2= "
            SELECT T0.\"ItemCode\", T0.\"Dscription\", T0.\"Quantity\", T0.\"Price\", T0.\"LineTotal\" FROM \"ELITE_NUTRITION\".\"INV1\" T0 
            WHERE T0.\"DocEntry\" = '$id_factura'
            ";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 



            for($j=0; $j<count($result2); $j++){

            
                array_push($productos, array(
                    "referencia"=>utf8_encode($result2[$j]['ItemCode']),
                    "descripcion"=>utf8_encode($result2[$j]['Dscription']),
                    "cantidad"=>round(utf8_encode($result2[$j]['Quantity'])),
                    "p_uni"=>round(utf8_encode($result2[$j]['Price']),2),
                    "subtotal"=>round(utf8_encode($result2[$j]['LineTotal']),2)
                ));
           
            }
            array_push($resp, array(
                "N_factura"=>utf8_encode($result[$i]['DocNum']),
                "fecha_factura"=>utf8_encode($fecha), 
                "fecha_vencimiento"=>utf8_encode($fecha_V),  
                "observaciones"=>utf8_encode($result[$i]['Comments']),     
                "vendido_a"=>utf8_encode($result[$i]['CardName']), 
                "correo"=>utf8_encode($result[$i]['E_Mail']),
                "direccion"=>utf8_encode($result[$i]['Street']),  
                "asesor"=>utf8_encode($result[$i]['SlpName']),   
                "telefono"=>utf8_encode($result[$i]['Phone1']), 
                "nit_cc"=>utf8_encode($result[$i]['CardCode']),
                "ciudad"=>utf8_encode($result[$i]['City']),
                "forma_pago"=>utf8_encode($result[$i]['U_ENG_Medio_Pago']),
                "productos"=>$productos,
                "subtotal"=>round(utf8_encode($result[$i]['SubTotal']),2),
                "impuestos"=>round(utf8_encode($result[$i]['VatSum']),2),
                "total"=>round(utf8_encode($result[$i]['DocTotal']),2)
            ));

            
        }
        
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});

/** factureaapv2 */


/** RUTA PARA APP STEVEN TOTAL VENTAS POR VENDEDOR ver2 */
$app->get('/sellerv2backup/params', function() use($app){
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
        SELECT count(T1.\"LineTotal\") AS \"valor\", sum(T1.\"LineTotal\") AS \"total\" FROM \"ELITE_NUTRITION\".\"OINV\" T0
        LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
        WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){
                $sql2 = "
                SELECT count(T1.\"LineTotal\") AS \"valor\", sum((T1.\"LineTotal\")* -1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

                
                $sql3 = "
                SELECT count(T1.\"LineTotal\") AS \"valor\", sum((T1.\"LineTotal\")* 1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON (T2.\"CardCode\" = T0.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\")
                WHERE T2.\"U_ENG_BraOffSeller\" = '$id' AND T0.\"DocDate\" BETWEEN '$ini' AND '$fin'
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();
                array_push($resp, array(
                    "totalv"=>round($result[0]['total']),
                    "valorv"=>round($result[0]['valor']),
                    "totald"=>round($result2[0]['total']),
                    "valord"=>round($result2[0]['valor']),
                    "totalo"=>round($result3[0]['total']),
                    "valoro"=>round($result3[0]['valor'])                    
                ));
            }
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN */


/** RUTA PARA APP STEVEN TOTAL VENTAS*/
$app->get('/allsellers/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "    
        SELECT count(T1.\"LineTotal\"), sum(T1.\"LineTotal\") AS \"total\" FROM \"ELITE_NUTRITION\".\"OINV\" T0
        LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
        WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"CardCode\" != 'C900986249'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){
                $sql2 = "
                SELECT count(T1.\"LineTotal\"), sum((T1.\"LineTotal\")* -1) AS \"total\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0
                LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T1 ON (T0.\"DocEntry\" = T1.\"DocEntry\")
                WHERE T0.\"DocDate\" BETWEEN '$ini' AND '$fin' AND T0.\"CardCode\" != 'C900986249'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

                
                
                array_push($resp, array(
                    "totalv"=>round($result[0]['total']),
                    "totald"=>round($result2[0]['total'])                   
                ));
            }
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** RUTA PARA APP STEVEN TOTAL VENTAS V2*/
$app->get('/allsellersv2/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "   

    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\", T2.\"U_ENG_Agrupado\" AS \"Agrupado por\", T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T3.\"City\", T3.\"County\" AS \"Departamento\", T10.\"ListName\",T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\", T2.\"CreateDate\",T1.\"U_ENG_Medio_Pago\" AS \"Medio de Pgago\", T12.\"GroupName\" AS \"Tipologia\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\"  T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\"  AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' AND T9.\"ItmsGrpCod\" <> '100'
    UNION 
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\", T2.\"U_ENG_Agrupado\" AS \"Agrupado por\", T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"E_Mail\", T3.\"City\", T3.\"County\" AS \"Departamento\", T10.\"ListName\", T2.\"Phone1\", T11.\"Name\" AS \"Analista Comercial\",T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T7.\"DiscPrcnt\", T2.\"CreateDate\",T1.\"U_ENG_Medio_Pago\" AS \"Medio de Pgago\", T12.\"GroupName\" AS \"Tipologia\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\"  T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\"  T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" AND T1.\"ShipToCode\" = T3.\"Address\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T11 ON T3.\"U_ENG_BraOffSeller\" = T11.\"Code\"
    INNER JOIN \"ELITE_NUTRITION\".\"OCRG\" T12 ON T2.\"GroupCode\" = T12.\"GroupCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' AND T9.\"ItmsGrpCod\" <> '100' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"
    
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            $app->response()->status(200); 
            $totalSellers = 0;
            for($i=0; $i<count($result); $i++){
                    $totalSellers = $totalSellers + $result[$i]['TOTAL'];
            }
                
            array_push($resp, array(
                "total"=>round($totalSellers)                  
            ));
            
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN todos los vendedores */





//get un bodega cliente
$app->get('/whcustomer/params', function() use($app){
    
    $id = $app->request()->params('id');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "   
        SELECT DISTINCT T0.\"U_ENG_WarehouseSales\",T0.\"U_HBT_DirMM\" FROM \"ELITE_NUTRITION\".\"CRD1\" T0 WHERE T0.\"CardCode\" = 'C$id' AND T0.\"U_HBT_DirMM\" = 'N' AND T0.\"U_ENG_WarehouseSales\" != ''
    
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            for($i=0; $i<count($result); $i++){
                array_push($resp, array(
                    "cod_bodega"=>$result[$i]['U_ENG_WarehouseSales']                    
                ));
            }
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});




//get un cliente
$app->get('/customer/params', function() use($app){
    
    $id = $app->request()->params('id');
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
    SELECT T0.\"CardCode\", T1.\"SlpCode\", T0.\"CardName\", T1.\"SlpName\", T0.\"Phone1\", T0.\"Address\", T0.\"City\", T0.\"E_Mail\", T2.\"ListNum\", T2.\"ListName\", T0.\"Cellular\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T2 ON T0.\"ListNum\" = T2.\"ListNum\"
    WHERE T0.\"CardCode\" = 'C$id' OR T0.\"CardName\" LIKE '%$id%'
    
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            for($i=0; $i<count($result); $i++){
                array_push($resp, array(
                    "id_cliente"=>$result[$i]['CardCode'],
                    "id_vendedor"=>$result[$i]['SlpCode'],
                    "nombres"=>utf8_encode($result[$i]['CardName']),
                    "nombres_vendedor"=>utf8_encode($result[$i]['SlpName']),
                    "teléfono"=>$result[$i]['Phone1'],
                    "dirección"=>utf8_encode($result[$i]['Address']), 
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "e_mail"=>utf8_encode($result[$i]['E_Mail']), 
                    "descuento"=>$result[$i]['ListNum'], 
                    "celular"=>$result[$i]['Cellular'] 
                ));
            }
    
            

        }else {
            $app->response()->status(404);       
            $resp= array("mensaje" => "no encontrado.");
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

//Get orden a factura

$app->get('/orderxfac/param', function() use($app){

    
    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"DocNum\", T0.\"CardCode\", T1.\"TrgetEntry\" FROM \"ELITE_NUTRITION\".\"ORDR\" T0  
        LEFT JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        WHERE T0.\"DocNum\" = '$id'
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       

    
        for($i=0; $i<count($result); $i++){
            

            $orden = array();


            $id_factura = $result[$i]['TrgetEntry'];
            $id_cliente = $result[$i]['CardCode'];

            if($id_factura != ""){
                $sql2= "
                    SELECT T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T2.\"SlpName\", T3.\"ListNum\" FROM \"ELITE_NUTRITION\".\"OCRD\"  T0 
                    INNER JOIN \"ELITE_NUTRITION\".\"OSLP\"  T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
                    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T0.\"ListNum\" = T3.\"ListNum\" 
                    WHERE T0.\"CardCode\" = '$id_cliente'
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();

            for ($j=0;$j < count($result2) ; $j++) { 
                $clientes = array(
                    "nombre"=>utf8_encode($result2[$j]['CardName']),
                    "direccion"=>utf8_encode($result2[$j]['Address']),
                    "ciudad"=>utf8_encode($result2[$j]['City']),
                    "telefono"=>$result2[$j]['Phone1'],
                    "celular"=>$result2[$j]['Cellular'],
                    "vendedor"=>utf8_encode($result2[$j]['SlpName']),
                    "Nivel_descuento"=>$result2[$j]['ListNum']
                );                      
            
            }
            
                $sql3= "
                    SELECT T1.\"Dscription\", T1.\"Quantity\", T2.\"SeriesName\", T0.\"DocNum\" FROM \"ELITE_NUTRITION\".\"OINV\" T0  
                    INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
                    INNER JOIN \"ELITE_NUTRITION\".\"NNM1\" T2 ON T0.\"Series\" = T2.\"Series\" 
                    WHERE T0.\"DocEntry\" = '$id_factura'
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();
                $temp=array();
            foreach ($result3 as $key=>$value) { 
                $prefijo = utf8_encode($value['SeriesName']);
                $fact = $value['DocNum'];
                 $temp = array(
                    "nombre_item"=>utf8_encode($value['Dscription']),
                    "contidad_item"=>$value['Quantity']
               );  
                                                
            array_push($orden,$temp);
            }
            

    


           array_push($resp, array(
                "prefijo"=>$prefijo,
                "numero"=>$fact,
                "orden"=>$orden,
                "cliente"=>$clientes
            ));

            }

        }


        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});

$app->get('/test', function ()  use($app){
    try{
        $paramname = $app->request()->params('base64');
        $test = base64_decode($paramname);
        $test = json_decode($test);

        if($paramname){
            echo json_encode( $test);
        } else echo json_encode("hola desconocido");
    }catch(Exception $exception){
        echo json_encode("Ocurrió un error:" . $exception);
    }
});


//producto
$app->get('/products/params', function() use($app){

    $id = $app->request()->params('id');
    $nivel = $app->request()->params('nivel');
    

    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
    SELECT T0.\"ItemCode\", T0.\"ItemName\", T0.\"OnHand\", T0.\"CodeBars\", T1.\"Price\", T1.\"PriceList\" FROM \"ELITE_NUTRITION\".\"OITM\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"ITM1\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\" WHERE T1.\"PriceList\" = 1 AND T0.\"ItemCode\" = '$id' 
    ORDER BY T1.\"PriceList\"
    
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;


    try{


        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();        
        $result = $stmt->fetchAll();      
        $resp = array();

        for($i=0; $i<count($result); $i++){
            $valor=round($result[$i]['Price']*1.19);
            $total;
            switch ($nivel) {
                case 2:
                $mult = $valor * 0.25;
                $total = $valor - $mult;
                break;
                case 3:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.05;
                $total= $subtotal-$mult;   
                break;
                case 4:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.10;
                $total= $subtotal-$mult;   
                break;
                case 5:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.15;
                $total= $subtotal-$mult;   
                break;
                case 6:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.20;
                $total= $subtotal-$mult;   
                break;
                default:
                $total=$valor;
                break;
                }
                array_push($resp, array(
                    "producto"=>utf8_encode($result[$i]['ItemName']),
                    "precio"=>$total
                ));
        }

        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    
    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

//producto 2
$app->get('/products', function() use($app){

    $producto= $app->request()->params('base64');
    //$test = base64_decode($producto);
    //$test = utf8_encode($test);
    //$test = json_decode($test);
    //$testproducto = $test->producto;
    $testproducto = "ZOLÉ";
    //$testnivel = $test->nivel;
    $testnivel = 2;
    var_dump($testproducto);

    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
    SELECT T0.\"ItemCode\", T0.\"ItemName\", T0.\"OnHand\", T0.\"CodeBars\", T1.\"Price\", T1.\"PriceList\" FROM \"ELITE_NUTRITION\".\"OITM\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"ITM1\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\" WHERE T1.\"PriceList\" = 1 AND T0.\"ItemName\" = '%ZOLÉ%' 
    ORDER BY T1.\"PriceList\"
    
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;


    try{


        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        
        $result = $stmt->fetchAll();
        if($result > 0){
            var_dump($result);
        }
        $resp = array();

        for($i=0; $i<count($result); $i++){
            $valor=round($result[$i]['Price']*1.19);
            $total;
            switch ($testnivel) {
                case 2:
                $mult = $valor * 0.25;
                $total = $valor - $mult;
                break;
                case 3:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.05;
                $total= $subtotal-$mult;   
                break;
                case 4:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.10;
                $total= $subtotal-$mult;   
                break;
                case 5:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.15;
                $total= $subtotal-$mult;   
                break;
                case 6:
                $mult = $valor * 0.25;
                $subtotal = $valor - $mult;
                $mult= $subtotal * 0.20;
                $total= $subtotal-$mult;   
                break;
                default:
                $total=$valor;
                break;
                }
                array_push($resp, array(
                    "producto"=>utf8_encode($result[$i]['ItemName']),
                    "precio"=>$total
                ));
        }

        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    
    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});



//Prueba
//region ejemplo Post json con array
$app->post('/salesreport', function() use($app){
    $req = $app->request();
 
    $data= json_decode($req->getBody());
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $object=array();
    
    $vendedores= $data->vendedores;
    $fecha= $data->fecha;
    $producto=$data->producto;
    $item2= $producto->item;
    $fechaI= $fecha->fechaI;
    $fechaF= $fecha->fechaF;
    for($i=0;$i<count($vendedores);$i++) 
    { 
        $activos = 0;
        $totalpro = 0;
    $id =$vendedores[$i]->id_vendedor; 
    //Codigo consultas hanna
    $sql= "
        SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
        WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'
    ";

    $sqlproduct= "
        SELECT T1.\"Quantity\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 
        INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON T0.\"CardCode\" = T2.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\"
        LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T3 ON T2.\"U_ENG_BraOffSeller\" = T3.\"Code\"
        WHERE T1.\"ItemCode\" = '$item2' AND T3.\"Code\" = '$id' AND T0.\"DocDate\" BETWEEN '$fechaI' AND '$fechaF'
    ";

    $sqlclientesimpactados= "
        SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 
        INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON T0.\"CardCode\" = T2.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\"
        LEFT JOIN \"ELITE_NUTRITION\".\"@ENG_VENDEDORES\" T3 ON T2.\"U_ENG_BraOffSeller\" = T3.\"Code\"
        WHERE T1.\"ItemCode\" = '$item2' AND T3.\"Code\" = '$id' AND T0.\"DocDate\" BETWEEN '$fechaI' AND '$fechaF'
    ";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $totalClientes = count($result);
    
        for($j=0; $j<$totalClientes; $j++){
           
      
            $id_cliente = $result[$j]['CardCode'];    

           
            $sql2= "
                SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0 
                WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$fechaI' and '$fechaF'
            ";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 



            if(count($result2) > 0){   
                $activos = $activos + 1;
            }

            
        }
        

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    try {
        $stmt3 = $dbh->prepare($sqlproduct);
        $stmt3 -> execute();
        $result3 = $stmt3->fetchAll();
        
        for($k=0; $k<count($result3); $k++){
            $totalpro = $totalpro + $result3[$k][0];
        }
        
        
        

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    try {
        $stmt4 = $dbh->prepare($sqlclientesimpactados);
        $stmt4 -> execute();
        $result4 = $stmt4->fetchAll();
        
        $clientesimpactados = count($result4);      
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
    $numerica = round(($clientesimpactados/$activos)*100);
    $ponderada = round($totalpro/$clientesimpactados, 2);

    // //resultado
    $item= array("id_vendedor"=>$id, "producto_foco"=> $item2, "clientes_activos"=>$activos, "clientes_impactados"=> $clientesimpactados, "total_producto_foco"=>$totalpro, "numerica"=>$numerica, "ponderada"=>$ponderada, "total_clientes"=>$totalClientes);
    array_push($object,$item);
    
    }
    echo json_encode($object, JSON_UNESCAPED_UNICODE);
    });
    //endregion

//Prueba

//Get clientes activos array

$app->get('/activesarray/param', function() use($app){

    
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T1.\"SlpName\", T2.\"ListNum\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T2 ON T0.\"ListNum\" = T2.\"ListNum\" 
        WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $clientes = array();
        $inactivos = 0;
        $activos = 0;
    
        for($i=0; $i<count($result); $i++){
           
      
            $id_cliente = $result[$i]['CardCode'];    

           
            $sql2= "
                SELECT DISTINCT T0.\"CardCode\", T0.\"CardName\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0 
                WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$ini' and '$fin'
            ";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 



            if(count($result2) == 0){
            
                $inactivos= $inactivos + 1;
            }else{    
                $activos = $activos + 1;
            }

            $clientes= array(
                "inactivos"=>$inactivos,
                "activos"=>$activos
            );
            
        }
        array_push($resp,  $clientes);
        
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});




//Get clientes inactivos V2

$app->get('/inactivesv2/param', function() use($app){

    
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T2 ON T0.\"ListNum\" = T2.\"ListNum\" 
        WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $clientes = array();
        $inactivos = 0;
        $activos = 0;
    
        for($i=0; $i<count($result); $i++){
           
      
            $id_cliente = $result[$i]['CardCode'];    

           
            $sql2= "
                SELECT DISTINCT T0.\"CardCode\", T0.\"CardName\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0 
                WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$ini' and '$fin'
            ";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 



            if(count($result2) == 0){
            
                $inactivos= $inactivos + 1;
            }else{    
                $activos = $activos + 1;
            }

            $clientes= array(
                "inactivos"=>$inactivos,
                "activos"=>$activos
            );
            
        }
        array_push($resp,  $clientes);
        
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});

#region

//Prueba
$app->get('/inactivesprueba/param', function() use($app, $dbh){

  
    
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T1.\"SlpName\", T2.\"ListNum\",T0.\"E_Mail\"  FROM \"ELITE_NUTRITION\".\"OCRD\" T0   
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T2 ON T0.\"ListNum\" = T2.\"ListNum\"        
        WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'
    ";
    
    try {
       
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $inactivos = 0;
        $activos = 0;
        $resp = array(); 
        $prueba = array();     
        

        for($i=0; $i<count($result); $i++){
           
            
            $id_cliente = $result[$i]['CardCode'];    

           
            $sql2= "
                SELECT DISTINCT T0.\"CardCode\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0                 
                WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$ini' and '$fin'
            ";

            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 
            


            if(count($result2) == 0){ 
                    $inactivos= $inactivos + 1;

            }else{    
                $activos= $activos + 1;
            }     
            
            $stmt2 = null;

           

            
        }
        array_push($resp,  $prueba=array(
            "inactivos"=>$inactivos,
            "activos"=>$activos
        ));
        $stmt = null;
        //var_dump($resp);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});
#endregion


//Get clientes inactivos

$app->get('/inactives/param', function() use($app){

    
    $id = $app->request()->params('id');
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
        SELECT DISTINCT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T1.\"SlpName\", T2.\"ListNum\",T0.\"E_Mail\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\" T1 ON T0.\"SlpCode\" = T1.\"SlpCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T2 ON T0.\"ListNum\" = T2.\"ListNum\" 
        WHERE T0.\"CardCode\"   LIKE 'C%' and  T0.\"SlpCode\"  = '$id' and T0.\"validFor\" = 'Y'
    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $inactivos = array();
    
        for($i=0; $i<count($result); $i++){
           
      
            $id_cliente = $result[$i]['CardCode'];    

           
            $sql2= "
                SELECT DISTINCT T0.\"CardCode\", T0.\"CardName\" FROM \"ELITE_NUTRITION\".\"OINV\"  T0 
                WHERE T0.\"CardCode\" = '$id_cliente' and T0.\"DocDate\" between '$ini' and '$fin'
            ";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2 -> execute();
            $result2 = $stmt2->fetchAll(); 



            if(count($result2) == 0){
            
                $inactivos= array(
                    "id_cliente"=>utf8_encode($result[$i]['CardCode']),
                    "nombres"=>utf8_encode($result[$i]['CardName']),
                    "dirección"=>utf8_encode($result[$i]['Address']),
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "teléfono"=>utf8_encode($result[$i]['Phone1']),
                    "celular"=>utf8_encode($result[$i]['Cellular']),
                    "descuento"=>utf8_encode($result[$i]['ListNum']),                    
                    "email"=>utf8_encode($result[$i]['E_Mail']),
                );
            }else{    
                // $inactivos= array("codigo_i"=>"prueba",
                // "nombre_i"=>"prueba2");
            }


           
          
            

    
        

           array_push($resp,  $inactivos);

            
        }
        
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});

//ventas por rango
$app->get('/salesxrange/param', function() use($app){

    

    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T3.\"ItmsGrpNam\",T5.\"SeriesName\", T0.\"DocNum\", T1.\"ItemCode\", T1.\"Dscription\", T0.\"DocDate\", T4.\"WhsCode\", T4.\"WhsName\", (T1.\"Price\" * T1.\"OpenQty\") AS \"TotalLinea\" , T1.\"OpenQty\" FROM \"ELITE_NUTRITION\".\"OINV\" T0 
    INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
    INNER JOIN \"ELITE_NUTRITION\".\"OITM\" T2 ON T1.\"ItemCode\" = T2.\"ItemCode\"
    INNER JOIN \"ELITE_NUTRITION\".\"OITB\" T3 ON T2.\"ItmsGrpCod\" = T3.\"ItmsGrpCod\" 
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T4 ON T1.\"WhsCode\" = T4.\"WhsCode\" 
    INNER JOIN \"ELITE_NUTRITION\".\"NNM1\" T5 ON T0.\"Series\" = T5.\"Series\"
    WHERE T0.\"DocDate\" between '$ini' and '$fin'
    ORDER BY T0.\"DocDate\", T1.\"Dscription\", T4.\"WhsCode\"
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
                    "prefijo"=>utf8_encode($result[$i]['SeriesName']),
                    "num_factura"=>intval($result[$i]['DocNum']),
                    "codigo"=>intval($result[$i]['ItemCode']),
                    "producto"=>utf8_encode($result[$i]['Dscription']),
                    "fecha"=>utf8_encode($result[$i]['DocDate']),
                    "cantidad"=>intval($result[$i]['OpenQty']),
                    "total"=>intval($result[$i]['TotalLinea']),
                    "cod_bodega"=>intval($result[$i]['WhsCode']),
                    "bodega"=>utf8_encode($result[$i]['WhsName']),
                );
           array_push($resp,  $vxr);            
        }     
                   
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});

//Inventario 
$app->get('/tempinventory', function() use($app){

    


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"WhsCode\", T3.\"WhsName\", T1.\"ItemCode\",T1.\"ItemName\", T2.\"ItmsGrpNam\", T0.\"OnHand\", T0.\"IsCommited\", T0.\"OnOrder\", T0.\"MinStock\", T0.\"MaxStock\", T0.\"AvgPrice\", (T0.\"OnHand\" * T0.\"AvgPrice\") AS \"CostInventario\" FROM \"ELITE_NUTRITION\".\"OITW\" T0
INNER JOIN \"ELITE_NUTRITION\".\"OITM\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\"
INNER JOIN \"ELITE_NUTRITION\".\"OITB\" T2 ON T1.\"ItmsGrpCod\" = T2.\"ItmsGrpCod\" 
INNER JOIN \"ELITE_NUTRITION\".\"OWHS\"	T3 ON T0.\"WhsCode\" = T3.\"WhsCode\"
WHERE T1.\"SellItem\" = 'Y' AND  T1.\"ItmsGrpCod\"  NOT IN (100) AND T0.\"WhsCode\" NOT IN (1051, 1056,1506,1511,1516,1521,2006,2506,3006,3506, 4000)
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "cod_bodega"=>intval($result[$i]['WhsCode']),
                    "nom_bodega"=>utf8_encode($result[$i]['WhsName']),
                    "cod_item"=>intval($result[$i]['ItemCode']),
                    "nom_item"=>utf8_encode($result[$i]['ItemName']),                    
                    "categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
                    "stock"=>intval($result[$i]['OnHand']),
                    "definido"=>intval($result[$i]['IsCommited']),
                    "pedido"=>intval($result[$i]['OnOrder']),
                    "stock_min"=>intval($result[$i]['MinStock']),
                    "stock_max"=>intval($result[$i]['MaxStock']),
                    "precio"=>intval($result[$i]['AvgPrice']),
                    "valorizado"=>intval($result[$i]['CostInventario']),
                );
           array_push($resp,  $vxr);            
        }     
                 
        
        if(count($resp)) $app->response()->status(200);
        else {
            if(count($resp)) $app->response()->status(400);
            $resp=array("mensaje"=>"No encontrado");
        }
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});


//Inventario V2
$app->get('/inventoryv2', function() use($app){

    $cod = $app->request()->params('cod');

    


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"WhsCode\", T3.\"WhsName\", T1.\"ItemCode\",T1.\"ItemName\", T2.\"ItmsGrpNam\", T0.\"OnHand\", T0.\"IsCommited\", T0.\"OnOrder\", T0.\"MinStock\", T0.\"MaxStock\", T0.\"AvgPrice\", (T0.\"OnHand\" * T0.\"AvgPrice\") AS \"CostInventario\" FROM \"ELITE_NUTRITION\".\"OITW\" T0
INNER JOIN \"ELITE_NUTRITION\".\"OITM\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\"
INNER JOIN \"ELITE_NUTRITION\".\"OITB\" T2 ON T1.\"ItmsGrpCod\" = T2.\"ItmsGrpCod\" 
INNER JOIN \"ELITE_NUTRITION\".\"OWHS\"	T3 ON T0.\"WhsCode\" = T3.\"WhsCode\"
WHERE T1.\"SellItem\" = 'Y' AND  T1.\"ItmsGrpCod\"  NOT IN (100) AND T0.\"WhsCode\" = '$cod'
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "cod_bodega"=>intval($result[$i]['WhsCode']),
                    "nom_bodega"=>utf8_encode($result[$i]['WhsName']),
                    "cod_item"=>intval($result[$i]['ItemCode']),
                    "nom_item"=>utf8_encode($result[$i]['ItemName']),                    
                    "categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
                    "stock"=>intval($result[$i]['OnHand']),
                    "definido"=>intval($result[$i]['IsCommited']),
                    "pedido"=>intval($result[$i]['OnOrder']),
                    "stock_min"=>intval($result[$i]['MinStock']),
                    "stock_max"=>intval($result[$i]['MaxStock']),
                    "precio"=>intval($result[$i]['AvgPrice']),
                    "valorizado"=>intval($result[$i]['CostInventario']),
                );
           array_push($resp,  $vxr);            
        }     
                 
        
        if(count($resp)) $app->response()->status(200);
        else {
            if(count($resp)) $app->response()->status(400);
            $resp=array("mensaje"=>"No encontrado");
        }
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});

//Inventario version 3

$app->get('/inventoryv3', function() use($app){

    


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"WhsCode\", T3.\"WhsName\", T1.\"ItemCode\",T1.\"ItemName\", T2.\"ItmsGrpNam\", T0.\"OnHand\", T0.\"IsCommited\", T0.\"OnOrder\", T0.\"MinStock\", T0.\"MaxStock\", T0.\"AvgPrice\", (T0.\"OnHand\" * T0.\"AvgPrice\") AS \"CostInventario\" FROM \"ELITE_NUTRITION\".\"OITW\" T0
INNER JOIN \"ELITE_NUTRITION\".\"OITM\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\"
INNER JOIN \"ELITE_NUTRITION\".\"OITB\" T2 ON T1.\"ItmsGrpCod\" = T2.\"ItmsGrpCod\" 
INNER JOIN \"ELITE_NUTRITION\".\"OWHS\"	T3 ON T0.\"WhsCode\" = T3.\"WhsCode\"
WHERE T1.\"validFor\" = 'Y' AND  T1.\"ItmsGrpCod\" IN ('104','105','106','103','102','101','120') AND T0.\"WhsCode\" NOT IN (1051, 1056,1506,1511,1516,1521,2006,2506,3006,3506,4000)
    ";
//WHERE T1.\"SellItem\" = 'Y' AND  T1.\"ItmsGrpCod\"  NOT IN (100) AND T0.\"WhsCode\" NOT IN (1051, 1056,1506,1511,1516,1521,2006,2506,3006,3506)    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "cod_bodega"=>intval($result[$i]['WhsCode']),
                    "nom_bodega"=>utf8_encode($result[$i]['WhsName']),
                    "cod_item"=>intval($result[$i]['ItemCode']),
                    "nom_item"=>utf8_encode($result[$i]['ItemName']),                    
                    "categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
                    "stock"=>intval($result[$i]['OnHand']),
                    "definido"=>intval($result[$i]['IsCommited']),
                    "pedido"=>intval($result[$i]['OnOrder']),
                    "stock_min"=>intval($result[$i]['MinStock']),
                    "stock_max"=>intval($result[$i]['MaxStock']),
                    "precio"=>intval($result[$i]['AvgPrice']),
                    "valorizado"=>intval($result[$i]['CostInventario']),
                );
           array_push($resp,  $vxr);            
        }     
                 
        
        if(count($resp)) $app->response()->status(200);
        else {
            if(count($resp)) $app->response()->status(400);
            $resp=array("mensaje"=>"No encontrado");
        }
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});



//Inventario 
$app->get('/inventory', function() use($app){

    


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"WhsCode\", T3.\"WhsName\", T1.\"ItemCode\",T1.\"ItemName\", T2.\"ItmsGrpNam\", T0.\"OnHand\", T0.\"IsCommited\", T0.\"OnOrder\", T0.\"MinStock\", T0.\"MaxStock\", T0.\"AvgPrice\", (T0.\"OnHand\" * T0.\"AvgPrice\") AS \"CostInventario\" FROM \"ELITE_NUTRITION\".\"OITW\" T0
INNER JOIN \"ELITE_NUTRITION\".\"OITM\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\"
INNER JOIN \"ELITE_NUTRITION\".\"OITB\" T2 ON T1.\"ItmsGrpCod\" = T2.\"ItmsGrpCod\" 
INNER JOIN \"ELITE_NUTRITION\".\"OWHS\"	T3 ON T0.\"WhsCode\" = T3.\"WhsCode\"
WHERE T1.\"SellItem\" = 'Y'   AND  T2.\"ItmsGrpNam\" <> 'SERVICIOS VENTA'   AND  T2.\"ItmsGrpNam\" <> 'SERVICIOS'  AND  T2.\"ItmsGrpNam\" <> 'MATERIA PRIMA'  AND  T2.\"ItmsGrpNam\" <> 'PROMOCIONES'  AND  T2.\"ItmsGrpNam\" <> 'DESCUENTO COMERCIAL'  AND  T1.\"ItmsGrpCod\"  NOT IN (100) AND T0.\"WhsCode\" NOT IN (1051, 1056,1506,1511,1516,1521,2006,2506,3006,3506, 4000)
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "cod_bodega"=>intval($result[$i]['WhsCode']),
                    "nom_bodega"=>utf8_encode($result[$i]['WhsName']),
                    "cod_item"=>intval($result[$i]['ItemCode']),
                    "nom_item"=>utf8_encode($result[$i]['ItemName']),                    
                    "categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
                    "stock"=>intval($result[$i]['OnHand']),
                    "definido"=>intval($result[$i]['IsCommited']),
                    "pedido"=>intval($result[$i]['OnOrder']),
                    "stock_min"=>intval($result[$i]['MinStock']),
                    "stock_max"=>intval($result[$i]['MaxStock']),
                    "precio"=>intval($result[$i]['AvgPrice']),
                    "valorizado"=>intval($result[$i]['CostInventario']),
                );
           array_push($resp,  $vxr);            
        }     
                 
        
        if(count($resp)) $app->response()->status(200);
        else {
            if(count($resp)) $app->response()->status(400);
            $resp=array("mensaje"=>"No encontrado");
        }
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});





//direcciones por cliente
$app->get('/dirxclient/param', function() use($app){

    $id = $app->request()->params('id');


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "
        SELECT T0.\"CardCode\", T0.\"CardName\", T1.\"Address\",T1.\"Street\", T1.\"City\", T1.\"U_ENG_BraOffSeller\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"CRD1\" T1 ON T0.\"CardCode\" = T1.\"CardCode\" 
        WHERE T0.\"CardCode\" = 'C$id'
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "cod_cliente"=>intval($result[$i]['CardCode']),
                    "nom_cliente"=>utf8_encode($result[$i]['CardName']),
                    "punto"=>utf8_encode($result[$i]['Address']),
                    "direccion"=>utf8_encode($result[$i]['Street']),                    
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "cod_vendedor"=>intval($result[$i]['U_ENG_BraOffSeller']),
                );
           array_push($resp,  $vxr);            
        }     
                   
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});

//direcciones clientes reactivacion
$app->get('/direction/param', function() use($app){

    $id = $app->request()->params('id');


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"Phone1\", T0.\"Phone2\", T0.\"Cellular\", T0.\"City\", T0.\"E_Mail\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0 
    WHERE T0.\"CardCode\" = 'C$id'
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            


$vxr= array(
"cod_cliente"=>utf8_encode($result[$i]['CardCode']),
"nom_cliente"=>utf8_encode($result[$i]['CardName']),
"direccion"=>utf8_encode($result[$i]['Address']),
"telefono_1"=>utf8_encode($result[$i]['Phone1']),
"telefono_2"=>utf8_encode($result[$i]['Phone2']),
"celular"=>utf8_encode($result[$i]['Cellular']),                   
"ciudad"=>utf8_encode($result[$i]['City']),
"correo"=>utf8_encode($result[$i]['E_Mail']),
);
array_push($resp,  $vxr);            
}     


if(count($resp)>0) $app->response()->status(200);
else {
$app->response()->status(404);
$resp=array("mensaje"=>"No encontrado");
}          

echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}
catch (Exception $e) {
echo $e->getMessage();
}
});


//ventas por rango version 2
$app->get('/salesxrangev2/param', function() use($app){

    

    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\",T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' 
    UNION 
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            $var= utf8_encode($result[$i]['DocDate']);
            $dt= explode(" ",$var);

                $vxr= array(
                    "prefijo"=>utf8_encode($result[$i]['SeriesName']), 
                    "Fecha"=>$dt[0], 
                    "num_doc"=>$result[$i]['DocNum'],
                    "referencia"=>$result[$i]['BaseRef'],
                    "cedula"=>utf8_encode($result[$i]['CardCode']),
                    "nombre_cliente"=>utf8_encode($result[$i]['CardName']),
                    "direccion"=>utf8_encode($result[$i]['Address2']), 
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "lista_precio"=>utf8_encode($result[$i]['ListName']), 
                    "telefono"=>utf8_encode($result[$i]['Phone1']), 
                    "codigo_vendedor"=>$result[$i]['SlpCode'], 
                    "nombre_vendedor"=>utf8_encode($result[$i]['SlpName']),
                    "usuario"=>utf8_encode($result[$i]['U_NAME']), 
                    "total_linea"=>utf8_encode($result[$i]['TOTAL']),
                    "forma_pago"=>utf8_encode($result[$i]['Descript']),
                    "numero_linea"=>utf8_encode($result[$i]['LineNum']), 
                    "codigo_producto"=>$result[$i]['ItemCode'], 
                    "producto"=>utf8_encode($result[$i]['Dscription']),
                    "cantidad"=>$result[$i]['Cantidad'], 
                    "categoria"=>utf8_encode($result[$i]['Categoria']),
                    "bodega"=>$result[$i]['WhsCode'],
                    "bodega_name"=>utf8_encode($result[$i]['WhsName']),
                   
                );
                if(utf8_encode($result[$i]['Categoria'])!="Artículos"  && utf8_encode($result[$i]['Categoria'])!="SERVICIOS" && utf8_encode($result[$i]['Categoria'])!="SERVICIOS VENTA")   array_push($resp,  $vxr);            
        }                   
       
        $response=array();
        $rows= $resp;
        if(count($rows)>0){
            $ventas=array();
            $devoluciones=array();
            foreach ($rows as $key => $value) {
                if($value['prefijo'] == "Primario")array_push($devoluciones, $value);
                else array_push($ventas, $value);
            }
            foreach ($devoluciones as $key => $d) {
                foreach ($ventas as $keyventas => $v) {
                    if($d['referencia']==$v['num_doc'] && $d['codigo_producto']== $v['codigo_producto']){
                        $v['cantidad']=  $v['cantidad'] +  $d['cantidad'];
                        $v['total_linea']=  $v['total_linea'] +  $d['total_linea'];
                        //array_splice($devoluciones, array_search($d, $devoluciones), 1);
                    }
                }
            }
            $ventas= array_merge($ventas, $devoluciones);
            foreach ($ventas as $keyventas => $v) {
                $row=array("categoria"=> $v['categoria'], "prefijo"=>$v['prefijo'], "num_factura"=>$v['num_doc'],
                "codigo"=>$v['codigo_producto'], "fecha"=>$v['Fecha'], "producto"=>$v['producto'],
                "cantidad"=>$v['cantidad'], "total"=>$v['total_linea'], "cod_bodega" =>$v['bodega'],
                "bodega"=>$v['bodega_name'], "codigo_vendedor"=>$v['codigo_vendedor'], "vendedor"=>$v['nombre_vendedor']);
                array_push($response, $row);
            }
        }
        /*
        $totalsuma = 0;
        $totalproducto = 0;

        foreach ($response as $keyventas => $r) {
           
            $totalsuma += $r['total'];
            $totalproducto += $r['cantidad'];
        }
        */
        echo json_encode($response, JSON_NUMERIC_CHECK);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});




//ventas por rango version 2
$app->get('/salesxrangev4/param', function() use($app){

    

    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\",T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N'  AND T11.\"WhsName\" NOT LIKE '%CUARENTE%'
    UNION 
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' AND T11.\"WhsName\" NOT LIKE '%CUARENTE%'
    ORDER BY T0.\"SeriesName\", T1.\"DocNum\"

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "prefijo"=>utf8_encode($result[$i]['SeriesName']), 
                    "Fecha"=>utf8_encode($result[$i]['DocDate']), 
                    "num_doc"=>$result[$i]['DocNum'],
                    "referencia"=>$result[$i]['BaseRef'],
                    "cedula"=>utf8_encode($result[$i]['CardCode']),
                    "nombre_cliente"=>utf8_encode($result[$i]['CardName']),
                    "direccion"=>utf8_encode($result[$i]['Address2']), 
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "lista_precio"=>utf8_encode($result[$i]['ListName']), 
                    "telefono"=>utf8_encode($result[$i]['Phone1']), 
                    "codigo_vendedor"=>$result[$i]['SlpCode'], 
                    "nombre_vendedor"=>utf8_encode($result[$i]['SlpName']),
                    "usuario"=>utf8_encode($result[$i]['U_NAME']), 
                    "total_linea"=>utf8_encode($result[$i]['TOTAL']),
                    "forma_pago"=>utf8_encode($result[$i]['Descript']),
                    "numero_linea"=>utf8_encode($result[$i]['LineNum']), 
                    "codigo_producto"=>$result[$i]['ItemCode'], 
                    "producto"=>utf8_encode($result[$i]['Dscription']),
                    "cantidad"=>$result[$i]['Cantidad'], 
                    "categoria"=>utf8_encode($result[$i]['Categoria']),
                    "bodega"=>$result[$i]['WhsCode'],
                    "bodega_name"=>utf8_encode($result[$i]['WhsName']),
                   
                );
           array_push($resp,  $vxr);            
        }                   
       
        $response=array();
        $rows= $resp;
        if(count($rows)>0){
            $ventas=array();
            $devoluciones=array();
            foreach ($rows as $key => $value) {
                if($value['prefijo'] == "Primario")array_push($devoluciones, $value);
                else array_push($ventas, $value);
            }
            foreach ($devoluciones as $key => $d) {
                foreach ($ventas as $keyventas => $v) {
                    if($d['referencia']==$v['num_doc'] && $d['codigo_producto']== $v['codigo_producto']){
                        $v['cantidad']=  $v['cantidad'] +  $d['cantidad'];
                        $v['total_linea']=  $v['total_linea'] +  $d['total_linea'];
                        //array_splice($devoluciones, array_search($d, $devoluciones), 1);
                    }
                }
            }
            $ventas= array_merge($ventas, $devoluciones);
            foreach ($ventas as $keyventas => $v) {
                $row=array("categoria"=> $v['categoria'], "prefijo"=>$v['prefijo'], "num_factura"=>$v['num_doc'],
                "codigo"=>$v['codigo_producto'], "fecha"=>$v['Fecha'], "producto"=>$v['producto'],
                "cantidad"=>$v['cantidad'], "total"=>$v['total_linea'], "cod_bodega" =>$v['bodega'],
                "bodega"=>$v['bodega_name'], "codigo_vendedor"=>$v['codigo_vendedor'], "vendedor"=>$v['nombre_vendedor']);
                array_push($response, $row);
            }
        }
        /*
        $totalsuma = 0;
        $totalproducto = 0;

        foreach ($response as $keyventas => $r) {
           
            $totalsuma += $r['total'];
            $totalproducto += $r['cantidad'];
        }
        */
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});



//ventas por rango version 3
$app->get('/salesxrangev3/param', function() use($app){

    

    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\",T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' 
    UNION 
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "prefijo"=>utf8_encode($result[$i]['SeriesName']), 
                    "Fecha"=>utf8_encode($result[$i]['DocDate']), 
                    "num_doc"=>$result[$i]['DocNum'],
                    "referencia"=>$result[$i]['BaseRef'],
                    "cedula"=>utf8_encode($result[$i]['CardCode']),
                    "nombre_cliente"=>utf8_encode($result[$i]['CardName']),
                    "direccion"=>utf8_encode($result[$i]['Address2']), 
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "lista_precio"=>utf8_encode($result[$i]['ListName']), 
                    "telefono"=>utf8_encode($result[$i]['Phone1']), 
                    "codigo_vendedor"=>$result[$i]['SlpCode'], 
                    "nombre_vendedor"=>utf8_encode($result[$i]['SlpName']),
                    "usuario"=>utf8_encode($result[$i]['U_NAME']), 
                    "total_linea"=>utf8_encode($result[$i]['TOTAL']),
                    "forma_pago"=>utf8_encode($result[$i]['Descript']),
                    "numero_linea"=>utf8_encode($result[$i]['LineNum']), 
                    "codigo_producto"=>$result[$i]['ItemCode'], 
                    "producto"=>utf8_encode($result[$i]['Dscription']),
                    "cantidad"=>$result[$i]['Cantidad'], 
                    "categoria"=>utf8_encode($result[$i]['Categoria']),
                    "bodega"=>$result[$i]['WhsCode'],
                    "bodega_name"=>utf8_encode($result[$i]['WhsName']),
                   
                );
           array_push($resp,  $vxr);            
        }     
                   
       

/*
$response=array();
$rows= $resp;
if(count($rows)>0){
$ventas=array();
$devoluciones=array();
foreach ($rows as $key => $value) {
if($value['prefijo'] == "Primario")array_push($devoluciones, $value);
else array_push($ventas, $value);
}
foreach ($devoluciones as $key => $d) {
foreach ($ventas as $keyventas => $v) {
if($d['referencia']==$v['num_doc'] && $d['codigo_producto']== $v['codigo_producto'])
{
$v['cantidad']=  $v['cantidad'] +  $d['cantidad'];
$v['total_linea']=  $v['total_linea'] +  $d['total_linea'];
}
}
}



foreach ($ventas as $keyventas => $v) {
$cliente=array( "nombre"=>$v['usuario'], "id_cliente"=>$v['cedula'], "direccion"=> $v['direccion'], "nivel_d"=>$v['lista_precio'], "telefono"=>$v['telefono'], "ciudad"=>$v['ciudad']);
$row=array("categoria"=> $v['categoria'], "prefijo"=>$v['prefijo'], "num_factura"=>$v['num_doc'],
"codigo"=>$v['codigo_producto'], "fecha"=>$v['Fecha'], "producto"=>$v['producto'],
"cantidad"=>$v['cantidad'], "total"=>$v['total_linea'], "cod_bodega" =>$v['bodega'],
"bodega"=>$v['bodega_name'], "codigo_vendedor"=>$v['codigo_vendedor'], "vendedor"=>$v['nombre_vendedor']);
array_push($response, $row);
}


*/



        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});


//ventas por rango version 
$app->get('/salesxrangev4/param', function() use($app){

    

    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


    SELECT DISTINCT  T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\",'' AS\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\",T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\", T7.\"LineTotal\" AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\",  T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\" AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"OINV\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"INV1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' 
    UNION 
    SELECT DISTINCT T0.\"SeriesName\", T1.\"DocDate\", T1.\"DocNum\", T7.\"BaseRef\",T1.\"CardCode\", T2.\"CardName\",T1.\"Address2\", T2.\"City\",T10.\"ListName\", T2.\"Phone1\", T3.\"U_SEI_RUTA\", T4.\"SlpCode\", T4.\"SlpName\", T5.\"U_NAME\",  T7.\"LineTotal\" *-1 AS \"TOTAL\", T6.\"Descript\", T7.\"LineNum\", T7.\"ItemCode\", T7.\"Dscription\", T7.\"Quantity\"*-1 AS \"Cantidad\", T9.\"ItmsGrpNam\" AS \"Categoria\", T7.\"WhsCode\", T11.\"WhsName\" FROM \"ELITE_NUTRITION\".\"NNM1\"  T0 
    LEFT JOIN \"ELITE_NUTRITION\".\"ORIN\" T1 ON T0.\"Series\" = T1.\"Series\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OCRD\" T2 ON T1.\"CardCode\" = T2.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"CRD1\" T3 ON T2.\"CardCode\" = T3.\"CardCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OSLP\" T4 ON T1.\"SlpCode\" = T4.\"SlpCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OUSR\" T5 ON T1.\"UserSign\" = T5.\"USERID\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OPYM\" T6 ON T1.\"PeyMethod\" = T6.\"PayMethCod\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"RIN1\" T7 ON T1.\"DocEntry\" = T7.\"DocEntry\"
    LEFT JOIN \"ELITE_NUTRITION\".\"OITM\" T8 ON T7.\"ItemCode\" = T8.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OITB\" T9 ON T8.\"ItmsGrpCod\" = T9.\"ItmsGrpCod\"
    INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T10 ON T2.\"ListNum\" = T10.\"ListNum\"
    INNER JOIN \"ELITE_NUTRITION\".\"OWHS\" T11 ON T7.\"WhsCode\" = T11.\"WhsCode\"
    WHERE  T1.\"DocDate\" >= '$ini' AND T1.\"DocDate\" <= '$fin' AND T1.\"CANCELED\" = 'N' ORDER BY T0.\"SeriesName\", T1.\"DocNum\"

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "prefijo"=>utf8_encode($result[$i]['SeriesName']), 
                    "Fecha"=>utf8_encode($result[$i]['DocDate']), 
                    "num_doc"=>$result[$i]['DocNum'],
                    "referencia"=>$result[$i]['BaseRef'],
                    "cedula"=>utf8_encode($result[$i]['CardCode']),
                    "nombre_cliente"=>utf8_encode($result[$i]['CardName']),
                    "direccion"=>utf8_encode($result[$i]['Address2']), 
                    "ciudad"=>utf8_encode($result[$i]['City']),
                    "lista_precio"=>utf8_encode($result[$i]['ListName']), 
                    "telefono"=>utf8_encode($result[$i]['Phone1']), 
                    "codigo_vendedor"=>$result[$i]['SlpCode'], 
                    "nombre_vendedor"=>utf8_encode($result[$i]['SlpName']),
                    "usuario"=>utf8_encode($result[$i]['U_NAME']), 
                    "total_linea"=>utf8_encode($result[$i]['TOTAL']),
                    "forma_pago"=>utf8_encode($result[$i]['Descript']),
                    "numero_linea"=>utf8_encode($result[$i]['LineNum']), 
                    "codigo_producto"=>$result[$i]['ItemCode'], 
                    "producto"=>utf8_encode($result[$i]['Dscription']),
                    "cantidad"=>$result[$i]['Cantidad'], 
                    "categoria"=>utf8_encode($result[$i]['Categoria']),
                    "bodega"=>$result[$i]['WhsCode'],
                    "bodega_name"=>utf8_encode($result[$i]['WhsName']),
                   
                );
           array_push($resp,  $vxr);            
        }                   
       
        $response=array();
        $rows= $resp;
        if(count($rows)>0){
            $ventas=array();
            $devoluciones=array();
            foreach ($rows as $key => $value) {
                if($value['prefijo'] == "Primario")array_push($devoluciones, $value);
                else array_push($ventas, $value);
            }
            foreach ($devoluciones as $key => $d) {
                foreach ($ventas as $keyventas => $v) {
                    if($d['referencia']==$v['num_doc'] && $d['codigo_producto']== $v['codigo_producto']){
                        $v['cantidad']=  $v['cantidad'] +  $d['cantidad'];
                        $v['total_linea']=  $v['total_linea'] +  $d['total_linea'];
                        //array_splice($devoluciones, array_search($d, $devoluciones), 1);
                    }
                }
            }
            $ventas= array_merge($ventas, $devoluciones);
            foreach ($ventas as $keyventas => $v) {
                
               // $fec = explode(' ',$v['Fecha']); "fecha"=>$fec[0],
                $row=array("prefijo"=>$v['prefijo'],
                "codigo"=>$v['codigo_producto'], "producto"=>$v['producto'],
                "cantidad"=>$v['cantidad'], "total"=>$v['total_linea'], "cod_bodega" =>$v['bodega'],
                "bodega"=>$v['bodega_name']);
                array_push($response, $row);
            }
        }
        /*
        $totalsuma = 0;
        $totalproducto = 0;

        foreach ($response as $keyventas => $r) {
           
            $totalsuma += $r['total'];
            $totalproducto += $r['cantidad'];
        }
        */
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});




//FIDELIZACION
$app->get('/fideliza/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


        SELECT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T2.\"SlpName\", T3.\"ListNum\" FROM \"ELITE_NUTRITION\".\"OCRD\"  T0 
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\"  T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T0.\"ListNum\" = T3.\"ListNum\" 
        WHERE T0.\"CardCode\" = 'C$id'

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();   
        $client = array();
        $buys = array();
        $products = array();
        $puntos = array();
        $temp = array();

  
        if(count($result)>0){
            $app->response()->status(200);
            for($i=0; $i<count($result); $i++){

                $id_client = $result[$i]['CardCode'];

                

                $sql3 = "
                select sum(\"PuntoCliente\" + \"PuntoReferido\") as \"TotalPuntos\" from \"SBO_FIDELIZACION\".\"Fidelizacion\" where \"CodigoCliente\" = '$id_client'
                    
                ";
                $stmt3 = $dbh->prepare($sql3);
                $stmt3 -> execute();
                $result3 = $stmt3->fetchAll();
                
                $sql4 = "
                select sum(\"PuntoCliente\") AS \"PuntosC\", sum(\"PuntoReferido\") AS \"PuntosR\" from \"SBO_FIDELIZACION\".\"Fidelizacion\" where \"CodigoCliente\" = '$id_client'
                ";
                $stmt4 = $dbh->prepare($sql4);
                $stmt4 -> execute();
                $result4 = $stmt4->fetchAll();   
                
                

                

                

                $client = array(
                    "nombre"=> utf8_encode($result[$i]['CardName']),
                    "direccion"=> utf8_encode($result[$i]['Address']),
                    "Ciudad"=> utf8_encode($result[$i]['City']),
                    "telefono"=> utf8_encode($result[$i]['Phone1']),
                    "celular"=> utf8_encode($result[$i]['Cellular']),
                    "total_puntos"=>$result3[0]['TotalPuntos'],
                    "puntos_cliente"=>$result4[0]['PuntosC'],
                    "puntos_referido"=>$result4[0]['PuntosR']
                );

            }
            
            array_push($response, $client);
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});


//Encuesta empleados
$app->get('/apiempleados/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


        SELECT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T2.\"SlpName\", T3.\"ListNum\" FROM \"ELITE_NUTRITION\".\"OCRD\"  T0 
        INNER JOIN \"ELITE_NUTRITION\".\"OSLP\"  T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
        INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T0.\"ListNum\" = T3.\"ListNum\" 
        WHERE T0.\"CardCode\" = 'P$id'

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();   
        $client = array();

  
        if(count($result)>0){
            $app->response()->status(200);
            for($i=0; $i<count($result); $i++){

                $id_client = $result[$i]['CardCode'];

                $client = array(
                    "nombre"=> utf8_encode($result[$i]['CardName']),
                    "direccion"=> utf8_encode($result[$i]['Address']),
                    "Ciudad"=> utf8_encode($result[$i]['City']),
                    "telefono"=> utf8_encode($result[$i]['Phone1']),
                    "celular"=> utf8_encode($result[$i]['Cellular'])
                );

            }
            
            array_push($response, $client);
        
        }else{


            //Nutramerican
            $sql2= "


        SELECT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\", T0.\"Cellular\",  T2.\"SlpName\", T3.\"ListNum\" FROM \"NUTRAMERICAN_PHARMA\".\"OCRD\"  T0 
        INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OSLP\"  T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
        INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OPLN\" T3 ON T0.\"ListNum\" = T3.\"ListNum\" 
        WHERE T0.\"CardCode\" = 'P$id'

    
    ";
    
    
    
        $stmt2 = $dbh->prepare($sql2);
        $stmt2 -> execute();
        $result2 = $stmt2->fetchAll();

  
        if(count($result2)>0){
            $app->response()->status(200);
            for($i=0; $i<count($result2); $i++){


                

                
                

                

                

                $client = array(
                    "nombre"=> utf8_encode($result2[$i]['CardName']),
                    "direccion"=> utf8_encode($result2[$i]['Address']),
                    "Ciudad"=> utf8_encode($result2[$i]['City']),
                    "telefono"=> utf8_encode($result2[$i]['Phone1']),
                    "celular"=> utf8_encode($result2[$i]['Cellular'])
                );

            }
            
            array_push($response, $client);
        
        }else{
            
            $app->response()->status(404);
        }

            //Nutramerican


        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});




//FIDELIZACION REFERIDO
$app->get('/fidelizareferido/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


        SELECT *from \"SBO_FIDELIZACION\".\"Fidelizacion\" where \"CodigoCliente\" = 'C$id' and \"CodigoReferido\" <> ''

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();   
        $client = array();
        $buys = array();
        $products = array();
        $puntos = array();
        $temp = array();

  
        if(count($result)>0){
            $app->response()->status(200);
            for($i=0; $i<count($result); $i++){

                $id_doc = $result[$i]['DocEntry'];

                $sql2 = "
                SELECT T0.\"DocNum\",T0.\"DocDate\", T0.\"DocTotal\", T0.\"CardName\" FROM \"ELITE_NUTRITION\".\"OINV\" T0
                WHERE T0.\"DocEntry\" = '$id_doc'
                    
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();
               

                for($j=0; $j<count($result2); $j++){
                    $temp = array(
                        "nombre"=>utf8_encode($result2[$j]['CardName']),
                        "total"=>utf8_encode($result2[$j]['DocTotal']),
                        "puntos"=>round($result2[$j]['DocTotal']*0.10),
                    );
                    //array_push($products, $temp);                   
                }
                array_push($response, $temp);
            }
            
            
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});




$app->get('/inventorynFilter', function() use($app){
if (! extension_loaded('pdo_odbc'))
{
die('ODBC extension not enabled / loaded');
}
//Ventas
$sql= "
SELECT T0.\"WhsCode\", T3.\"WhsName\", T1.\"ItemCode\",T1.\"ItemName\", T2.\"ItmsGrpNam\", T0.\"OnHand\", T0.\"IsCommited\", T0.\"OnOrder\", T0.\"MinStock\", T0.\"MaxStock\", T0.\"AvgPrice\", (T0.\"OnHand\" * T0.\"AvgPrice\") AS \"CostInventario\" FROM \"NUTRAMERICAN_PHARMA\".\"OITW\" T0
INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OITM\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\"
INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OITB\" T2 ON T1.\"ItmsGrpCod\" = T2.\"ItmsGrpCod\" 
INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OWHS\"	T3 ON T0.\"WhsCode\" = T3.\"WhsCode\"
WHERE T1.\"SellItem\" = 'Y' AND  T1.\"ItmsGrpCod\"  NOT IN (100) AND T0.\"WhsCode\" NOT IN (1051, 1056,1506,1511,1516,1521,2006,2506,3006,3506) AND  T3.\"WhsName\" LIKE 'PRODUCCION - PRODUCTO TERMINADO'
";
$username = "SYSTEM";
$password = "B1HanaAdmin";
$dsn = "odbc:serverara";
$queryString = $sql;
try {
$dbh = new PDO($dsn, $username, $password);
$stmt = $dbh->prepare($queryString);
$stmt -> execute();
$result = $stmt->fetchAll();
$resp = array();      
$vxr = array();
for($i=0; $i<count($result); $i++){  
$vxr= array(
"cod_bodega"=>intval($result[$i]['WhsCode']),
"nom_bodega"=>utf8_encode($result[$i]['WhsName']),
"cod_item"=>intval($result[$i]['ItemCode']),
"nom_item"=>utf8_encode($result[$i]['ItemName']),                    
"categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
"stock"=>intval($result[$i]['OnHand']),
"definido"=>intval($result[$i]['IsCommited']),
"pedido"=>intval($result[$i]['OnOrder']),
"stock_min"=>intval($result[$i]['MinStock']),
"stock_max"=>intval($result[$i]['MaxStock']),
"precio"=>intval($result[$i]['AvgPrice']),
"valorizado"=>intval($result[$i]['CostInventario']),
);
array_push($resp,  $vxr);            
}     


if(count($resp)) $app->response()->status(200);
else {
if(count($resp)) $app->response()->status(400);
$resp=array("mensaje"=>"No encontrado");
}

echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}
catch (Exception $e) {
echo $e->getMessage();
}
});






//Inventario Nutramerican 
$app->get('/inventoryn', function() use($app){

    


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"WhsCode\", T3.\"WhsName\", T1.\"ItemCode\",T1.\"ItemName\", T2.\"ItmsGrpNam\", T0.\"OnHand\", T0.\"IsCommited\", T0.\"OnOrder\", T0.\"MinStock\", T0.\"MaxStock\", T0.\"AvgPrice\", (T0.\"OnHand\" * T0.\"AvgPrice\") AS \"CostInventario\" FROM \"NUTRAMERICAN_PHARMA\".\"OITW\" T0
INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OITM\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\"
INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OITB\" T2 ON T1.\"ItmsGrpCod\" = T2.\"ItmsGrpCod\" 
INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OWHS\"	T3 ON T0.\"WhsCode\" = T3.\"WhsCode\"
WHERE T1.\"SellItem\" = 'Y' AND  T1.\"ItmsGrpCod\"  NOT IN (100) AND T0.\"WhsCode\" NOT IN (1051, 1056,1506,1511,1516,1521,2006,2506,3006,3506)
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            
            

                $vxr= array(
                    "cod_bodega"=>intval($result[$i]['WhsCode']),
                    "nom_bodega"=>utf8_encode($result[$i]['WhsName']),
                    "cod_item"=>intval($result[$i]['ItemCode']),
                    "nom_item"=>utf8_encode($result[$i]['ItemName']),                    
                    "categoria"=>utf8_encode($result[$i]['ItmsGrpNam']),
                    "stock"=>intval($result[$i]['OnHand']),
                    "definido"=>intval($result[$i]['IsCommited']),
                    "pedido"=>intval($result[$i]['OnOrder']),
                    "stock_min"=>intval($result[$i]['MinStock']),
                    "stock_max"=>intval($result[$i]['MaxStock']),
                    "precio"=>intval($result[$i]['AvgPrice']),
                    "valorizado"=>intval($result[$i]['CostInventario']),
                );

           array_push($resp,  $vxr);            
        }     
                 
        
        if(count($resp)) $app->response()->status(200);
        else {
            if(count($resp)) $app->response()->status(400);
            $resp=array("mensaje"=>"No encontrado");
        }
       
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});



//inventario empaque
$app->get('/inventorypack', function() use($app){

    


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"ItemCode\", T0.\"ItemName\", T2.\"OnHand\", T2.\"IsCommited\", (T2.\"OnHand\" - T2.\"IsCommited\") AS \"Disponible\", T2.\"OnOrder\", T2.\"MinStock\", T2.\"MaxStock\", T1.\"ItmsGrpCod\", T1.\"ItmsGrpNam\" FROM \"NUTRAMERICAN_PHARMA\".\"OITM\" T0  
    LEFT JOIN \"NUTRAMERICAN_PHARMA\".\"OITB\" T1 ON T0.\"ItmsGrpCod\" = T1.\"ItmsGrpCod\" 
    LEFT JOIN \"NUTRAMERICAN_PHARMA\".\"OITW\" T2 ON T0.\"ItemCode\" = T2.\"ItemCode\"
    WHERE T1.\"ItmsGrpCod\" = 112 and T2.\"WhsCode\" IN (1010)
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            


$vxr= array(
"cod_item"=>utf8_encode($result[$i]['ItemCode']),
"nom_item"=>utf8_encode($result[$i]['ItemName']),
"stock"=>utf8_encode($result[$i]['OnHand']),
"definido"=>utf8_encode($result[$i]['IsCommited']),
"disponible"=>utf8_encode($result[$i]['Disponible']),
"solicitado"=>utf8_encode($result[$i]['OnOrder']),
"minimo"=>utf8_encode($result[$i]['MinStock']),
"maximo"=>utf8_encode($result[$i]['MaxStock']),
"categoria"=>utf8_encode($result[$i]['ItmsGrpNam'])
);
array_push($resp,  $vxr);            
}     


if(count($resp)>0) $app->response()->status(200);
else {
$app->response()->status(404);
$resp=array("mensaje"=>"No encontrado");
}          

echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}
catch (Exception $e) {
echo $e->getMessage();
}
});


//item x producto
$app->get('/itemxproduct/param', function() use($app){

    $cod = $app->request()->params('cod');


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "

    SELECT T0.\"Code\", T0.\"Name\", T1.\"Father\",T1.\"Code\" AS \"CodMp\",  T2.\"ItemName\" FROM \"NUTRAMERICAN_PHARMA\".\"OITT\" T0  
    INNER JOIN \"NUTRAMERICAN_PHARMA\".\"ITT1\" T1 ON T0.\"Code\" = T1.\"Father\" 
    INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OITM\" T2 ON T1.\"Code\" = T2.\"ItemCode\" 
    INNER JOIN \"NUTRAMERICAN_PHARMA\".\"OWHS\" T3 ON T1.\"Warehouse\" = T3.\"WhsCode\" WHERE T0.\"Code\" = '$cod' and  T3.\"WhsCode\"  = 1010
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();      
        $vxr = array();
    
        for($i=0; $i<count($result); $i++){  
            


$vxr= array(
"cod_item"=>utf8_encode($result[$i]['Code']),
"nom_item"=>utf8_encode($result[$i]['Name']),
"cod_mp"=>utf8_encode($result[$i]['CodMp']),
"empaque"=>utf8_encode($result[$i]['ItemName'])
);
array_push($resp,  $vxr);            
}     


if(count($resp)>0) $app->response()->status(200);
else {
$app->response()->status(404);
$resp=array("mensaje"=>"No encontrado");
}          

echo json_encode($resp, JSON_UNESCAPED_UNICODE);
}
catch (Exception $e) {
echo $e->getMessage();
}
});


//FIDELIZACION cliente V2
$app->get('/fidelizaclientev2/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "
        SELECT *, (\"PuntoCliente\" + \"PuntoReferido\") \"TotalPuntos\" from \"SBO_FIDELIZACION\".\"Fidelizacion\" where \"CodigoCliente\" = 'C$id'
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();  
        $temp = array(); 
        $compras = array();
        
       
  
        if(count($result)>0){
            $app->response()->status(200);        

            for($i=0; $i<count($result); $i++){

                $id_doc = $result[$i]['DocEntry'];
                $fecvence = $result[$i]['FechaVencPuntos'];

                $sql2 = "
                SELECT T0.\"DocEntry\", T0.\"DocNum\",T0.\"DocDate\", T0.\"DocTotal\" FROM \"ELITE_NUTRITION\".\"OINV\" T0
                WHERE T0.\"DocEntry\" = '$id_doc'
                    
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();
                


                for($j=0; $j<count($result2); $j++){






                    $sql3 = "
                    SELECT T0.\"ItemCode\",T0.\"Dscription\", T0.\"Quantity\", T0.\"LineTotal\" FROM \"ELITE_NUTRITION\".\"INV1\" T0
                    WHERE T0.\"DocEntry\" = '$id_doc'
                    
                    ";
                    $stmt3 = $dbh->prepare($sql3);
                    $stmt3 -> execute();
                    $result3 = $stmt3->fetchAll();
                    $temp2 = array();
                    $compras = array();
                    for($k=0; $k<count($result3); $k++){
                    
                        $temp2 = array(
                            "cod_item"=> utf8_encode($result3[$k]['ItemCode']),
                            "producto"=> utf8_encode($result3[$k]['Dscription']),
                            "cantidad"=> utf8_encode($result3[$k]['Quantity']),
                            "total_linea"=> utf8_encode(round($result3[$k]['LineTotal']*1.19))
                        );
                        array_push($compras, $temp2);
                    }
                    

                    $temp = array(
                        "vence"=> utf8_encode($fecvence),
                        "numero"=> $result2[$j]['DocNum'],
                        "fecha"=> utf8_encode($result2[$j]['DocDate']),
                        "precio"=> utf8_encode($result2[$j]['DocTotal']),
                        "puntos"=> utf8_encode($result[$i]['TotalPuntos']),
                        "detalle"=>$compras
                    );

                    

                    
                    
                }
                array_push($response, $temp);
            }
            
            
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

   
});

//VERSION 3
$app->get('/fidelizaclientev3/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "
        SELECT *, (\"PuntoCliente\" + \"PuntoReferido\") \"TotalPuntos\" from \"SBO_FIDELIZACION\".\"Fidelizacion\" where \"CodigoCliente\" = 'C$id'
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();  
        $temp = array(); 
        $compras = array();
        
       
  
        if(count($result)>0){
            $app->response()->status(200);        

            for($i=0; $i<count($result); $i++){

                $id_doc = $result[$i]['DocEntry'];
                $fecvence = $result[$i]['FechaVencPuntos'];

                $sql2 = "
                SELECT T0.\"DocEntry\", T0.\"DocNum\",T0.\"DocDate\", T0.\"DocTotal\" FROM \"BONOS\".\"OINV\" T0
                WHERE T0.\"DocEntry\" = '$id_doc'
                    
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();
                


                for($j=0; $j<count($result2); $j++){






                    $sql3 = "
                    SELECT T0.\"ItemCode\",T0.\"Dscription\", T0.\"Quantity\", T0.\"LineTotal\" FROM \"BONOS\".\"INV1\" T0
                    WHERE T0.\"DocEntry\" = '$id_doc'
                    
                    ";
                    $stmt3 = $dbh->prepare($sql3);
                    $stmt3 -> execute();
                    $result3 = $stmt3->fetchAll();
                    $temp2 = array();
                    $compras = array();
                    for($k=0; $k<count($result3); $k++){
                    
                        $temp2 = array(
                            "cod_item"=> utf8_encode($result3[$k]['ItemCode']),
                            "producto"=> utf8_encode($result3[$k]['Dscription']),
                            "cantidad"=> utf8_encode($result3[$k]['Quantity']),
                            "total_linea"=> utf8_encode(round($result3[$k]['LineTotal']*1.19))
                        );
                        array_push($compras, $temp2);
                    }
                    

                    $temp = array(
                        "vence"=> utf8_encode($fecvence),
                        "numero"=> $result2[$j]['DocNum'],
                        "fecha"=> utf8_encode($result2[$j]['DocDate']),
                        "precio"=> utf8_encode($result2[$j]['DocTotal']),
                        "puntos"=> utf8_encode($result[$i]['TotalPuntos']),
                        "detalle"=>$compras
                    );

                    

                    
                    
                }
                array_push($response, $temp);
            }
            
            
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});



//FIDELIZACION cliente detalle V2
$app->get('/fidelizaclientedetv2/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "


        SELECT *from \"SBO_FIDELIZACION\".\"Fidelizacion\" where \"CodigoCliente\" = 'C$id' and \"CodigoReferido\" = ''

    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();  
        $temp = array(); 
        $compras = array();
        
       
  
        if(count($result)>0){
            $app->response()->status(200);        

            for($i=0; $i<count($result); $i++){

                $id_doc = $result[$i]['DocEntry'];

                $sql2 = "
                SELECT T0.\"DocNum\",T0.\"DocDate\", T0.\"DocTotal\" FROM \"BONOS\".\"OINV\" T0
                WHERE T0.\"DocEntry\" = '$id_doc'
                    
                ";
                $stmt2 = $dbh->prepare($sql2);
                $stmt2 -> execute();
                $result2 = $stmt2->fetchAll();
                


                for($j=0; $j<count($result2); $j++){
                    
                    $temp = array(
                        "numero"=> $result2[$j]['DocNum'],
                        "fecha"=> utf8_encode($result2[$j]['DocDate']),
                        "precio"=> utf8_encode($result2[$j]['DocTotal']),
                        "puntos"=> utf8_encode($result2[$j]['DocTotal']*0.10),
                        "vence"=> utf8_encode($result[$i]['FechaVencPuntos'])
                    );
                    
                    
                }
                array_push($response, $temp);
            }
            
            
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});


//lotes
$app->get('/lotes', function() use($app){


    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "
    SELECT T1.\"ItemCode\", T0.\"ItemName\", T1.\"BatchNum\", T1.\"InDate\", T1.\"ExpDate\", T1.\"Quantity\", T2.\"WhsName\" FROM \"ELITE_NUTRITION\".\"OITM\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"OIBT\" T1 ON T0.\"ItemCode\" = T1.\"ItemCode\" 
    LEFT JOIN \"ELITE_NUTRITION\".\"OWHS\" T2 ON T1.\"WhsCode\" = T2.\"WhsCode\" 
    WHERE T1.\"Quantity\" > 0 
    GROUP BY 
    ORDER BY T1.\"ExpDate\"   
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();  
        $temp = array(); 
        $compras = array();
        
       
  
        if(count($result)>0){
            $app->response()->status(200);        

            for($i=0; $i<count($result); $i++){

                    $temp = array(
                        "item"=> utf8_encode($result[$i]['ItemCode']), 
                        "producto"=> utf8_encode($result[$i]['ItemName']),                       
                        "lote"=> utf8_encode($result[$i]['BatchNum']),
                        "fecha"=> utf8_encode($result[$i]['InDate']),
                        "vencimiento"=> utf8_encode($result[$i]['ExpDate']),
                        "cantidad"=> utf8_encode($result[$i]['Quantity']),
                        "almacen"=> utf8_encode($result[$i]['WhsName']),
                    );
                    
                array_push($response, $temp);
            }
            
            
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});


//DINERO A DIARIO
$app->get('/ddiario/param', function() use($app){

    

    $id = $app->request()->params('id');

    
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    //Ventas
    $sql= "
        SELECT T0.\"CardCode\", T0.\"CardName\", T0.\"Address\", T0.\"Phone1\", T0.\"Cellular\", T0.\"City\", T0.\"E_Mail\" FROM \"ELITE_NUTRITION\".\"OCRD\" T0 
        WHERE T0.\"U_ENG_DineroDiario\" = 'SI' AND T0.\"CardCode\" = 'C$id'    
    ";
    
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $response = array();   
        $client = array();
        $buys = array();
        $products = array();
        $puntos = array();
        $temp = array();

  
        if(count($result)>0){
            $app->response()->status(200);
            for($i=0; $i<count($result); $i++){ 
                $client = array(
                    "codigo"=> utf8_encode($result[$i]['CardCode']),
                    "nombre"=> utf8_encode($result[$i]['CardName']),
                    "direccion"=> utf8_encode($result[$i]['Address']),
                    "telefono"=> utf8_encode($result[$i]['Phone1']),
                    "celular"=> utf8_encode($result[$i]['Cellular']),
                    "Ciudad"=> utf8_encode($result[$i]['City']),
                    "email"=> utf8_encode($result[$i]['E_Mail'])
                );
            }
            
            array_push($response, $client);
        
        }else{
            $app->response()->status(404);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
});

//Datos Clientes por rangos de fecha
$app->get('/customerv2', function() use($app){

    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');
    $nit = $app->request()->params('nit');
    $id = $app->request()->params('id');

    $doc = 'C'.$nit;
    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= "
    SELECT T1.\"SeriesName\", T0.\"DocNum\", T0.\"DocDate\",  T0.\"CardCode\",T0.\"Address2\", T0.\"Comments\", T0.\"DocTotal\", T0.\"PeyMethod\", T0.\"ShipToCode\" FROM \"ELITE_NUTRITION\".\"OINV\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"NNM1\" T1 ON T0.\"Series\" = T1.\"Series\" 
    INNER JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON T0.\"CardCode\" = T2.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\"
    WHERE T0.\"DocDate\"  between '$ini' and '$fin' and  T0.\"CardCode\"  = '$doc' AND T2.\"U_ENG_BraOffSeller\" = '$id'
    UNION
    SELECT T1.\"SeriesName\", T0.\"DocNum\", T0.\"DocDate\",  T0.\"CardCode\",T0.\"Address2\", T0.\"Comments\", T0.\"DocTotal\" * -1, T0.\"PeyMethod\", T0.\"ShipToCode\" FROM \"ELITE_NUTRITION\".\"ORIN\" T0  
    INNER JOIN \"ELITE_NUTRITION\".\"NNM1\" T1 ON T0.\"Series\" = T1.\"Series\" 
    INNER JOIN \"ELITE_NUTRITION\".\"CRD1\" T2 ON T0.\"CardCode\" = T2.\"CardCode\"  AND T0.\"ShipToCode\" = T2.\"Address\"
    WHERE T0.\"DocDate\"  between '$ini' and '$fin' and  T0.\"CardCode\"  = '$doc'  AND T2.\"U_ENG_BraOffSeller\" = '$id'
    ORDER BY T0.\"DocDate\"

    ";
    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        
        if(count($result)>0){
            $app->response()->status(200);
            for($i=0; $i<count($result); $i++){ 
                

                $pedido = array();
                $id_cliente = $result[$i]['CardCode'];
                $tipo = $result[$i]['SeriesName'];
                $numero = $result[$i]['DocNum'];
                $nuevo_valor = round($result[$i]['DocTotal']/1.19);


                $cedula = substr($result[$i]['CardCode'], 1);
            //    $dir_envio = str_replace("\r", ",", $result[$i]['Address2']);
                $dir_envio= $result[$i]['Address2'];
        // $temp=explode('\r', $result[$i]['Address2']);
                $temp= preg_split('/\R/', $result[$i]['Address2']);
                $dir_envio= $temp[0];

                

                $sql3= "
                        SELECT T1.\"Dscription\", T1.\"Quantity\" FROM \"ELITE_NUTRITION\".\"OINV\" T0  
                        INNER JOIN \"ELITE_NUTRITION\".\"INV1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
                        INNER JOIN \"ELITE_NUTRITION\".\"NNM1\" T2 ON T0.\"Series\" = T2.\"Series\" 
                        WHERE T2.\"SeriesName\" = '$tipo' and T0.\"DocNum\" = '$numero'
                    ";
                    $stmt3 = $dbh->prepare($sql3);
                    $stmt3 -> execute();
                    $result3 = $stmt3->fetchAll();
                    $temp=array();
                foreach ($result3 as $key=>$value) { 
                    $temp = array(
                        "nombre_item"=>utf8_encode($value['Dscription']),
                        "contidad_item"=>$value['Quantity']
                );  
                                                    
                array_push($pedido,$temp);
                }

        


            array_push($resp, array(
                    "prefijo"=>$result[$i]['SeriesName'],
                    "fecha"=>$result[$i]['DocDate'],
                    "numero"=>$result[$i]['DocNum'],
                    "valor_pedido"=>$nuevo_valor,
                    "metodo_pago"=>utf8_encode($result[$i]['PeyMethod']),
                    "pedido"=>$pedido
                ));

            }
            //var_dump($resp);
        }else{
            $app->response()->status(200);       
            array_push($resp);
        }
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);

    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

});


/** Ordenes por vendedor producto cantidad */
$app->get('/orders/params', function() use($app){
    $ini = $app->request()->params('ini');
    $fin = $app->request()->params('fin');

    
    if (! extension_loaded('pdo_odbc'))
    {
        die('ODBC extension not enabled / loaded');
    }
    $sql= " 
        SELECT T1.\"Dscription\", T1.\"Quantity\", T1.\"Price\", T1.\"Quantity\" * T1.\"Price\" AS \"TotalLinea\", T0.\"SlpCode\", T0.\"CardCode\", T0.\"CardName\"  FROM \"ELITE_NUTRITION\".\"ORDR\" T0  
        INNER JOIN \"ELITE_NUTRITION\".\"RDR1\" T1 ON T0.\"DocEntry\" = T1.\"DocEntry\" 
        WHERE T0.\"DocDate\" between '$ini' and '$fin' AND T0.\"U_ENG_Motivo_Cierro\" = 'NA' AND T0.\"CANCELED\" = 'N' AND T1.\"Price\" >= '1'
    ";

    
    $username = "SYSTEM";
    $password = "B1HanaAdmin";
    $dsn = "odbc:serverara";
    $queryString = $sql;
    try {
        $dbh = new PDO($dsn, $username, $password);
        $stmt = $dbh->prepare($queryString);
        $stmt -> execute();
        $result = $stmt->fetchAll();
        $resp = array();
       
        if(count($result) > 0){
            $app->response()->status(200); 
            for($i=0; $i<count($result); $i++){
                
                array_push($resp, array(
                    "producto"=>utf8_encode($result[$i]['Dscription']),
                    "cantidad"=>round($result[$i]['Quantity']),
                    "valor"=>round($result[$i]['Price']),
                    "total"=>round($result[$i]['TotalLinea']),
                    "cod_vendedor"=>round($result[$i]['SlpCode']),
                    "cliente"=>utf8_encode($result[$i]['CardName'])

                ));
            }

            $result = groupArray($resp,'producto');
            $temp=array();
            for ($i=0; $i < count($result); $i++) { 
                $item=$result[$i]['data'];
                $precio=0;
                $cantidad=0;
                foreach ($item as $key => $value) {
                    $precio += $value['total'];
                    $cantidad += $value['cantidad'];
            }


            $vendors=array();
            $clientesArray = array();
            $vendedores = groupArray($item,'cod_vendedor');
            for ($j=0; $j < count($vendedores); $j++) { 
                $itemVendor=$vendedores[$j]['data'];
                $name=$vendedores[$j]['cod_vendedor'];
                
                $preciov=0;
                $cantidadv=0;
                foreach ($itemVendor as $key => $value) {
                    $preciov += $value['total'];
                    $cantidadv += $value['cantidad'];
                      $cliente=$value['cliente']; 
                }
                array_push($vendors, array("cantidad"=>$cantidadv, "total"=>$preciov, "cod_vendor"=>$name, "clientes"=>$itemVendor));
            }

            $fila= array("producto"=>$result[$i]['producto'], "cantidad"=>$cantidad, "total"=>$precio, "p"=>$vendors);
                        array_push($temp, $fila);
                        }
                        // $result= $result[0]['data'];
                        $resp= $temp;
            

        }else {
            $app->response()->status(200);       
            array_push($resp);
        }
    
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        

    } catch(Exception $e){
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});

/** FIN RUTA STEVEN */


//Inicia el Api
$app->run();

function groupArray($array,$groupkey)
{
 if (count($array)>0)
 {
 	$keys = array_keys($array[0]);
 	$removekey = array_search($groupkey, $keys);		if ($removekey===false)
 		return array("Clave \"$groupkey\" no existe");
 	else
 		unset($keys[$removekey]);
 	$groupcriteria = array();
 	$return=array();
 	foreach($array as $value)
 	{
 		$item=null;
 		foreach ($keys as $key)
 		{
 			$item[$key] = $value[$key];
 		}
 	 	$busca = array_search($value[$groupkey], $groupcriteria);
 		if ($busca === false)
 		{
 			$groupcriteria[]=$value[$groupkey];
 			$return[]=array($groupkey=>$value[$groupkey],'data'=>array());
 			$busca=count($return)-1;
 		}
 		$return[$busca]['data'][]=$item;
 	}
 	return $return;
 }
 else
 	return array();
}


//Notas
//1. subir archivos al servidor buscar-> uploadVisitas
//$_POST['observaciones'] y $app->request()->post('observaciones') es lo mismo
// htmlspecialchars() elimina los caracteres especiales que generan error en las consultas update e insert de mysql

//2. query fecha, solo mes y año. En mysql.
// SELECT DATE_FORMAT(fecha, '%Y %m') AS AÑO_MES FROM `tb_maestro_planilla` WHERE `mensajero`->'$.id'=52698507
// GROUP BY AÑO_MES

//Poner esta función en otro archivo. Se usa en la consulta ventas trimestral.
function nombremes($mes){
setlocale(LC_TIME, 'spanish');  
$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
return $nombre;
} 

