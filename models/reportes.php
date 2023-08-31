<?php
require_once('./models/querys.php'); 
class Reportes
{
public function __construct( $cn = null) { $this->cn = $cn;}
function _groupArray($array,$groupkey)
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

public function superReport($ini, $fin)
{
try{
$queryString=  QUERYS::superReport($ini, $fin);
$stmt = $this->cn->prepare($queryString);
$stmt -> execute();
$resp = $stmt->fetchAll();
$result = $this->_groupArray($resp,'DocNum');
$temp=array();
$response = array();
for ($i=0; $i < count($result); $i++) { 
$detalle=$result[$i]['data'];
$factura= $result[$i]['DocNum'];
$total=0;
$cantidad=0;
$detal=array();
foreach ($detalle as $key => $value) {
if( $value['Categoria'] != "SERVICIOS") $total += $value['TOTAL'];
if( ($value['Categoria'] != "SERVICIOS")) $cantidad += $value['Cantidad'];
$SeriesName= utf8_encode($value['SeriesName']);
$DocDate= utf8_encode(explode(" ",$value['DocDate'])[0]);
$isIns= utf8_encode($value['isIns']);
$Dscription= utf8_encode($value['Dscription']);
$Cantidad= utf8_encode($value['Cantidad']);
$Categoria= utf8_encode($value['Categoria']);
array_push($detal,array("SeriesName"=>$SeriesName,"DocDate"=>$DocDate, "isIns"=>$isIns,
"Dscription"=>$Dscription,"Cantidad" =>$Cantidad, "Categoria"=>$Categoria ));
}
$fila= array("id_factura"=>$factura, "cantidad"=>$cantidad, "total"=>$total, "detalle"=>$detal);
array_push($temp,$fila);
}
$sum= array_reduce($temp, function($cantidades, $item) {
    $cantidades += $item['cantidad'];
    return $cantidades;
});
$count= array_reduce($temp, function($cantidades, $item) {
    $cantidades += $item['total'];
    return $cantidades;
});
$response=array("cantidad"=>$sum, "total"=>$count,"detalle"=>$temp);
}
catch(Exception $e){}
return $response;
}


public function superReportProduct($ini, $fin, $producto)
{
try{
$queryString=  QUERYS::superReportP($ini, $fin, $producto);
$stmt = $this->cn->prepare($queryString);
$stmt -> execute();
$resp = $stmt->fetchAll();
$result = $this->_groupArray($resp,'DocNum');
$temp=array();
$response = array();
for ($i=0; $i < count($result); $i++) { 
$detalle=$result[$i]['data'];
$factura= $result[$i]['DocNum'];
$total=0;
$cantidad=0;
$detal=array();
foreach ($detalle as $key => $value) {
if( $value['Categoria'] != "SERVICIOS") $total += $value['TOTAL'];
if( ($value['Categoria'] != "SERVICIOS")) $cantidad += $value['Cantidad'];
$SeriesName= utf8_encode($value['SeriesName']);
$DocDate= utf8_encode(explode(" ",$value['DocDate'])[0]);
$isIns= utf8_encode($value['isIns']);
$Dscription= utf8_encode($value['Dscription']);
$Cantidad= utf8_encode($value['Cantidad']);
$Categoria= utf8_encode($value['Categoria']);
array_push($detal,array("SeriesName"=>$SeriesName,"DocDate"=>$DocDate, "isIns"=>$isIns,
"Dscription"=>$Dscription,"Cantidad" =>$Cantidad, "Categoria"=>$Categoria ));
}
$fila= array("id_factura"=>$factura, "cantidad"=>$cantidad, "total"=>$total, "detalle"=>$detal);
array_push($temp,$fila);
}
$sum= array_reduce($temp, function($cantidades, $item) {
    $cantidades += $item['cantidad'];
    return $cantidades;
});
$count= array_reduce($temp, function($cantidades, $item) {
    $cantidades += $item['total'];
    return $cantidades;
});
$response=array("cantidad"=>$sum, "total"=>$count,"detalle"=>$temp);
}
catch(Exception $e){}
return $response;
}


    
}




