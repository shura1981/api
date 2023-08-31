<?php

class Utils{

public function ObtenerFestivos($mes = null)
{

$FESTIVOS=[
array("mes"=>1,"festivos"=>[1,06]),
array("mes"=>3,"festivos"=>[22]),  
array("mes"=>4,"festivos"=>[1,2]),
array("mes"=>5,"festivos"=>[1,25]),
array("mes"=>6,"festivos"=>[15,22,29]),
array("mes"=>7,"festivos"=>[20]),   
array("mes"=>8, "festivos"=>[7,17]),
array("mes"=>10,"festivos"=>[12]),  
array("mes"=>11,"festivos"=>[2,16]),    
array("mes"=>12,"festivos"=>[8,25])
];



for ($i=0; $i < count($FESTIVOS) ; $i++) { 
$m= $FESTIVOS[$i]["mes"];
if($m==$mes) return $FESTIVOS[$i]["festivos"];
}
}
public function nombremes($mes):string{
setlocale(LC_TIME, 'spanish');  
$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000)); 
return $nombre;
} 

public function numberDay($Day):string{
switch ($Day) {
case 1: return "Lunes";
case 2: return "Martes";
case 3: return "Miércoles";
case 4: return "Jueves";
case 5: return "Viernes";
case 6: return "Sábado";
default:  return "Domingo";
}
} 


}