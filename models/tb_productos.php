<?php


class tb_productos
{
public function __construct($cn) {
$this->cn = $cn;
}

public function getData()
{
try{
$rows= array();    
$select=$this->cn->query("SELECT cod_item, nom_item FROM tb_productos_activos");
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





