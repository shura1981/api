
<?php
class tb_usuarios_ventas
{
public function __construct($cn) {
$this->cn = $cn;
}

public function getData()
{
try{
$rows= array();    
$select=$this->cn->query("SELECT id_usuario, usuario FROM tb_usuarios_ventas WHERE categoria='ESPECIALISTAS DE NEGOCIO'");
while($row = $select->fetch_assoc())
{
array_push($rows,$row);
}
}
catch(Exception $e){
throw new Exception("Error Processing Request ". $e->getMessage());
}
finally{
$select->free_result();
$select=null;
}
return $rows;
}

public function getDataFabio()
{
try{
$rows= array();    
$select=$this->cn->query("SELECT id_usuario, usuario FROM tb_usuarios_ventas WHERE categoria='ESPECIALISTAS DE NEGOCIO' AND id_usuario=48");
while($row = $select->fetch_assoc())
{
array_push($rows,$row);
}
}
catch(Exception $e){
throw new Exception("Error Processing Request ". $e->getMessage());
}
finally{
$select->free_result();
$select=null;
}
return $rows;
}





public function getByIdData($id)
{
try{
$rows= array();    
$select=$this->cn->query("SELECT id_usuario, usuario FROM tb_usuarios_ventas WHERE categoria='ESPECIALISTAS DE NEGOCIO' AND id_usuario=$id");
while($row = $select->fetch_assoc())
{
array_push($rows,$row);
}
}
catch(Exception $e){
throw new Exception("Error Processing Request ". $e->getMessage());
}
finally{
$select->free_result();
$select=null;
}
return $rows;
}


}

