<?php
require_once('./models/querys.php'); 
class ProductNumeric 
{
public function __construct( $cn = null) { $this->cn = $cn;}
public function getData($id, $item2, $fechaI, $fechaF)
{
try{
$activos = 0;
$totalpro = 0;
$queryString=  QUERYS::block_numerics_one($id);
$sqlproduct=  QUERYS::block_numerics_two($id, $item2, $fechaI, $fechaF);
$sqlclientesimpactados= QUERYS::block_numerics_three($id, $item2, $fechaI, $fechaF);
$stmt = $this->cn->prepare($queryString);
$stmt -> execute();
$result = $stmt->fetchAll();
$totalClientes = count($result);
for($j=0; $j<$totalClientes; $j++){
$id_cliente = $result[$j]['CardCode'];    
$sql2= QUERYS::block_numerics_four($id_cliente, $item2, $fechaI, $fechaF);
$stmt2 = $this->cn->prepare($sql2);
$stmt2 -> execute();
$result2 = $stmt2->fetchAll(); 
if(count($result2) > 0){   
$activos = $activos + 1;
}
}
$stmt3 = $this->cn->prepare($sqlproduct);
$stmt3 -> execute();
$result3 = $stmt3->fetchAll();
if($result3[0]["TOTAL"])$totalpro=$result3[0]["TOTAL"];
else $totalpro=0;
$stmt4 = $this->cn->prepare($sqlclientesimpactados);
$stmt4 -> execute();
$result4 = $stmt4->fetchAll();
$clientesimpactados = count($result4);  
$numerica = round(($clientesimpactados/$activos)*100);
if($clientesimpactados>0)$ponderada = round($totalpro/$clientesimpactados, 2);
else $ponderada=0;
$item= array("id_vendedor"=>$id, "producto_foco"=> $item2, "clientes_activos"=>$activos, "clientes_impactados"=> $clientesimpactados, "total_producto_foco"=>$totalpro, "numerica"=>$numerica, "ponderada"=>$ponderada, "total_clientes"=>$totalClientes);
}
catch(Exception $e){
// $item= array("mesage"=>$e->getMessage(),"id_vendedor"=>$id, "producto_foco"=> 0, "clientes_activos"=>0, "clientes_impactados"=> 0, "total_producto_foco"=>0, "numerica"=>0, "ponderada"=>0, "total_clientes"=>0);
$item= array("id_vendedor"=>$id, "producto_foco"=> 0, "clientes_activos"=>0, "clientes_impactados"=> 0, "total_producto_foco"=>0, "numerica"=>0, "ponderada"=>0, "total_clientes"=>0);
}
return $item;
}


    
}
