<?php
class tb_numericas
{

public function __construct($cn) {
$this->cn = $cn;
}

public function Get($id_vendedor,$cod_prod, $fechaI, $fechaF)
{
$result=array();
try{
$select=$this->cn->query("SELECT id,fecha, id_vendedor, nombre_vendedor,sum(clientes_activos) as clientes_activos, sum(clientes_impactados) as clientes_impactados,
sum(total_producto_foco) as total_producto_foco, sum(numerica) as numerica,sum(ponderada) as ponderada, MAX(total_clientes) as total_clientes FROM `tb_numerica` WHERE id_vendedor=$id_vendedor AND producto_foco=$cod_prod AND fecha BETWEEN '$fechaI' AND '$fechaF'");
while($row = $select->fetch_assoc())
{
array_push($result,$row);
}
return $result;   
}
catch(Exception $e){
throw new Exception("Error Processing Request ". $e->getMessage());
}
finally{
$select->free_result();
$select=null;
}
}

public function Insert($json)
{
try{
$id_vendedor=$json['id_vendedor'];
$nombre_vendedor=$json['nombre_vendedor'];
$producto_foco=$json['producto_foco'];
$nombre_producto_foco=$json['nombre_producto_foco'];
$clientes_activos=$json['clientes_activos'];
$clientes_impactados=$json['clientes_impactados'];
$total_producto_foco=$json['total_producto_foco'];
$numerica=$json['numerica'];
$ponderada= $json['ponderada'];
$total_clientes=$json['total_clientes'];
$fecha= $json['fecha'];
$query="INSERT INTO tb_numerica SET id_vendedor=$id_vendedor, nombre_vendedor='$nombre_vendedor',producto_foco=$producto_foco,
nombre_producto_foco='$nombre_producto_foco',clientes_activos=$clientes_activos,clientes_impactados=$clientes_impactados, total_producto_foco=$total_producto_foco,
numerica=$numerica,ponderada=$ponderada, total_clientes=$total_clientes, fecha='$fecha'";
$insert =$this->cn->query($query);
if ($insert) {
$last=$this->cn->insert_id; 
$result = array('status' => 'true', 'message' => 'Creado correctamente', 'id'=>$last);
} else {
$result = array('status' => 'false', 'message' => 'Ocurrió un error '.$this->cn->error);
}
return $result;   
}
catch(Exception $e){
throw new Exception("Error Processing Request ". $e->getMessage());
}
finally{
$insert=null;
}

}











}

