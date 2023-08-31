<?php
function putFirebase($tabla,$id,$data)
{
$payload = json_encode($data);
// Prepare new cURL resource
$ch = curl_init("https://elitenutritiongroup-9385a.firebaseio.com/$tabla/$id.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//curl_setopt($ch, CURLOPT_POST, true);
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

return array("status"=>"ok", "code"=>200,"body"=>$result);
}

