<?php

class  CURL{
public static function consumePostApi($url,$data){
$payload = json_encode($data);
// Prepare new cURL resource
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
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
public static function Messages($url,$data){
// Prepare new cURL resource
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Authorization: Key=AAAAl46i4OM:APA91bE3LkR4EeGs_ob9ngW48dpdrZHODpXQgMp1Ca-IlJNk-xinOlFewlNzZakT6KI6XeayiPW22fBvKpQi1pkKld9sqCBarXgM81YLhckrP3AqdF5oWMDx98LcKmMmhOsuPNz2kmL7'
));
$result = curl_exec($ch);
curl_close($ch);
return $result;
}
public static function consumePutApi($url,$data){
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
public static function consumeDeleteApi($url){
$ch = curl_init();   
curl_setopt($ch, CURLOPT_URL, $url);   
curl_setopt($ch, CURLOPT_HEADER, false);   
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');   
// curl_setopt($curl, CURLOPT_HTTPHEADER, array(
// 'Content-Type: application/json',
// 'Content-Length: ' . strlen($payload))
// );
$data = curl_exec($ch);   
$response=json_decode($data);
curl_close($ch);   
return $response;
}
public static function consumeGetApi($url){
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_TIMEOUT, 20);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
$http_code = curl_getinfo($curl , CURLINFO_HTTP_CODE);
// Set HTTP Header for POST request 
// curl_setopt($curl, CURLOPT_HTTPHEADER, array(
// 'Content-Type: application/json',
// 'Content-Length: ' . strlen($payload))
// );
$response = curl_exec($curl);
$data=json_decode($response);
curl_close($curl);    
return $data;
}
    
}
