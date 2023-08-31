<?php
class CronJob
{
public function consumePutApi($url,$data){
    $payload = json_encode($data);
    // Prepare new cURL resource
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    // Set HTTP Header for POST request 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload))
    );
    // Submit the POST request
    $result = curl_exec($ch);
    // Close cURL session handle
    curl_close($ch);
    return $result;
    }


}


$c= new CronJob();
$id=123;
$hoy = date('Y-m-d h-i');
$package=array("fecha"=>$hoy, "visita"=>$id);
$url="https://elitenutritiongroup-9385a.firebaseio.com/agenda.json";
$resp= $c->consumePutApi($url, $package);
echo json_encode($resp);




