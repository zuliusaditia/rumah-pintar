<?php

function kirimWA($target, $pesan){

$token = TOKEN_FONNTE_KAMU;

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.fonnte.com/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
    'target' => $target,
    'message' => $pesan,
),
    CURLOPT_HTTPHEADER => array(
    "Authorization: $token"
),
));

$response = curl_exec($curl);
curl_close($curl);

return $response;

}