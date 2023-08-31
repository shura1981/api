<?php

class tb_vendedores{
public function __construct( $cn = null) {
$this->cn = $cn;
require_once './models/querys.php';
}
public function getData($id, $ini, $fin)
{
try{
//region ventas del vendedor
$devolucione=0;
$totalDev=0;
$totalVentas=0;
$facturado=0;
$sql= QUERYS::sellerv2_Block1($ini,$fin,$id);
$sqldev=  QUERYS::sellerv2_Block2($ini,$fin,$id);
$stmt2 =$this->cn->prepare($sqldev);
$stmt2->execute();
$result2 = $stmt2->fetchAll();
$stmt = $this->cn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();
if(count($result) > 0){
for($j=0; $j<count($result2); $j++){$totalDev = $totalDev + $result2[$j]['TOTAL']; }
$devoluciones=round($totalDev);
for($i=0; $i<count($result); $i++){$totalVentas = $totalVentas + $result[$i]['TOTAL'];}
$facturado= (round($totalVentas)+ $devoluciones );
}
return $facturado;
//endregion
}
catch(Exception $e){
throw new Exception($e->getMessage());
}finally{
$stmt=null;
$stmt2=null;
}

}




}







