<?php

//загрузка из .env файла токена телеграмм бота, так нужно, чтобы не хранить токен прямо в коде, потому что это очень уязвимо
//Хорошей практикой считается хранить подобные api ключи(токены) либо в защищенном хранилище либо в конфигруационных файлах, которые не будут включены в репозиторий
/////////////////////////////////////////////
$env = fopen('../.env', 'r');
if ($env) {
    while (($line = fgets($env)) !== false) {
        putenv($line);
    }
    fclose($env);
}

//////////////////////////////////////////////

$token = getenv("BOT_TOKEN");
$offset = 0; //переменная где будет хранится значение айди от которого будет запрос идти дальше, чтобы не останавливаться на старых апдейтах
while (true) {
    $result = getUpdates(); //получаем апдейты

    if (!empty($result)) { //если они не пустые

        foreach ($result as $update) { //foreach это по сути упрощение for, который проходится по всем членам ассоциотивного массива и дает сразу объект
            //$update["update_id"] != $offset игнорируем адпейт со значением оффсета, потому что телеграмм будет кидать один последний недавний апдейт в апдейтах даже если фактически это не апдейт.
            if ($update["message"]["text"] == "/start" && $update["update_id"] != $offset) {
                sendMessage($update["message"]["from"]["id"], "Эй ниггер рэп это кал");
            }
            if ($update["message"]["text"] == "/about" && $update["update_id"] != $offset) {
                sendMessage($update["message"]["from"]["id"], "Как бодрость духа например?");
            }
            $offset = $update["update_id"]; //выставляем новый оффсет от которого мы будем брать новые сообщения
        }
    }
    sleep(5); //задержка между чтением обновлений, можно поставить меньше для быстроты работы
}

//Функция получения апдейтов тг
function getUpdates()
{
    global $token, $offset;
    $body = ["offset" => $offset]; //тело post запроса
    $ch = curl_init("https://api.telegram.org/bot" . $token . "/getUpdates");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_close($ch);
    return json_decode(curl_exec($ch), true)["result"];
}

//Функция отправления сообщения в тг
function sendMessage($chatId, $text)
{
    global $token;
    $body = [
        "chat_id" => $chatId,
        "text" => $text
    ]; //тело post запроса
    $ch = curl_init("https://api.telegram.org/bot" . $token . "/sendMessage");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_close($ch);
    return json_decode(curl_exec($ch), true);
}
