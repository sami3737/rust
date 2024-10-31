<?php

$g2aEmail = ''; // customer email
$apiHash = ''; // customer API hash
$apiSecret = ''; // customer API secret

$apiKey = hash('sha256', $apiHash . $g2aEmail . $apiSecret);

echo 'Authorization: ' . $apiHash . ', ' . $apiKey . PHP_EOL;

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://sandboxapi.g2a.com/v1/products",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: $apiHash, $apiKey",
        "Content-Type: application/json",
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}