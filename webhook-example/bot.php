<?php
$token = "";
$url = "";
$ch = curl_init("https://api.telegram.org/bot" . $token . "/setWebhook");
$json = json_encode([
   "url" => ""
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json)
));

function onUpdate(HttpRequest $req) {

}