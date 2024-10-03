<?php
require 'config.php';

$apiUrl = "https://api.telegram.org/bot" . TOKEN;

$offset = 0; 
while (true) {
    $response = file_get_contents($apiUrl . "/getUpdates?offset=$offset");
    $updates = json_decode($response);

    foreach ($updates->result as $update) {
        $chatId = $update->message->chat->id;
        $messageText = $update->message->text;
        $offset = $update->update_id + 1; 

        switch ($messageText) {
            case "/start":
                $responseText = "Olá! Bem-vindo ao meu bot do Telegram! Use /help para ver os comandos disponíveis.";
                sendMessage($chatId, $responseText);
                break;
                
            case "/help":
                $responseText = "Comandos disponíveis:\n/start - Iniciar o bot\n/help - Mostrar este menu\n/about - Informações sobre o bot\n/joke - Receber uma piada aleatória";
                sendMessage($chatId, $responseText);
                break;

            case "/about":
                $responseText = "Este é um bot simples do Telegram criado para demonstrar como usar a API do Telegram em PHP.";
                sendMessage($chatId, $responseText);
                break;

            case "/joke":
                $joke = getRandomJoke();
                sendMessage($chatId, $joke);
                break;

            default:
                if (stripos($messageText, "oi") !== false) {
                    sendMessage($chatId, "Oi! Como posso ajudar você?");
                } 
                else {
                    sendMessage($chatId, "Desculpe, não entendi. Digite /help para ver os comandos.");
                }
                break;
        }
    }

    sleep(1);
}

function sendMessage($chatId, $text) {
    global $apiUrl;
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
    ];
    file_get_contents($apiUrl . '/sendMessage?' . http_build_query($data));
}

function getRandomJoke() {
    $jokes = [
        "Por que o lápis ficou triste? Porque ele estava sem ponta!",
        "O que a abelha disse para a flor? 'Você está linda hoje!'",
        "Por que os pássaros não usam Facebook? Porque já têm Twitter!"
    ];
    return $jokes[array_rand($jokes)];
}
