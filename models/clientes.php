<?php

class tb_clientes{
public function __construct( $cn = null) { $this->cn = $cn;}
public function getData($id_cliente)
{
try{
$query= "
SELECT T0.\"CardCode\",T0.\"CardName\", T0.\"Address\", T0.\"City\",T0.\"Phone1\",T0.\"Phone2\", T0.\"Cellular\",  T2.\"SlpName\", T3.\"ListNum\", T0.\"E_Mail\" FROM \"ELITE_NUTRITION\".\"OCRD\"  T0 
INNER JOIN \"ELITE_NUTRITION\".\"OSLP\"  T2 ON T0.\"SlpCode\" = T2.\"SlpCode\" 
INNER JOIN \"ELITE_NUTRITION\".\"OPLN\" T3 ON T0.\"ListNum\" = T3.\"ListNum\" 
WHERE T0.\"CardCode\" = '$id_cliente'
";
$resulset = $this->cn->prepare($query);
$resulset -> execute();
$res = $resulset->fetchAll();
if(count($res)<1) return null;
else {
return  array(
"id_cliente"=>$res[0]['CardCode'],    
"nombres"=>utf8_encode($res[0]['CardName']),
"dirección"=>utf8_encode($res[0]['Address']),
"ciudad"=>utf8_encode($res[0]['City']),
"teléfono"=>$res[0]['Phone1'],
"teléfono2"=>$res[0]['Phone2'],
"celular"=>$res[0]['Cellular'],
"vendedor"=>utf8_encode($res[0]['SlpName']),
"descuento"=>$res[0]['ListNum'],
"email"=>utf8_encode($res[0]['E_Mail'])
);   
}
}
catch(Exception $e){
throw new Exception($e->getMessage());
}finally{
$resulset=null;
}

}


public function getPhones($id_cliente)
{
try{
$query= "
SELECT T0.\"Phone1\",T0.\"Phone2\", T0.\"Cellular\" FROM \"ELITE_NUTRITION\".\"OCRD\"  T0 
WHERE T0.\"CardCode\" = '$id_cliente'
";
$resulset = $this->cn->prepare($query);
$resulset -> execute();
$res = $resulset->fetchAll();
if(count($res)<1) return null;
else {
return  array(
"teléfono"=>$res[0]['Phone1'],
"teléfono2"=>$res[0]['Phone2'],
"celular"=>$res[0]['Cellular']
);   
}
}
catch(Exception $e){throw new Exception($e->getMessage());}
finally{
$resulset=null;
$this->cn=null;
}
}


}







